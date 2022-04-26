<div class="foto-titulo-receita">
                <div>
                    <h1 class="titulo-receita"><?= $receita->getTitulo() ?></h1>
                    <div class="nome-usuario-box">
                        <a class="nome-usuario-box-2"href="<?= URL_RAIZ . 'usuarios/' . $receita->getUsuario()->getId()?>">
                            <p class="nome-usuario"><?= $receita->getUsuario()->getNome() ?></p>
                        </a>
                    </div>
                    <div class="nome-usuario-box">
                        <p class="nome-usuario"><?= $receita->getDataPublicado() ?></p>
                    </div>
                </div>
                <img class="imagem-receita" alt="Imagem da receita" src="<?= URL_IMG . $receita->getId() . '.jpeg'?>">
            </div>
            <div class="ingredientes">
                <h1 class="titulo-ingredientes">Ingredientes:</h1>
                <p class="ingredientes-list"><?= $receita->getIngredientes() ?></p>
            </div>
            <div class="passos">
                <h1 class="titulo-passo-a-passo">Passo a passo:</h1>
                <p><?= $receita->getPassos() ?></p>
            </div>
            <div class="comentarios">
                <h1 class="comentarios-text">Comentários</h1>
                <?php if ($usuarioid == null) : ?>
                    <div class="comentario">
                        <a class="nome-usuario-box-2"href="<?= URL_RAIZ . 'login'?>">
                            <p class="nome-usuario">Você precisa estar em uma conta para publicar um comentário.</p>
                        </a>
                    </div>
                <?php endif ?>
                <?php if ($usuarioid != null) : ?>
                    <div class="comentario">
                        <div class="usuario-e-data">
                            <div class="nome-usuario-box">
                                <a class="nome-usuario-box-2"href="<?= URL_RAIZ . 'usuarios/' . $usuarioid?>">
                                    <p class="nome-usuario"><?= $usuario->getNome() ?></p>
                                </a>
                            </div>
                        </div>
                        <form action="<?= URL_RAIZ . 'receitas/' . $receita->getId() ?>" method="post">
                            <textarea class="comentar" placeholder="Comente algo sobre a receita!" name="mensagem" cols="40" rows="3"></textarea>
                            <input class="submit-comentario" type="submit"value="Submit">
                        </form>
                        <?php if ($mensagem) : ?>
                            <div class="mensagem-alerta">
                                <p><?= $mensagem ?></p>
                            </div>
                        <?php endif ?>
                        <?php if ($this->temErro('mensagem')) : ?>
                            <div class="erro">
                                <p><?= $this->getErro('mensagem') ?></p>
                            </div>
                        <?php endif ?><br>
                    </div>
                <?php endif ?>
                <?php if (empty($comentarios)) : ?>
                    <h2 class="nao-publicou">Esta receita ainda não apresenta comentários.</h2>
                <?php endif ?>
                <?php foreach ($comentarios as $comentario) : ?>
                    <div class="comentario">
                        <div class="usuario-e-data">
                            <div class="nome-usuario-box">
                                <a class="nome-usuario-box-2"href="<?= URL_RAIZ . 'usuarios/' . $comentario->buscarUsuario($comentario->getUsuarioId())->getId() ?>">
                                    <p class="nome-usuario"><?= $comentario->buscarUsuario($comentario->getUsuarioId())->getNome() ?></p>
                                </a>
                            </div>
                            <p class="data-comentario"><?= $comentario->getDataPublicado() ?></p>
                        </div>
                        <div class="mensagem-e-lixo">
                            <?php if ($usuarioid == $comentario->buscarUsuario($comentario->getUsuarioId())->getId()) : ?>
                            <p class="comentario-usuario"><?= $comentario->getMensagem() ?></p>
                            <form class="nome-usuario-box-2" method="post" action="<?= URL_RAIZ . 'receitas/' . $comentario->buscarUsuario($comentario->getUsuarioId())->getId()?>">
                                <input type="hidden" name="_metodo" value="DELETE">
                                    <input type="hidden" name="idcomentario" value="<?= $comentario->getId() ?>>">
                                    <a href="" onclick="event.preventDefault(); this.parentNode.submit()"><img class="editar-icon" alt="receita" src="<?= URL_IMG . 'lixo.png'?>"></a>
                                </form>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>

            </div>