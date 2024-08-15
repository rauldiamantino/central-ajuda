<div class="relative w-full min-h-full flex flex-col bg-white">
  <div class="w-full flex justify-between items-center">
    <h2 class="py-4 text-2xl font-semibold">Artigos</h2>
    <div class="flex items-center gap-2">
      <button type="button" class="w-max flex gap-2 items-center justify-center py-2 px-4 border border-yellow-800 hover:border-yellow-600 text-yellow-800 text-sm text-xs rounded-lg" onclick="filtrarArtigos()">Filtrar por categoria</button>
      <button type="button" class="w-max flex gap-2 items-center justify-center py-2 px-4 border border-yellow-800 hover:border-yellow-600 text-yellow-800 text-sm text-xs rounded-lg" onclick="buscarArtigos()">Reorganizar</button>
      <a href="/dashboard/artigo/adicionar" class="w-max flex gap-2 items-center justify-center py-2 px-4 border border-green-800 bg-green-800 hover:bg-green-600 text-white text-xs rounded-lg">Adicionar</a>
    </div>
  </div>
  <?php require_once 'tabela-artigos.php' ?>
  <?php require_once 'paginacao.php' ?>
</div>
<?php require_once 'modais/remover.php' ?>
<?php require_once 'modais/filtrar-alerta.php' ?>
<?php require_once 'modais/filtrar-categoria.php' ?>
<?php require_once 'modais/organizar.php' ?>