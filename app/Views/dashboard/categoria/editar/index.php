<div class="relative w-full min-h-screen flex flex-col">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex gap-2">Editar categoria<span class="text-gray-400 font-light italic">
        <a href="/<?php echo $this->usuarioLogado['subdominio']; ?>/categoria/<?php echo $categoria[0]['Categoria']['id'] ?>" target="_blank" class="text-sm text-gray-400 font-light italic hover:underline">
          (Categoria #<?php echo $categoria[0]['Categoria']['id']; ?>)
        </a>
      </h2>
      <p class="text-gray-600">Organize seus tutoriais em categorias e facilite a busca!</p>
    </div>
    <div class="py-2 h-full flex flex-wrap sm:flex-nowrap gap-2 items-start">
      <button type="button" class="flex gap-2 items-center <?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>" <?php echo count($categoria) > 1 ? 'onclick="buscarArtigos()"' : '' ?>>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/></svg>
        Reorganizar
      </button>
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categorias'); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
      <button type="button" class="w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> botao-abrir-menu-editar-categoria" onclick="document.querySelector('.menu-editar-categoria').showModal()">Editar</button>
    </div>
  </div>
  <?php require_once 'tabela-artigos.php' ?>
</div>

<?php require_once 'modais/formulario.php' ?>
<?php require_once 'modais/remover.php' ?>
<?php require_once 'modais/filtrar.php' ?>
<?php require_once 'modais/filtrar-alerta.php' ?>
<?php require_once 'modais/organizar.php' ?>
