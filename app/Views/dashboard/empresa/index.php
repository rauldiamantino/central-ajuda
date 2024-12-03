<div class="mb-10 relative w-full h-max flex flex-col container-empresa">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex gap-2">Editar <span class="flex items-center gap-2 text-gray-400 font-light italic text-sm">#<?php echo $empresa['Empresa']['id']; ?></span>
      </h2>
      <p class="text-gray-600">Gerencie seu plano, extrato e configurações para otimizar sua experiência.</p>
    </div>
    <div class="py-2 w-full h-full flex gap-2 items-start justify-end">
      <button type="submit" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> btn-gravar-empresa" form="form-editar-empresa" onclick="evitarDuploCliqueRedirect(event, '.form-editar-empresa');">Gravar</button>
    </div>
  </div>

  <?php require_once 'formulario.php' ?>
</div>