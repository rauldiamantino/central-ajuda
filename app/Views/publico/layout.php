<!DOCTYPE html>
<html lang="pt-br">
<?php require_once 'template/cabecalho.php' ?>
<body class="min-h-screen max-w-screen flex flex-col gap-4 font-normal bg-gray-200">
  <?php require_once 'template/topo.php' ?>
  <main class="w-full min-h-screen flex flex-col gap-4 items-center">
    <?php require_once 'template/busca.php' ?>
    <div class="w-8/12 min-h-screen flex bg-white rounded">
      <?php require_once 'template/menu_lateral.php' ?>
      
      <?php // Categoria selecionada ?>
      <?php if (isset($artigos) and $artigos) { ?>
        <?php require_once 'template/artigos.php' ?>
      <?php } ?>

      <?php // Artigo selecionado ?>
      <?php if (isset($artigo) and $artigo) { ?>
        <?php require_once 'template/artigo.php' ?>
      <?php } ?>
    </div>
  </main>
  <?php require_once 'template/rodape.php' ?>
</body>
</html>