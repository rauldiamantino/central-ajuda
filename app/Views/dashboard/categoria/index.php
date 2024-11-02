<div class="relative p-4 w-full min-h-full flex flex-col">
  <div class="pb-4 w-full flex flex-col sm:flex-row justify-between items-start gap-10 sm:items-center">
    <h2 class="text-2xl font-semibold">Categorias</h2>
    <div class="w-max flex gap-6 items-center rounded-md">
      <button type="button" class="w-full flex gap-2 items-center justify-end text-black text-sm text-sm hover:underline" <?php echo count($categorias) > 1 ? 'onclick="buscarCategorias()"' : '' ?>>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
        </svg>
        Reorganizar
      </button>
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categoria/adicionar'); ?>" class="<?php echo CLASSES_DASH_BUTTON_ADICIONAR; ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
        </svg>
        Adicionar
      </a>
    </div>
  </div>

  <?php require_once 'tabela-categorias.php' ?>
  <?php require_once 'paginacao.php' ?>
</div>

<?php require_once 'modais/organizar.php' ?>
<?php require_once 'modais/remover.php' ?>