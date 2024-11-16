<div class="relative w-full h-max flex flex-col">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex gap-2">Editar categoria<span class="text-gray-400 font-light italic">
        <a href="/<?php echo $this->usuarioLogado['subdominio']; ?>/categoria/<?php echo $categoria[0]['Categoria']['id'] ?>" target="_blank" class="text-sm text-gray-400 font-light italic hover:underline">
          (Categoria #<?php echo $categoria[0]['Categoria']['id']; ?>)
        </a>
      </h2>
      <p class="text-gray-600">Aqui estão todos os artigos da categoria <span class="font-semibold underline"><?php echo $categoria[0]['Categoria']['nome'] ?></span></p>
    </div>
    <div class="py-2 w-full h-full flex gap-2 items-start justify-end">
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categorias'); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
      <button type="button" class="<?php echo CLASSES_DASH_BUTTON_ADICIONAR; ?>" onclick="document.querySelector('.menu-editar-categoria-novo-artigo').showModal()">
        Novo artigo
      </button>

      <?php // Menu auxiliar ?>
      <div class="relative">
        <button type="button" class="p-3 bg-gray-400/10 hover:bg-gray-400/20 rounded-lg cursor-pointer" onclick="document.querySelector('.menu-auxiliar-categoria').classList.toggle('hidden')">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
          </svg>
        </button>
        <ul class="absolute top-12 right-0 md:-right-10 border border-slate-300 lg:mx-10 flex flex-col justify-center bg-white text-gray-600 rounded-md shadow hidden menu-auxiliar menu-auxiliar-categoria">
          <li class="px-8 py-5">
            <button type="button" onclick="document.querySelector('.menu-editar-categoria').showModal()" class="flex gap-3 items-center hover:text-gray-950 botao-abrir-menu-editar-categoria">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
              </svg>
              <span class="whitespace-nowrap">Editar categoria</span>
            </button>
          </li>
          <li class="px-8 py-5">
            <button type="button" <?php echo count($categoria) > 1 ? 'onclick="buscarArtigos()"' : '' ?> class="flex gap-3 items-center hover:text-gray-950">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
              </svg>
              <span class="whitespace-nowrap">Reorganizar</span>
            </button>
          </li>
          <li class="px-8 py-5">
            <button type="button" class="flex items-center gap-3 text-red-800 js-dashboard-categorias-remover" data-categoria-id="<?php echo $categoria[0]['Categoria']['id'] ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="min-h-full">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
              </svg>
              <span class="whitespace-nowrap">Excluir categoria</span>
            </button>
          </li>
        </ul>
      </div>

    </div>
  </div>
  <?php require_once 'tabela-artigos.php' ?>
</div>
<?php require_once 'modais/adicionar-artigo.php' ?>
<?php require_once 'modais/formulario.php' ?>
<?php require_once 'modais/filtrar.php' ?>
<?php require_once 'modais/filtrar-alerta.php' ?>
<?php require_once 'modais/organizar.php' ?>
<?php require_once 'modais/remover-categoria.php' ?>
