<div class="publicar">
                <h1>Sua Receita</h1>
                <?php if ($mensagem) : ?>
                    <div class="mensagem-alerta">
                        <p><?= $mensagem ?></p>
                    </div>
                <?php endif ?>
                <form action="<?= URL_RAIZ . 'receitas' ?>" method="post" enctype="multipart/form-data">
                    <label for="nome-receita">Título da receita:</label>
                    <input type="text" id="nome-receita" name="titulo" value="<?= $this->getPost('titulo') ?>">
                    <?php if ($this->temErro('titulo')) : ?>
                        <div class="erro">
                            <p><?= $this->getErro('titulo') ?></p>
                        </div>
                    <?php endif ?><br>
                    <label for="img">Envie uma imagem do prato (apenas jpeg/jpg):</label>
                    <input type="file" id="imagem" name="imagem" value="<?= $this->getPost('imagem') ?>">
                    <?php if ($this->temErro('imagem')) : ?>
                        <div class="erro">
                            <p><?= $this->getErro('imagem') ?></p>
                        </div>
                    <?php endif ?><br>
                    <label for="ingredientes">Ingredientes da receita:</label>
                    <textarea class="comentar" id="ingredientes" name="ingredientes" cols="40" rows="3"><?= $this->getPost('ingredientes') ?></textarea>
                    <?php if ($this->temErro('ingredientes')) : ?>
                        <div class="erro">
                            <p><?= $this->getErro('ingredientes') ?></p>
                        </div>
                    <?php endif ?><br>
                    <label for="passo-a-passo">Passo a passo da receita (escreva cada ingrediente em uma linha):</label>
                    <textarea class="comentar" id="passo-a-passo" name="passos" cols="40" rows="3""><?= $this->getPost('passos') ?></textarea>
                    <?php if ($this->temErro('passos')) : ?>
                        <div class="erro">
                            <p><?= $this->getErro('passos') ?></p>
                        </div>
                    <?php endif ?><br>
                    <input class="submit-receita" type="submit" value="Submit">
                </form>
            </div>