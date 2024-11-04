<div class="w-full h-full flex flex-col p-4">
  <h2 class="flex gap-1 text-2xl font-semibold mb-4">
    Editar
    <span class="flex items-center gap-2 text-gray-400 font-light italic text-sm">
      #<?php echo $empresa['Empresa']['id']; ?>
    </span>
  </h2>
  <div class="w-full flex flex-col md:flex-row gap-4">
    <div class="w-full flex flex-col gap-2">
      <?php require_once 'formulario.php' ?>
    </div>
    <div class="w-full">
      <?php require_once 'assinatura.php' ?>
    </div>
  </div>
</div>