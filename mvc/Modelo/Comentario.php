<?php
namespace Modelo;

use \PDO;
use \Framework\DW3BancoDeDados;

class Comentario extends Modelo
{
    const BUSCAR_COMENTARIOS = 'SELECT * FROM comentarios WHERE receita_id = ';
    const BUSCAR_AUTOR = 'SELECT * FROM usuarios WHERE id = ?';
    const BUSCAR_RECEITA = 'SELECT * FROM receitas WHERE id = ?';
    const INSERIR = 'INSERT INTO comentarios(mensagem, receita_id, usuario_id, data_publicado) VALUES (?, ?, ?, ?)';
    const DELETAR = 'DELETE FROM comentarios WHERE id = ?';
    const DELETAR_TODOS = 'DELETE FROM comentarios WHERE receita_id = ?';
    const DESC = ' ORDER BY data_publicado DESC';
    const BUSCAR_ID = 'SELECT * FROM comentarios WHERE id = ?';

    private $id;
    private $mensagem;
    private $dataPublicado;
    private $receitaId;
    private $usuarioId;
    private $usuario;

    public function __construct(
        $mensagem = null,
        $receitaId = null,
        $usuarioId = null,
        $dataPublicado = null,
        $id = null
    ) {
        $this->id = $id;
        $this->mensagem = $mensagem;
        $this->setDataPublicado($dataPublicado);
        $this->receitaId = $receitaId;
        $this->usuarioId = $usuarioId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMensagem()
    {
        return $this->mensagem;
    }

    public function getDataPublicado()
    {
        $data = date_create($this->dataPublicado);
        return date_format($data, 'd/m/Y');
    }

    public function getReceitaId()
    {
        return $this->receitaId;
    }

    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    public function getUsuario()
    {
        if ($this->usuario == null) {
            $this->usuario = Usuario::buscarId($this->usuarioId);
        }
        return $this->usuario;
    }

    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
    }

    public function setDataPublicado($dataPublicado)
        {
            $brasileiro = preg_match('/(\d\d)\/(\d\d)\/(\d\d\d\d)/', $dataPublicado, $matches);
            if ($brasileiro) {
                $dataPublicado = "$matches[3]-$matches[2]-$matches[1]";
            }
            $this->dataPublicado = $dataPublicado;
        }

    public function salvar()
    {
        $this->inserir();
    }

    public function inserir()
    {
        DW3BancoDeDados::getPdo()->beginTransaction();
        $comando = DW3BancoDeDados::prepare(self::INSERIR);
        $comando->bindValue(1, $this->mensagem, PDO::PARAM_STR);
        $comando->bindValue(2, $this->receitaId, PDO::PARAM_STR);
        $comando->bindValue(3, $this->usuarioId, PDO::PARAM_INT);
        $comando->bindValue(4, $this->dataPublicado, PDO::PARAM_STR);
        $comando->execute();
        $this->id = DW3BancoDeDados::getPdo()->lastInsertId();
        DW3BancoDeDados::getPdo()->commit();
    }

    public function validarFormularioComentario($mensagem) {
        $erros = [];

        if (strlen($mensagem) < 3) {
            $erros['mensagem'] = 'No mínimo 3 caracteres são aceitos!';
        }
        return $erros;
    }

    public static function buscarId($id)
    {
        $comando = DW3BancoDeDados::prepare(self::BUSCAR_ID);
        $comando->bindValue(1, $id, PDO::PARAM_INT);
        $comando->execute();
        $registro = $comando->fetch();

        if ($registro) {
            return new Comentario(
                        $registro['mensagem'],
                        $registro['receita_id'],
                        $registro['usuario_id'],
                        $registro['data_publicado'],
                        $registro['id'],
            );
        }
    }

    public static function buscarUsuario($usuarioId)
    {
        $comando = DW3BancoDeDados::prepare(self::BUSCAR_AUTOR);
        $comando->bindValue(1, $usuarioId, PDO::PARAM_INT);
        $comando->execute();
        $registro = $comando->fetch();
        return new Usuario(
            $registro['email'],
            $registro['nome'],
            null,
            $registro['id']
        );
    }

    public static function buscarReceita($receitaId)
    {
        $comando = DW3BancoDeDados::prepare(self::BUSCAR_RECEITA);
        $comando->bindValue(1, $receitaId, PDO::PARAM_INT);
        $comando->execute();
        $registro = $comando->fetch();

        return new Receita(
                $registro['titulo'],
                $registro['ingredientes'],
                $registro['passos'],
                $registro['usuario_id'],
                $registro['data_publicado'],
                $registro['id']
            );
    }

    public static function buscarComentarios($receitaId)
    {
        $comando = self::BUSCAR_COMENTARIOS . $receitaId . self::DESC;
        $registros = DW3BancoDeDados::query($comando);
        $comentarios = [];
            if ($registros) {
                foreach ($registros as $registro) {
                    $comentarios[] = new Comentario(
                    $registro['mensagem'],
                    $registro['receita_id'],
                    $registro['usuario_id'],
                    $registro['data_publicado'],
                    $registro['id']
                    );
                }
            }
        return $comentarios;
    }

    public static function destruirComentario($id)
    {
        $comando = DW3BancoDeDados::prepare(self::DELETAR);
        $comando->bindValue(1, $id, PDO::PARAM_STR);
        $comando->execute();
        $registro = $comando->fetch();
    }

    public static function destruirTodosComentario($id)
    {
        $comando = DW3BancoDeDados::prepare(self::DELETAR_TODOS);
        $comando->bindValue(1, $id, PDO::PARAM_STR);
        $comando->execute();
    }
}
