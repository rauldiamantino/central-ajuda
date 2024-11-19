<div class="mb-10 relative w-full h-max flex flex-col">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex gap-2">Ajustes</h2>
      <p class="text-gray-600">Escolha o que mostrar e deixe sua página do seu jeito!</p>
    </div>
    <div class="py-2 w-full h-full flex gap-2 items-start justify-end">
      <button type="button" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?>" onclick="evitarDuploCliqueRedirect(event, '.form-dashboard-ajustes');">Gravar</button>
    </div>
  </div>
  <?php require_once 'formulario.php' ?>
</div>