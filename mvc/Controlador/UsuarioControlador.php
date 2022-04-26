<?php
namespace Controlador;

use \Modelo\Usuario;
use \Modelo\Receita;
use \Framework\DW3Sessao;

class UsuarioControlador extends Controlador
{
    public function criar()
    {
        if ($this->verificarLogado()) {
             $this->visao('usuarios/criar.php', ['usuarioid' => DW3Sessao::get('usuario'), 'mensagem' => DW3Sessao::getFlash('mensagem', null)], 'logado.php');
        } else {
             $this->visao('usuarios/criar.php', ['mensagem' => DW3Sessao::getFlash('mensagem', null)], 'deslogado.php');
        }
    }

    public function armazenar()
    {
        $usuario = new Usuario($_POST['email'], $_POST['nome'], $_POST['senha']);
        $usuario2 = Usuario::buscarEmail($_POST['email']);

        $this->setErros($usuario->validarFormularioCriarConta($_POST['email'], $_POST['senha'], $_POST['nome']));

        if ($this->getErro('email') || $this->getErro('nome') || $this->getErro('senha')) {
            $this->visao('usuarios/criar.php', ['mensagem' => DW3Sessao::getFlash('mensagem', null)], 'deslogado.php');
        } else {
            if ($usuario2 != null) {
                $this->setErros(['incorreto' => 'Um usuário com este email já foi criado.']);
                $this->visao('usuarios/criar.php', ['mensagem' => DW3Sessao::getFlash('mensagem', null)], 'deslogado.php');
            } else {
                $usuario->salvar();
                DW3Sessao::setFlash('mensagem', 'Conta criada com sucesso!');
                $this->redirecionar(URL_RAIZ . 'usuarios/criar');
            }
        }
    }

    public function mostrar($id)
    {
        $usuario = Usuario::buscarId($id);
        $receitas = Receita::buscarUsuarioReceitas($id);

        if ($this->verificarLogado()) {
            $this->visao('usuarios/mostrar.php', [
                'usuario' => $usuario,
                'usuarioid' => DW3Sessao::get('usuario'),
                'receitas' => $receitas
            ], 'logado.php');
        } else {
            $this->visao('usuarios/mostrar.php', [
            'usuario' => $usuario,
            'usuarioid' => DW3Sessao::get('usuario'),
            'receitas' => $receitas
            ], 'deslogado.php');
        }

    }
}
