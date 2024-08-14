<div class="relative w-full min-h-full flex flex-col bg-white">
  <div class="w-full flex justify-between items-center">
    <h2 class="py-4 text-2xl font-semibold">Artigos</h2>
    <div class="flex gap-2">
      <?php if (is_array($artigos) and count($artigos) > 1) { ?>
        <button type="button" class="w-max flex gap-2 items-center justify-center py-2 px-4 border border-yellow-800 hover:border-yellow-600 text-yellow-800 text-sm text-xs rounded-lg" onclick="buscarArtigos()">Reorganizar</button>
      <?php } ?>
      <a href="/dashboard/artigo/adicionar" class="w-max flex gap-2 items-center justify-center py-2 px-4 border border-green-800 bg-green-800 hover:bg-green-600 text-white text-xs rounded-lg">Adicionar</a>
    </div>
  </div>
  <?php require_once 'tabela-artigos.php' ?>
  <?php require_once 'paginacao.php' ?>
</div>
<?php require_once 'modais/remover.php' ?>
<?php require_once 'modais/organizar.php' ?>