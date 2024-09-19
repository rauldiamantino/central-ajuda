<div class="w-full h-full flex flex-col bg-white p-4">
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
      <?php if ($empresa['Empresa.subdominio'] and isset($_SERVER['SERVER_NAME']) and $_SERVER['SERVER_NAME']) { ?>
        <div class="p-4 flex flex-col flex-wrap md:flex-row md:gap-2 justify-center items-center w-full border-b border-slate-200 text-gray-900 text-center text-sm">
          Divulgue o endereço:
          <a href="/<?php echo $empresa['Empresa.subdominio'] ?>" target="_blank" class="text-xl text-red-700"><?php echo  $_SERVER['SERVER_NAME'] . '/' . $empresa['Empresa.subdominio']?></a>
        </div>
      <?php } ?>
    </div>
    <div class="hidden md:block w-full"></div>
  </div>
</div>