<?php
namespace Controlador;

use \Modelo\Receita;
use \Modelo\Usuario;
use \Modelo\Comentario;
use \Framework\DW3Sessao;

class ReceitasControlador extends Controlador
{
    public function mostrar()
    {
        $paginacao = $this->calcularPaginacao();
        $numeroReceitas = Receita::contarReceitas();
        $numeroUsuarios = Usuario::contarUsuarios();

        if ($this->verificarLogado()) {
            $this->visao('receitas/index.php', ['receitas' => $paginacao['receitas'],
                                                'pagina' => $paginacao['pagina'],
                                                'ultimaPagina' => $paginacao['ultimaPagina'],
                                                'filtro' => $paginacao['filtro'],
                                                'ordem' => $paginacao['ordem'],
                                                'usuarioid' => DW3Sessao::get('usuario'),
                                                'numeroReceitas' => $numeroReceitas,
                                                'numeroUsuarios' => $numeroUsuarios],
                                                'logado.php');
        } else {
            $this->visao('receitas/index.php', ['receitas' => $paginacao['receitas'],
                                                 'pagina' => $paginacao['pagina'],
                                                 'ultimaPagina' => $paginacao['ultimaPagina'],
                                                 'filtro' => $paginacao['filtro'],
                                                 'ordem' => $paginacao['ordem'],
                                                 'numeroReceitas' => $numeroReceitas,
                                                 'numeroUsuarios' => $numeroUsuarios],
                                                 'deslogado.php');
        }
    }
        public function filtrar($filtro = null, $ordem = null)
        {
            if ($filtro == null && $ordem == null) {
                $paginacao = $this->calcularPaginacao($_POST['filtro'], $_POST['ordem']);
            } else {
                $paginacao = $this->calcularPaginacao($filtro, $ordem);
            }

            $numeroReceitas = Receita::contarReceitas();
            $numeroUsuarios = Usuario::contarUsuarios();

            if ($this->verificarLogado()) {
                $this->visao('receitas/index.php', ['receitas' => $paginacao['receitas'],
                                                    'pagina' => $paginacao['pagina'],
                                                    'ultimaPagina' => $paginacao['ultimaPagina'],
                                                    'filtro' => $paginacao['filtro'],
                                                    'ordem' => $paginacao['ordem'],
                                                    'usuarioid' => DW3Sessao::get('usuario'),
                                                    'numeroReceitas' => $numeroReceitas,
                                                    'numeroUsuarios' => $numeroUsuarios],
                                                    'logado.php');
            } else {
                $this->visao('receitas/index.php', ['receitas' => $paginacao['receitas'],
                                                     'pagina' => $paginacao['pagina'],
                                                     'ultimaPagina' => $paginacao['ultimaPagina'],
                                                     'filtro' => $paginacao['filtro'],
                                                     'ordem' => $paginacao['ordem'],
                                                     'numeroReceitas' => $numeroReceitas,
                                                     'numeroUsuarios' => $numeroUsuarios],
                                                     'deslogado.php');
            }
        }

    public function criar()
    {
        if ($this->verificarLogado()) {
            $this->visao('receitas/criar.php', [
                'usuario' => $this->getUsuario(),
                'mensagem' => DW3Sessao::getFlash('mensagem', null),
                'usuarioid' => DW3Sessao::get('usuario')
                ], 'logado.php');
        } else {
            DW3Sessao::setFlash('mensagem', 'Você precisa estar em uma conta para publicar uma receita.');
            $this->redirecionar(URL_RAIZ . 'login');
        }
    }

    public function armazenar()
    {
        $imagem = array_key_exists('imagem', $_FILES) ? $_FILES['imagem'] : null;
        $receita = new Receita(
            $_POST['titulo'],
            $_POST['ingredientes'],
            $_POST['passos'],
            $this->getUsuario()->getId()
        );

        $this->setErros($receita->validarFormularioReceita($_POST['titulo'], $_POST['ingredientes'], $_POST['passos'], $imagem));

        if ($this->getErro('titulo') || $this->getErro('ingredientes') || $this->getErro('passos') || $this->getErro('imagem')) {
            $this->visao('receitas/criar.php', ['mensagem' => DW3Sessao::getFlash('mensagem', null), 'usuarioid' => DW3Sessao::get('usuario')], 'logado.php');
        } else {
            $receita->salvar();
            $receita->salvarImagem($imagem);
            DW3Sessao::setFlash('mensagem', 'Receita publicada com sucesso!!');
            $this->redirecionar(URL_RAIZ . 'receitas/criar');
        }
    }

