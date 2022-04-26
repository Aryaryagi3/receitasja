<h1 class="sua-conta-text">Sua Conta</h1>
    <div class="sua-conta">
        <h1 class="nome-usuario"><?= $usuario->getNome() ?></h1>
        <div class="sair-conta">
            <form action="<?= URL_RAIZ . 'login' ?>" method="post">
                <input type="hidden" name="_metodo" value="DELETE">
                <?php if ($usuario->getId() === $usuarioid) : ?>
                    <a href="" class="publicar-link" onclick="event.preventDefault(); this.parentNode.submit()">
                        <img class="publicar-icon" alt="sair" src="<?= URL_IMG . 'porta.png' ?>">
                        <h2>Sair da Conta</h2>
                    </a>
                <?php endif ?>
            </form>
        </div>
        <?php if ($usuario->getId() === $usuarioid) :  ?>
            <a href="<?= URL_RAIZ . 'receitas/criar'?> " class="publicar-link">
                <img class="publicar-icon" alt="receita" src="<?= URL_IMG . 'plus.png' ?>">
                <h2>Publicar Nova Receita</h2>
            </a>
        <?php endif ?>
    </div>
    <div class="suas-receitas">
        <h1>Suas Receitas</h1>
        <div class="receitas-home">
            <?php if (empty($receitas)) : ?>
                <h2 class="nao-publicou">Nenhuma receita foi publicada</h2>
            <?php endif ?>
            <?php foreach ($receitas as $receita) : ?>
                <div class="receita">
                    <a href="<?= URL_RAIZ . 'receitas/' . $receita->getId()?>">
                        <img class="receita-image" alt="receita" src="<?= URL_IMG . $receita->getId()?>.jpeg">
                        <h3><?= $receita->getTitulo() ?></h3>
                    </a>
                    <div class="autor-receita-caixa">
                        <p class="por-receita">por</p>
                        <a class="autor-receita" href="<?= URL_RAIZ . 'usuarios/' . $receita->getUsuarioId()?>"><?= $receita->getUsuario()->getNome() ?></a>
                        <?php if ($usuario->getId() === $usuarioid) :  ?>
                            <a href="<?= URL_RAIZ . 'receitas/' . $receita->getId() . '/editar'?>" class="editar-link"><img class="editar-icon" alt="receita" src="<?= URL_IMG . 'edit.png'?>"></a>
                        <?php endif ?>
                    </div>
                    <p class="data"><?= $receita->getDataPublicado() ?></p>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>