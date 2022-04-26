<?php
namespace Controlador;

use \Modelo\Usuario;
use \Framework\DW3Sessao;

class LoginControlador extends Controlador
{
    public function criar()
    {
        if ($this->verificarLogado()) {
            $this->visao('login/criar.php', ['usuarioid' => DW3Sessao::get('usuario'), 'mensagem' => DW3Sessao::getFlash('mensagem', null)], 'logado.php');
        } else {
            $this->visao('login/criar.php', ['mensagem' => DW3Sessao::getFlash('mensagem', null)], 'deslogado.php');
        }

    }

    public function armazenar()
    {
        $erros = [];

        if (strlen($_POST['email']) < 3) {
            $erros['email'] = 'Seu email é muito curto, no mínimo 3 caracteres são aceitos!';
        }
        if (strlen($_POST['senha']) < 3) {
            $erros['senha'] = 'Sua senha é muito curta, no mínimo 3 caracteres são aceitos!';
        }
        $this->setErros($erros);

        if ($this->getErro('email') || $this->getErro('senha')) {
            $this->visao('login/criar.php', ['mensagem' => DW3Sessao::getFlash('mensagem', null)], 'deslogado.php');
        } else {
            $usuario = Usuario::buscarEmail($_POST['email']);

            if ($usuario && $usuario->verificarSenha($_POST['senha'])) {
                DW3Sessao::set('usuario', $usuario->getId());
                $this->redirecionar(URL_RAIZ . 'receitas');
            } else {
                $erros['incorreto'] = 'Email ou senha incorreta.';
                $this->setErros($erros);
                $this->visao('login/criar.php', ['mensagem' => DW3Sessao::getFlash('mensagem', null)], 'deslogado.php');
            }
        }
    }

    public function destruir()
    {
        if ($this->verificarLogado()) {
            DW3Sessao::deletar('usuario');
            $this->redirecionar(URL_RAIZ . 'login');
        } else {
            $this->redirecionar(URL_RAIZ . 'receitas');
        }
    }
}
