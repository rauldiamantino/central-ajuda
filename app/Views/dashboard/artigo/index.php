<?php if (isset($artigos[0]) and is_array($artigos[0]) or isset($artigos['filtro'])) { ?>
  <div class="relative p-4 w-full min-h-full flex flex-col bg-white">
    <div class="pb-4 md:pb-0 w-full flex flex-col justify-center items-start md:flex-row md:justify-between md:items-center">
      <h2 class="py-4 text-2xl font-semibold flex gap-2">Artigos</h2>
      <div class="pt-4 md:pt-0 w-full flex justify-end">
        <div class="py-3 w-full md:w-max flex justify-center md:justify-end items-center gap-6 rounded-md">
          <a href="/<?php echo $this->usuarioLogadoSubdominio ?>/dashboard/artigo/adicionar" class="w-full flex gap-2 items-center justify-center text-black text-sm text-sm hover:underline">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
            </svg>
            Adicionar
          </a>
          <button type="button" class="w-full flex gap-2 items-center justify-center text-black text-sm text-sm hover:underline" onclick="filtrarArtigos()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z"/>
            </svg>
            Filtrar
          </button>
          <button type="button" class="w-full flex gap-2 items-center justify-end text-black text-sm text-sm hover:underline" <?php echo count($artigos) > 1 ? 'onclick="buscarArtigos()"' : '' ?>>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
            </svg>
            Reorganizar
          </button>
        </div>
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
    <a href="/<?php echo $this->usuarioLogadoSubdominio ?>/dashboard/artigo/adicionar" class="w-max flex gap-2 items-center justify-center py-2 px-4 border border-green-800 bg-green-800 hover:bg-green-600 text-white text-xs rounded-lg">Adicionar</a>
  </div>
<?php } ?>