<div class="mb-10 relative w-full min-h-screen flex flex-col">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex gap-2">Editar <span class="flex items-center gap-2 text-gray-400 font-light italic text-sm">#<?php echo $empresa['Empresa']['id']; ?></span>
      </h2>
      <p class="text-gray-600">Gerencie seu plano, extrato e configurações para otimizar sua experiência.</p>
    </div>
    <div class="py-2 w-full h-full flex gap-2 items-start justify-end">
      <button type="button" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?>" onclick="document.querySelector('.btn-gravar-empresa').click()">Gravar</button>
    </div>
  </div>

  <?php require_once 'formulario.php' ?>
  <div class="mt-10"></div>
  <?php require_once 'plano.php' ?>
</div>