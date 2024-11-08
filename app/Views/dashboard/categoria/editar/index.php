<div class="relative w-full min-h-full flex flex-col lg:flex-row gap-10 lg:gap-4 p-4">
  <div class="w-full md:w-8/12 lg:w-1/2">
    <div class="w-full">
      <h2 class="text-2xl font-semibold mb-4">
        Editar
        <span class="text-gray-400 font-light italic">
          <a href="/<?php echo $this->usuarioLogado['subdominio']; ?>/categoria/<?php echo $categoria[0]['Categoria']['id'] ?>" target="_blank" class="text-sm text-gray-400 font-light italic hover:underline">(Categoria #<?php echo $categoria[0]['Categoria']['id']; ?>)</a>
        </span>
      </h2>

      <?php require_once 'formulario.php' ?>
    </div>

    <div class="mt-10 pb-2 w-full flex flex-col sm:flex-row justify-between items-start gap-10 sm:items-center">
      <h2 class="text-2xl font-semibold flex gap-2">Artigos</h2>
      <div class="w-full flex md:gap-6 justify-between md:justify-end items-center rounded-md">
        <button type="button" class="w-max flex gap-2 items-center justify-end text-black text-sm text-sm hover:underline" <?php echo count($categoria) > 1 ? 'onclick="buscarArtigos()"' : '' ?>>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
          </svg>
          Reorganizar
        </button>
        <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/adicionar') . '?referer=' . urlencode(baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categoria/editar/' . $categoria[0]['Categoria']['id'])); ?>" class="<?php echo CLASSES_DASH_BUTTON_ADICIONAR; ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
          </svg>
          Adicionar
        </a>
      </div>
    </div>

    <?php require_once 'tabela-artigos.php' ?>
  </div>
</div>

<?php require_once 'modais/remover.php' ?>
<?php require_once 'modais/filtrar.php' ?>
<?php require_once 'modais/filtrar-alerta.php' ?>
<?php require_once 'modais/organizar.php' ?>
