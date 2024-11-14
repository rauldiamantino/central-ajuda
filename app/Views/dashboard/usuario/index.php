<div class="mb-10 relative w-full h-max flex flex-col">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex gap-2">Usuários</h2>
      <p class="text-gray-600">Cadastre ou edite os usuários da sua empresa de forma rápida e fácil!</p>
    </div>
    <div class="py-2 w-full h-full flex gap-2 items-start justify-end">
      <button type="button" class="<?php echo CLASSES_DASH_BUTTON_ADICIONAR; ?>" onclick="document.querySelector('.menu-adicionar-usuario').showModal()">Adicionar</button>
    </div>
  </div>

  <?php require_once 'tabela-usuarios.php' ?>
  <?php require_once 'paginacao.php' ?>
</div>
<?php require_once 'modais/adicionar.php' ?>
<?php require_once 'modais/remover.php' ?>