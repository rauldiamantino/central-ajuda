<div class="relative w-full min-h-full flex flex-col bg-white p-4">
  <h2 class="text-2xl font-semibold mb-4">
    Editar
    <span class="text-gray-400 font-light italic">
      <a href="<?php echo subdominioDominio($this->usuarioLogado['subdominio']); ?>/categoria/<?php echo $categoria['Categoria.id'] ?>" target="_blank" class="text-sm text-gray-400 font-light italic hover:underline">(Categoria #<?php echo $categoria['Categoria.id']; ?>)</a>
    </span>
  </h2>
  <?php require_once 'datas.php' ?>
  <div class="w-full flex gap-4">
    <div class="w-full flex flex-col gap-10">
      <?php require_once 'formulario.php' ?>
    </div>
    <div class="hidden md:block w-full"></div>
  </div>
</div>