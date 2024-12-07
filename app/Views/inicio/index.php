<!DOCTYPE html>
<html lang="pt-br">

<?php require_once 'template/cabecalho.php' ?>

<body class="w-full min-h-screen max-w-screen flex flex-col font-normal bg-white <?php echo isset($inicio) ? 'bg-gray-100' : 'bg-gray-100' ?>" data-base-url="<?php echo RAIZ; ?>">

  <?php require_once 'template/topo.php' ?>

  <main class="w-full h-full">
    <div class="w-full bg-white rounded-b-[90px]">
      <?php require_once $visao ?>
    </div>
  </main>

  <?php require_once 'template/rodape.php' ?>
  <?php require_once 'scripts.php' ?>
</body>
</html>