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
      <?php if ($empresa['Empresa.subdominio']) { ?>
        <div class="p-4 w-full border-b border-slate-200 text-gray-900 text-center text-sm">Divulgue o endereço <span class="text-xl text-red-700"><?php echo $empresa['Empresa.subdominio'] ?? ''?>.luminaon.com.br</span></div>
      <?php } ?>
    </div>
    <div class="w-full"></div>
  </div>
</div>
