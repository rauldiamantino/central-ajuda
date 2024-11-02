<div class="relative p-4 w-full min-h-full flex flex-col">
  <div class="pb-4 w-full flex justify-between items-start gap-10 sm:items-center">
    <h2 class="text-2xl font-semibold">Usuários</h2>
    <div class="w-max">
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuario/adicionar'); ?>" class="<?php echo CLASSES_DASH_BUTTON_ADICIONAR; ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
        </svg>
        Adicionar
      </a>
    </div>
  </div>
  <?php require_once 'tabela-usuarios.php' ?>
  <?php require_once 'paginacao.php' ?>
</div>
<?php require_once 'modais/remover.php' ?>