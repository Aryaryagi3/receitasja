<?php
namespace Controlador;

use \Modelo\Comentario;
use \Modelo\Usuario;
use \Framework\DW3Sessao;

class ComentariosControlador extends Controlador
{
    public function armazenar($id)
    {
        $comentario = new Comentario($_POST['mensagem'], $id, DW3Sessao::get('usuario'));
        $receita = Comentario::buscarReceita($comentario->getReceitaId());
        $usuario = Usuario::buscarId(DW3Sessao::get('usuario'));
        $comentarios = Comentario::buscarComentarios($comentario->getReceitaId());

        $this->setErros($comentario->validarFormularioComentario($_POST['mensagem']));

        if ($this->getErro('mensagem')) {
            $this->visao('receitas/mostrar.php', ['comentarios' => $comentarios, 'receita' => $receita, 'usuario' => $usuario, 'usuarioid' => DW3Sessao::get('usuario'), 'mensagem' => DW3Sessao::getFlash('mensagem', null)], 'logado.php');
        } else {
            $comentario->salvar();
            DW3Sessao::setFlash('mensagem', 'Comentario publicado com sucesso!');
            $this->redirecionar(URL_RAIZ . 'receitas/' . $comentario->getReceitaId());
        }
    }

    public function destruir()
    {
        $comentario = Comentario::buscarId($_POST['idcomentario']);
        $receitaId = $comentario->getReceitaId();

        if ($this->verificarLogado()) {
            $comentario->destruirComentario($comentario->getId());
            $this->redirecionar(URL_RAIZ . 'receitas/' . $receitaId);
        } else {
            $this->redirecionar(URL_RAIZ . 'receitas');
        }
    }
}
