<!DOCTYPE html>
<html lang="pt-br">
<?php require_once 'template/cabecalho.php' ?>
<body class="min-h-screen max-w-screen flex flex-col gap-4 font-normal">
  <?php require_once 'template/topo.php' ?>
  <main class="w-full min-h-screen flex flex-col gap-4 items-center">
    <div class="w-8/12 min-h-screen flex bg-white rounded">
     
      <?php if (! isset($resultadoBuscar)) { ?>
        <?php require_once 'template/menu_lateral.php' ?>
      <?php } ?>

      <?php require_once $visao ?>
    </div>
  </main>
  <?php require_once 'template/rodape.php' ?>
  <script src="/public/js/publico/index.js"></script>
</body>
</html>