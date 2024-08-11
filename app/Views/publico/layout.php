<!DOCTYPE html>
<html lang="pt-br">
<?php require_once 'template/cabecalho.php' ?>
<body class="min-h-screen max-w-screen flex flex-col gap-4 font-normal bg-gray-200">
  <?php require_once 'template/topo.php' ?>
  <main class="w-full h-full flex flex-col gap-4 items-center">
    <?php require_once 'template/busca.php' ?>
    <div class="w-8/12 h-full flex bg-white rounded">
      <?php require_once 'template/menu_lateral.php' ?>
      <?php require_once 'template/artigo.php' ?>
      </div>
    </div>
  </main>
  <?php require_once 'template/rodape.php' ?>
</body>
</html>