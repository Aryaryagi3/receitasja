<div class="publicar">
                <h1>Editar receita</h1>
                <?php if ($mensagem) : ?>
                    <div class="mensagem-alerta">
                        <p><?= $mensagem ?></p>
                    </div>
                <?php endif ?>
                <form action="<?= URL_RAIZ . 'receitas/' . $receita->getId() . '/editar'?>" method="post">
                    <input type="hidden" name="_metodo" value="PATCH">
                    <label for="nome-receita">Título da receita:</label>
                    <input type="text" id="nome-receita" name="titulo" value="<?= ($this->getPost('titulo') != null) ? $this->getPost('titulo') : $receita->getTitulo() ?>">
                    <?php if ($this->temErro('titulo')) : ?>
                        <div class="erro">
                            <p><?= $this->getErro('titulo') ?></p>
                        </div>
                    <?php endif ?><br>
                    <label for="ingredientes">Ingredientes da receita (escreva cada ingrediente em uma linha):</label>
                    <textarea class="comentar" id="ingredientes" name="ingredientes" cols="40" rows="3"><?= $this->getPost('ingredientes') ?><?= ($this->getPost('ingredientes') != null) ? $this->getPost('ingredientes') : $receita->getIngredientes() ?></textarea>
                    <?php if ($this->temErro('ingredientes')) : ?>
                        <div class="erro">
                            <p><?= $this->getErro('ingredientes') ?></p>
                        </div>
                    <?php endif ?><br>
                    <label for="passo-a-passo">Passo a passo da receita:</label>
                    <textarea class="comentar" id="passo-a-passo" name="passos" cols="40" rows="3"><?= $this->getPost('ingredientes') ?><?= ($this->getPost('passos') != null) ? $this->getPost('passos') : $receita->getPassos() ?></textarea>
                    <?php if ($this->temErro('passos')) : ?>
                        <div class="erro">
                            <p><?= $this->getErro('passos') ?></p>
                        </div>
                    <?php endif ?><br>
                    <input class="submit-receita" type="submit" value="Submit">
                </form>
                <form action="<?= URL_RAIZ . 'receitas/' . $receita->getId() . '/editar'?>" method="post">
                    <input type="hidden" name="_metodo" value="DELETE">
                    <input class="submit-receita" type="submit" value="Deletar receita">
                </form>

            </div>