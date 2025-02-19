<main class="w-full min-h-screen flex flex-col gap-4 items-center">
  <div class="relative min-w-full h-full">
    <?php require_once 'efeito-arredondamento.php' ?>

    <div class="px-4 <?php echo isset($inicio) ? 'pt-16' : 'pt-4'; ?> w-full h-full bg-white text-black">
      <div class="mx-auto w-full <?php echo $classesLarguraGeral ?> min-h-screen flex gap-4">
        <?php require_once 'menu_lateral.php' ?>
        <?php require_once $visao ?>
    </div>
    </div>
  </div>
</main>