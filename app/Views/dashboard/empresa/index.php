<div class="relative w-full min-h-full flex flex-col bg-white p-4">
  <h2 class="flex gap-1 text-2xl font-semibold mb-4">
    Editar empresa
    <span class="flex items-center gap-2 text-gray-400 font-light italic">
      #<?php echo $empresa['Empresa.id']; ?>
    </span>
  </h2>
  <?php require_once 'datas.php' ?>
  <div class="w-full flex gap-4">
    <div class="w-full flex flex-col gap-2">
      <?php require_once 'formulario.php' ?>
    </div>
    <div class="w-full"></div>
  </div>
</div>