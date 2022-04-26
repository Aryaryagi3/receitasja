<?php
namespace Modelo;

use \PDO;
use \Framework\DW3BancoDeDados;
use \Framework\DW3Sessao;

class Usuario extends Modelo
{
    const BUSCAR_ID = 'SELECT * FROM usuarios WHERE id = ?';
    const BUSCAR_EMAIL = 'SELECT * FROM usuarios WHERE email = ?';
    const INSERIR = 'INSERT INTO usuarios(email, nome, senha) VALUES (?, ?, ?)';
    const CONTAR_USUARIOS = 'SELECT count(id) FROM usuarios';

    private $id;
    private $email;
    private $nome;
    private $senha;
    private $senhaInalterada;

    public function __construct(
        $email = null,
        $nome = null,
        $senhaInalterada = null,
        $id = null
    ) {
        $this->email = $email;
        $this->nome = $nome;
        $this->senhaInalterada = $senhaInalterada;
        $this->senha = password_hash($senhaInalterada, PASSWORD_BCRYPT);
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function verificarSenha($senhaInalterada)
    {
        return password_verify($senhaInalterada, $this->senha);
    }

    public function salvar()
    {
        $this->inserir();
    }

    public function inserir()
    {
        DW3BancoDeDados::getPdo()->beginTransaction();
        $comando = DW3BancoDeDados::prepare(self::INSERIR);
        $comando->bindValue(1, $this->email, PDO::PARAM_STR);
        $comando->bindValue(2, $this->nome, PDO::PARAM_STR);
        $comando->bindValue(3, $this->senha, PDO::PARAM_STR);
        $comando->execute();
        $this->id = DW3BancoDeDados::getPdo()->lastInsertId();
        DW3BancoDeDados::getPdo()->commit();
    }

    public function validarFormularioCriarConta($email, $senha, $nome = null) {
        $erros = [];

        if (strlen($email) < 3) {
            $erros['email'] = 'Seu email é muito curto, no mínimo 3 caracteres são aceitos!';
        }
        if ($nome && strlen($nome) < 3) {
            $erros['nome'] = 'Seu nome é muito curto, no mínimo 3 caracteres são aceitos!';
        }
        if (strlen($senha) < 3) {
            $erros['senha'] = 'Sua senha é muito curta, no mínimo 3 caracteres são aceitos!';
        }
        return $erros;
    }

    public static function contarUsuarios()
    {
        $registros = DW3BancoDeDados::query(self::CONTAR_USUARIOS);
        $total = $registros->fetch();
        return intval($total[0]);
    }

    public static function buscarId($id)
    {
        $comando = DW3BancoDeDados::prepare(self::BUSCAR_ID);
        $comando->bindValue(1, $id, PDO::PARAM_INT);
        $comando->execute();
        $registro = $comando->fetch();
        $usuario = null;
        if ($registro) {
            $usuario = new Usuario(
                $registro['email'],
                $registro['nome'],
                null,
                $registro['id']
            );
            $usuario->senha = $registro['senha'];
        }
        return $usuario;
    }

    public static function buscarEmail($email)
    {
        $comando = DW3BancoDeDados::prepare(self::BUSCAR_EMAIL);
        $comando->bindValue(1, $email, PDO::PARAM_STR);
        $comando->execute();
        $registro = $comando->fetch();
        $usuario = null;
        if ($registro) {
            $usuario = new Usuario(
                $registro['email'],
                $registro['nome'],
                null,
                $registro['id']
            );
            $usuario->senha = $registro['senha'];
        }
        return $usuario;
    }
}
