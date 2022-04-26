<div class="banner">
    <img class="banner-image" alt="banner" src="<?= URL_IMG . 'banner.png'?>">
</div>
<div class="quantidade-de-receitas-e-usuarios">
    <h2>Número de Receitas: <?=$numeroReceitas?><br>Número de usuários: <?=$numeroUsuarios?></h2>
</div>
<div class="filtrar">
    <form action="<?= URL_RAIZ . 'receitas/filtrar'?>" method="post">
        <label for="filtro">Filtrar por ingrediente (apenas um ingrediente é aceito):</label>
        <input type="text" name="filtro" value="<?= ($this->getPost('filtro') != null) ? $this->getPost('filtro') : '' ?>">
        <label for="ordem">Organizar por data:</label>
        <select name="ordem" id="ordem">
           <option value="DESC">Decrescente</option>
           <option <?= ($this->getPost('ordem') != null && $this->getPost('ordem') == 'ASC') ? 'selected' : '' ?> value="ASC">Crescente</option>
        </select>
        <input class="submit" type="submit" value="Filtrar">
    </form>
</div>
<div class="receitas-home">
   <?php if (empty($receitas)) : ?>
       <h2 class="nao-publicou">O site ainda não tem nenhuma receita</h2>
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
           </div>
           <p class="data"><?= $receita->getDataPublicado() ?></p>
       </div>
   <?php endforeach ?>
</div>
<?php if ($ordem == null && $filtro == null) : ?>
    <div class="paginacao">
        <?php if ($pagina > 1) : ?>
            <a class="paginacao-button-avancar" href="<?= URL_RAIZ . 'receitas?p=' . ($pagina-1) ?>">Voltar uma página</a>
        <?php endif ?>
        <?php if ($pagina < $ultimaPagina) : ?>
            <a class="paginacao-button-voltar" href="<?= URL_RAIZ . 'receitas?p=' . ($pagina+1) ?>">Avançar uma página</a>
        <?php endif ?>
    </div>
<?php endif ?>
<?php if ($ordem != null || $filtro != null) : ?>
    <div class="paginacao">
        <?php if ($pagina > 1) : ?>
            <form class="paginacao-form" action="<?= URL_RAIZ . 'receitas/filtrar?p=' . ($pagina-1) ?>" method="post">
                <input hidden name="filtro" value="<?=$filtro?>">
                <input hidden name="ordem" value="<?=$ordem?>">
                <button class="paginacao-button-voltar" type="submit">Voltar uma página</button>
            </form>
        <?php endif ?>
        <?php if ($pagina < $ultimaPagina) : ?>
            <form class="paginacao-form" action="<?= URL_RAIZ . 'receitas/filtrar?p=' . ($pagina+1) ?>" method="post">
                <input hidden name="filtro" value="<?=$filtro?>">
                <input hidden name="ordem" value="<?=$ordem?>">
                <button class="paginacao-button-avancar" type="submit">Avançar uma página</button>
            </form>
        <?php endif ?>
    </div>
<?php endif ?>