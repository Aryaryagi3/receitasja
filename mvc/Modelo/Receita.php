<?php
namespace Modelo;

use \PDO;
use \Framework\DW3BancoDeDados;

class Receita extends Modelo
{
    const BUSCAR_ID = 'SELECT * FROM receitas WHERE id = ?';
    const BUSCAR_AUTOR = 'SELECT * FROM usuarios WHERE usuario_id = ?';
    const INSERIR = 'INSERT INTO receitas(titulo, data_publicado, ingredientes, passos, usuario_id) VALUES (?, ?, ?, ?, ?)';
    const ATUALIZAR = 'UPDATE receitas SET titulo = ?, ingredientes = ?, passos = ? WHERE id = ?';
    const BUSCAR_TODOS = 'SELECT * FROM receitas ORDER BY data_publicado ';
    const BUSCAR_RECEITAS = 'SELECT * FROM receitas WHERE usuario_id = ';
    const DELETAR = 'DELETE FROM receitas WHERE id = ?';
    const CONTAR_RECEITAS = 'SELECT count(id) FROM receitas';
    const CONTAR_FILTRO = "SELECT count(id) FROM receitas WHERE ingredientes LIKE lower(";
    const CONTAR_FILTRO2 = ")";
    const FILTRAR_INGREDIENTES = "SELECT * FROM receitas WHERE ingredientes LIKE lower(";
    const FILTRAR_INGREDIENTES2 = ") ORDER BY data_publicado ";
    const LIMIT_OFFSET = ' LIMIT ? OFFSET ?';

    private $id;
    private $titulo;
    private $dataPublicado;
    private $ingredientes;
    private $passos;
    private $usuarioId;
    private $usuario;

