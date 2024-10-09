<?php if (isset($artigos[0]) and is_array($artigos[0]) or isset($artigos['filtro'])) { ?>
  <div class="relative p-4 w-full min-h-full flex flex-col">
    <div class="pb-4 w-full flex flex-col sm:flex-row justify-between items-start gap-10 sm:items-center">
      <h2 class="text-2xl font-semibold flex gap-2">Artigos</h2>
      <div class="w-max flex gap-6 items-center rounded-md">
        <button type="button" class="w-full flex gap-2 items-center justify-center text-black text-sm text-sm hover:underline" onclick="filtrarArtigos()">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
            <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/>
          </svg>
          Filtrar
        </button>
        <button type="button" class="w-full flex gap-2 items-center justify-end text-black text-sm text-sm hover:underline" <?php echo count($artigos) > 1 ? 'onclick="buscarArtigos()"' : '' ?>>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
          </svg>
          Reorganizar
        </button>
        <a href="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/artigo/adicionar" class="<?php echo CLASSES_DASH_BUTTON_ADICIONAR; ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
          </svg>
          Adicionar
        </a>
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
    <a href="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/artigo/adicionar" class="<?php echo CLASSES_DASH_BUTTON_ADICIONAR; ?>">Adicionar</a>
  </div>
<?php } ?>