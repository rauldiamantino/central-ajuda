<div class="relative w-full h-max flex flex-col">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex gap-2">Categorias</h2>
      <p class="text-gray-600">Organize seus artigos em categorias e facilite a busca!</p>
    </div>
    <div class="w-full py-2 h-full flex items-start gap-2 justify-end">
      <button type="button" class="<?php echo CLASSES_DASH_BUTTON_ADICIONAR; ?>" onclick="document.querySelector('.menu-adicionar-categoria').showModal()">
        Nova categoria
      </button>

      <?php // Menu auxiliar ?>
      <div class="relative">
        <button type="button" class="p-3 bg-gray-400/10 hover:bg-gray-400/20 rounded-lg cursor-pointer" onclick="document.querySelector('.menu-auxiliar-categorias').classList.toggle('hidden')">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
          </svg>
        </button>
        <ul class="absolute top-12 right-0 md:-right-10 border border-slate-300 lg:mx-10 flex flex-col justify-center bg-white text-gray-600 rounded-md shadow hidden menu-auxiliar menu-auxiliar-categorias">
          <li class="px-4 py-3">
            <button type="button" <?php echo count($categorias) > 1 ? 'onclick="buscarCategorias()"' : '' ?> class="flex gap-3 items-center hover:text-gray-950">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
              </svg>
              Reorganizar
            </button>
          </li>
          <li class="px-4 py-3">
            <button type="button" onclick="filtrarCategorias()" class="flex gap-2 items-center hover:text-gray-950">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
                <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/>
              </svg>
              <span class="whitespace-nowrap">Filtrar</span>
            </button>
          </li>
        </ul>
      </div>

    </div>
  </div>
  <div class="flex flex-col md:flex-row gap-2 md:items-center text-blue-600 text-xs categorias-filtros-ativos">

    <?php if (count($filtroAtual) >= 1) { ?>
      <div class="md:mb-4 text-black">
        Filtrando por:
      </div>

      <div class="mb-4 flex gap-2 flex-wrap">
        <?php foreach ($filtroAtual as $chave => $linha): ?>

          <?php if ($chave == 'id') { ?>
            <div class="w-max border-dashed border border-blue-600 px-2 py-1 whitespace-nowrap cursor-pointer" onclick="filtrarCategorias()">
              ID: <span class="font-semibold"><?php echo $linha ?></span>
            </div>
          <?php } ?>

          <?php if ($chave == 'status') { ?>
            <div class="w-max border-dashed border border-blue-600 px-2 py-1 whitespace-nowrap cursor-pointer" onclick="filtrarCategorias()">
              Status: <span class="font-semibold"><?php echo $linha == ATIVO ? 'ATIVO' : 'INATIVO'; ?></span>
            </div>
          <?php } ?>

          <?php if ($chave == 'nome') { ?>
            <div class="w-max border-dashed border border-blue-600 px-2 py-1 whitespace-nowrap cursor-pointer" onclick="filtrarCategorias()">
              Título: <span class="font-semibold"><?php echo $linha; ?></span>
            </div>
          <?php } ?>
        <?php endforeach; ?>
      </div>
    <?php } ?>
  </div>

  <?php require_once 'tabela-categorias.php' ?>
  <?php require_once 'paginacao.php' ?>
</div>

<?php require_once 'modais/adicionar.php' ?>
<?php require_once 'modais/filtrar.php' ?>
<?php require_once 'modais/organizar.php' ?>