    public function __construct(
        $titulo = null,
        $ingredientes = null,
        $passos = null,
        $usuarioId = null,
        $dataPublicado = null,
        $id = null
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->setDataPublicado($dataPublicado);
        $this->ingredientes = $ingredientes;
        $this->passos = $passos;
        $this->usuarioId = $usuarioId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getDataPublicado()
    {
        $data = date_create($this->dataPublicado);
        return date_format($data, 'd/m/Y');
    }

    public function getIngredientes()
    {
        return $this->ingredientes;
    }

    public function getPassos()
    {
        return $this->passos;
    }

    public function getUsuario()
    {
        if ($this->usuario == null) {
            $this->usuario = Usuario::buscarId($this->usuarioId);
        }
        return $this->usuario;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    public function setDataPublicado($dataPublicado)
        {
            $brasileiro = preg_match('/(\d\d)\/(\d\d)\/(\d\d\d\d)/', $dataPublicado, $matches);
            if ($brasileiro) {
                $dataPublicado = "$matches[3]-$matches[2]-$matches[1]";
            }
            $this->dataPublicado = $dataPublicado;
        }

    public function setIngredientes($ingredientes)
    {
        $this->ingredientes = $ingredientes;
    }

    public function setPassos($passos)
    {
        $this->passos = $passos;
    }

    public function salvar()
    {
        if ($this->id == null) {
            $this->inserir();
        } else {
            $this->atualizar();
        }
    }

    public function salvarImagem($imagem) {
        move_uploaded_file($imagem['tmp_name'], 'C:\xampp\htdocs' . URL_IMG . $this->id . '.' . substr($imagem['type'], 6));
    }

    public function inserir()
    {
        DW3BancoDeDados::getPdo()->beginTransaction();
        $comando = DW3BancoDeDados::prepare(self::INSERIR);
        $comando->bindValue(1, $this->titulo, PDO::PARAM_STR);
        $comando->bindValue(2, $this->dataPublicado, PDO::PARAM_STR);
        $comando->bindValue(3, $this->ingredientes, PDO::PARAM_STR);
        $comando->bindValue(4, $this->passos, PDO::PARAM_STR);
        $comando->bindValue(5, $this->usuarioId, PDO::PARAM_INT);
        $comando->execute();
        $this->id = DW3BancoDeDados::getPdo()->lastInsertId();
        DW3BancoDeDados::getPdo()->commit();
    }

    public function atualizar()
    {
        $comando = DW3BancoDeDados::prepare(self::ATUALIZAR);
        $comando->bindValue(1, $this->titulo, PDO::PARAM_STR);
        $comando->bindValue(2, $this->ingredientes, PDO::PARAM_STR);
        $comando->bindValue(3, $this->passos, PDO::PARAM_STR);
        $comando->bindValue(4, $this->id, PDO::PARAM_STR);
        $comando->execute();
    }

    public function validarFormularioReceita($titulo, $ingredientes, $passos, $imagem = null) {
        $erros = [];

        if (strlen($titulo) < 3) {
            $erros['titulo'] = 'No mínimo 3 caracteres são aceitos!';
        }
        if (strlen($ingredientes) < 3) {
            $erros['ingredientes'] = 'No mínimo 3 caracteres são aceitos!';
        }
        if (strlen($passos) < 3) {
            $erros['passos'] = 'No mínimo 3 caracteres são aceitos!';
        }

        if ($imagem == null || substr($imagem['type'], 6) != 'jpeg' || $imagem['size'] <= 0) {
            $erros['imagem'] = 'Você não enviou uma imagem válida!';
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
            return new Receita(
                        $registro['titulo'],
                        $registro['ingredientes'],
                        $registro['passos'],
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

    public static function buscarReceitas($limit = 12, $offset = 0, $filtro = null, $ordem = null)
    {
        if ($ordem == null) {
            $ordem = 'DESC';
        }

        if ($filtro) {
        $preComando = self::FILTRAR_INGREDIENTES . "'%" . $filtro . "%'" . self::FILTRAR_INGREDIENTES2 . $ordem . self::LIMIT_OFFSET;
        $comando = DW3BancoDeDados::prepare($preComando);
                $comando->bindValue(1, $limit, PDO::PARAM_INT);
                $comando->bindValue(2, $offset, PDO::PARAM_INT);
                $comando->execute();
                $registros = $comando->fetchAll();
                $receitas=[];

                foreach ($registros as $registro) {
                    $receitas[] = new Receita(
                        $registro['titulo'],
                        $registro['ingredientes'],
                        $registro['passos'],
                        $registro['usuario_id'],
                        $registro['data_publicado'],
                        $registro['id']
                    );
                }
                return $receitas;
        } else {
        $preComando = self::BUSCAR_TODOS . $ordem . self::LIMIT_OFFSET;
        $comando = DW3BancoDeDados::prepare($preComando);
                $comando->bindValue(1, $limit, PDO::PARAM_INT);
                $comando->bindValue(2, $offset, PDO::PARAM_INT);
                $comando->execute();
                $registros = $comando->fetchAll();
                $receitas=[];

                foreach ($registros as $registro) {
                    $receitas[] = new Receita(
                        $registro['titulo'],
                        $registro['ingredientes'],
                        $registro['passos'],
                        $registro['usuario_id'],
                        $registro['data_publicado'],
                        $registro['id']
                    );
                }
                return $receitas;
        }
    }

    public static function contarReceitas()
    {
        $registros = DW3BancoDeDados::query(self::CONTAR_RECEITAS);
        $total = $registros->fetch();
        return intval($total[0]);
    }

    public static function contarFiltro($filtro)
        {
            $comando = self::CONTAR_FILTRO . "'%" . $filtro . "%'" . self::CONTAR_FILTRO2;
            $registros = DW3BancoDeDados::query($comando);
            $total = $registros->fetch();
            return intval($total[0]);
        }

    public static function buscarUsuarioReceitas($id)
    {
            $registros = DW3BancoDeDados::query(self::BUSCAR_RECEITAS . $id);
            $receitas=[];

                    foreach ($registros as $registro) {
                        $receitas[] = new Receita(
                            $registro['titulo'],
                            $registro['ingredientes'],
                            $registro['passos'],
                            $registro['usuario_id'],
                            $registro['data_publicado'],
                            $registro['id']
                        );
                    }
                    return $receitas;
    }

    public function destruir($id)
    {
        $comando = DW3BancoDeDados::prepare(self::DELETAR);
        $comando->bindValue(1, $id, PDO::PARAM_STR);
        $comando->execute();
    }
}
