<div class="relative w-full min-h-screen flex flex-col">
  <div class="pb-4 w-full flex flex-col sm:flex-row justify-between items-start gap-10 sm:items-center">
    <div class="mb-5 w-full flex flex-col">
      <h2 class="text-3xl font-semibold flex gap-2">Artigos</h2>
      <p class="text-gray-600">Que tal cadastrar seus tutoriais de ajuda com textos, vídeos e imagens?</p>
    </div>
    <div class="w-full flex gap-6 justify-start md:justify-end items-center rounded-md">
      <button type="button" class="w-max flex gap-2 items-center justify-center text-black text-sm text-sm hover:underline" onclick="filtrarArtigos()">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
          <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/>
        </svg>
        Filtrar
      </button>
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/adicionar'); ?>" class="<?php echo CLASSES_DASH_BUTTON_ADICIONAR; ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
        </svg>
        Adicionar
      </a>
    </div>
  </div>

  <div class="flex flex-col md:flex-row gap-2 md:items-center text-blue-600 text-xs artigos-filtros-ativos">

    <?php if (count($filtroAtual) >= 1) { ?>
      <div class="md:mb-4 text-black">
        Filtrando por:
      </div>

      <div class="mb-4 flex gap-2 flex-wrap">
        <?php foreach ($filtroAtual as $chave => $linha): ?>

          <?php if ($chave == 'id') { ?>
            <div class="w-max border-dashed border border-blue-600 px-2 py-1 whitespace-nowrap cursor-pointer" onclick="filtrarArtigos()">
              ID: <span class="font-semibold"><?php echo $linha ?></span>
            </div>
          <?php } ?>

          <?php if ($chave == 'categoria_id') { ?>
            <div class="w-max border-dashed border border-blue-600 px-2 py-1 whitespace-nowrap cursor-pointer" onclick="filtrarArtigos()">
              <?php $categoriaNome = $filtroAtual['categoria_nome'] ?? ''; ?>
              Categoria: <span class="font-semibold"><?php echo $categoriaNome ? $categoriaNome : '*** Sem Nome ***'; ?></span>
            </div>
          <?php } ?>

          <?php if ($chave == 'status') { ?>
            <div class="w-max border-dashed border border-blue-600 px-2 py-1 whitespace-nowrap cursor-pointer" onclick="filtrarArtigos()">
              Status: <span class="font-semibold"><?php echo $linha == ATIVO ? 'ATIVO' : 'INATIVO'; ?></span>
            </div>
          <?php } ?>

          <?php if ($chave == 'titulo') { ?>
            <div class="w-max border-dashed border border-blue-600 px-2 py-1 whitespace-nowrap cursor-pointer" onclick="filtrarArtigos()">
              Título: <span class="font-semibold"><?php echo $linha; ?></span>
            </div>
          <?php } ?>
        <?php endforeach; ?>
      </div>
    <?php } ?>
  </div>

  <?php require_once 'tabela-artigos.php' ?>
  <?php require_once 'paginacao.php' ?>
</div>

<?php require_once 'modais/remover.php' ?>
<?php require_once 'modais/filtrar.php' ?>
<?php require_once 'modais/filtrar-alerta.php' ?>
<?php require_once 'modais/organizar.php' ?>