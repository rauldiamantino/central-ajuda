<div class="relative w-full min-h-full flex flex-col bg-white">
  <div class="w-full flex justify-between items-center">
    <h2 class="py-4 text-2xl font-semibold">Artigos</h2>
    <a href="/dashboard/artigo/adicionar" class="w-max flex gap-2 items-center justify-center py-2 px-4 bg-green-800 hover:bg-green-600 text-white text-sm text-xs rounded-lg">Adicionar</a>
  </div>
  <?php require_once 'tabela-artigos.php' ?>
  <?php require_once 'paginacao.php' ?>
</div>
<?php require_once 'modais/remover.php' ?>