    public function mostrarReceita($id) {
        $receita = Receita::buscarId($id);
        $comentarios = Comentario::buscarComentarios($id);

        $usuario = Usuario::buscarId(DW3Sessao::get('usuario'));

        if ($receita == null) {
            $this->redirecionar(URL_RAIZ . 'receitas');
        }

        if ($this->verificarLogado()) {
            $this->visao('receitas/mostrar.php', [
                'comentarios' => $comentarios,
                'receita' => $receita,
                'mensagem' => DW3Sessao::getFlash('mensagem', null),
                'usuarioid' => DW3Sessao::get('usuario'),
                'usuario' => $usuario
                ], 'logado.php');
        } else {
            $this->visao('receitas/mostrar.php', [
                'comentarios' => $comentarios,
                'receita' => $receita,
                'mensagem' => DW3Sessao::getFlash('mensagem', null),
                'usuarioid' => DW3Sessao::get('usuario')
                ], 'deslogado.php');
        }
    }

    public function atualizar($id) {
        $receita = new Receita(
            $_POST['titulo'],
            $_POST['ingredientes'],
            $_POST['passos'],
            null,
            null,
            $id
        );

        $this->setErros($receita->validarFormularioReceita($_POST['titulo'], $_POST['ingredientes'], $_POST['passos']));

        if ($this->getErro('titulo') || $this->getErro('ingredientes') || $this->getErro('passos')) {
             $this->visao('receitas/editar.php', ['mensagem' => DW3Sessao::getFlash('mensagem', null),
                         'usuarioid' => DW3Sessao::get('usuario'), 'receita' => $receita], 'logado.php');
        } else {
            $confirmacao = Receita::buscarId($id)->getUsuarioId();
            if (DW3Sessao::get('usuario') === $confirmacao) {
                $receita->salvar();
                DW3Sessao::setFlash('mensagem', 'Receita atualizada com sucesso!');
                $this->redirecionar(URL_RAIZ . 'receitas/' . $receita->getId() . '/editar');
            } else {
                $this->redirecionar(URL_RAIZ . 'receitas');
            }
        }
    }

    public function editar($id)
    {
        $receita = Receita::buscarId($id);

        if (DW3Sessao::get('usuario') === $receita->getUsuarioId()) {
            $this->visao('receitas/editar.php', ['mensagem' => DW3Sessao::getFlash('mensagem', null),
            'usuarioid' => DW3Sessao::get('usuario'), 'receita' => $receita], 'logado.php');
        } else {
            $this->redirecionar(URL_RAIZ . 'receitas');
        }
    }

    public function destruir($id)
    {
        $receita = Receita::buscarId($id);

        if (DW3Sessao::get('usuario') === $receita->getUsuarioId()) {
            Comentario::destruirTodosComentario($id);
            $receita->destruir($id);
            $this->redirecionar(URL_RAIZ . 'receitas/' . $receita->getUsuarioId());
        } else {
            $this->redirecionar(URL_RAIZ . 'receitas');
        }
    }

    public function calcularPaginacao($filtro = null, $ordem = null) {
        $pagina = array_key_exists('p', $_GET) ? intval($_GET['p']) : 1;
        $limit = 12;
        $offset = ($pagina - 1) * $limit;
        $receitas = Receita::buscarReceitas($limit, $offset, $filtro, $ordem);
        if ($filtro != null) {
            $ultimaPagina = ceil(Receita::contarFiltro($filtro) / $limit);
        } else {
            $ultimaPagina = ceil(Receita::contarReceitas() / $limit);
        }
        return compact('filtro', 'ordem', 'pagina', 'receitas', 'ultimaPagina');
    }
}
