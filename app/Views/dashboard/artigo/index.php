<?php if (isset($artigos[0]) and is_array($artigos[0]) or isset($artigos['filtro'])) { ?>
  <div class="relative p-4 w-full min-h-full flex flex-col bg-white">
    <div class="pb-4 md:pb-0 w-full flex flex-col justify-center items-start md:flex-row md:justify-between md:items-center">
      <h2 class="py-4 text-2xl font-semibold">Artigos</h2>
      <div class="w-full flex justify-between md:justify-end items-center gap-2">
        <button type="button" class="w-full min-w-max md:w-max flex gap-2 items-center justify-center py-2 px-4 border border-yellow-800 hover:border-yellow-600 text-yellow-800 text-sm text-xs rounded-lg" onclick="filtrarArtigos()">Filtrar por categoria</button>
        <button type="button" class="w-full md:w-max flex gap-2 items-center justify-center py-2 px-4 border border-yellow-800 hover:border-yellow-600 text-yellow-800 text-sm text-xs rounded-lg" onclick="buscarArtigos()">Reorganizar</button>
        <a href="/dashboard/artigo/adicionar" class="w-full md:w-max flex gap-2 items-center justify-center py-2 px-4 border border-green-800 bg-green-800 hover:bg-green-600 text-white text-xs rounded-lg">Adicionar</a>
      </div>
    </div>

    <?php require_once 'tabela-artigos.php' ?>
    <?php require_once 'paginacao.php' ?>
  </div>

  <?php require_once 'modais/remover.php' ?>
  <?php require_once 'modais/filtrar-alerta.php' ?>
  <?php require_once 'modais/filtrar-categoria.php' ?>
  <?php require_once 'modais/organizar.php' ?>
<?php } ?>

<?php if (! isset($artigos['filtro']) and (! isset($artigos[0]) or empty($artigos[0]))) { ?>
  <div class="p-4 w-full flex flex-col gap-4 items-center justify-center">
    <h2 class="text-xl">Ops! Você ainda não possui artigos</h2>
    <a href="/dashboard/artigo/adicionar" class="w-max flex gap-2 items-center justify-center py-2 px-4 border border-green-800 bg-green-800 hover:bg-green-600 text-white text-xs rounded-lg">Adicionar</a>
  </div>
<?php } ?>