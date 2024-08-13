<div class="relative w-full min-h-full flex flex-col bg-white p-4">
  <h2 class="text-2xl font-semibold mb-4">Editar artigo <span class="text-gray-400 font-light italic">#<?php echo $artigo['Artigo.id']; ?></span></h2>
  <?php require_once 'datas.php' ?>
  <div class="w-full flex gap-4">
    <div class="w-full flex flex-col gap-10">
      <?php require_once 'formulario.php' ?>
      <?php require_once 'conteudo/menu-adicionar.php' ?>
      <?php require_once 'conteudo/modais/adicionar.php' ?>
    </div>
    <?php require_once 'conteudo/blocos.php' ?>
  </div>
</div>
<?php require_once 'conteudo/modais/editar.php' ?>
<?php require_once 'conteudo/modais/remover.php' ?>