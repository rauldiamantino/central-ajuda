<?php if (isset($categorias[0]) and is_array($categorias[0])) { ?>
  <div class="relative p-4 w-full min-h-full flex flex-col bg-white">
    <div class="pb-4 md:pb-0 w-full flex flex-col md:flex-row md:justify-between md:items-center">
      <h2 class="py-4 text-2xl font-semibold">Categorias</h2>
      <div class="w-full md:w-max flex flex-col md:flex-row gap-2">
        <button type="button" class="w-full md:w-max flex gap-2 items-center justify-center py-2 px-4 border border-yellow-800 hover:border-yellow-600 text-yellow-800 text-sm text-xs rounded-lg" onclick="buscarCategorias()">Reorganizar</button>
        <a href="/dashboard/categoria/adicionar" class="w-full md:w-max flex gap-2 items-center justify-center py-2 px-4 border border-green-800 bg-green-800 hover:bg-green-600 text-white text-xs rounded-lg">Adicionar</a>
      </div>
    </div>

    <?php require_once 'tabela-categorias.php' ?>
    <?php require_once 'paginacao.php' ?>
  </div>
  
  <?php require_once 'modais/organizar.php' ?>
  <?php require_once 'modais/remover.php' ?>
<?php } ?>

<?php if (! isset($categorias[0]) or empty($categorias[0])) { ?>
  <div class="p-4 w-full flex flex-col gap-4 items-center justify-center">
    <h2 class="text-xl">Ops! Você ainda não possui categorias</h2>
    <a href="/dashboard/categoria/adicionar" class="w-max flex gap-2 items-center justify-center py-2 px-4 border border-green-800 bg-green-800 hover:bg-green-600 text-white text-xs rounded-lg">Adicionar</a>
  </div>
<?php } ?>