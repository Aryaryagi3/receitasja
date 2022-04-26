
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= APLICACAO_NOME ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?= URL_CSS . 'style.css'?>">
        <link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <div class="logo">
                <a class="logo-content" href="<?= URL_RAIZ . 'receitas'?>">
                    <img class="image-logo-header" alt="Receitas Já logo" src="<?= URL_IMG . 'logo.png'?>">
                    <h1 class="site-nome">Receitas Já</h1>
                </a>
            </div>
            <div class="menu-direita">
                <div class="todas-as-receitas-link">
                    <a class="text-receitas-header" href="<?= URL_RAIZ . 'receitas/criar'?>">Publicar Receita</a>
                </div>
                <div class="conta-menu">
                    <div class="entrar-link">
                        <a class="entrar-text"href="<?= URL_RAIZ . 'login'?>">Entrar</a>
                    </div>
                    <div class="criar-conta-link">
                        <a class="criar-conta-text"href="<?= URL_RAIZ . 'usuarios/criar'?>">Criar Conta</a>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <?php $this->imprimirConteudo() ?>
        </main>
        <footer>
            <div class="logo">
                <a class="logo-content" href="<?URL_RAIZ . 'index'?>">
                    <img class="image-logo-header" alt="Receitas Já logo" src="<?= URL_IMG . 'logo.png'?>">
                    <h1 class="site-nome">Receitas Já</h1>
                </a>
            </div>
            <div class="contatos-footer">
                <div class="contatos">
                    <h2 class="contatos-texto">Contatos:</h2>
                </div>
                <div class="contatos-content">
                    <div class="telefone-image">
                        <img class="contatos-image" alt="telefone" src="<?= URL_IMG . 'telefone.png'?>">
                    </div>
                    <h2>99999-9999</h2>
                </div>
                <div class="contatos-content">
                    <div class="email-image">
                        <img class="contatos-image" alt="email" src="<?= URL_IMG . 'email.png'?>">
                    </div>
                    <h2>pedidosja@gmail.com</h2>
                </div>
            </div>
        </footer>
    </body>
</html>