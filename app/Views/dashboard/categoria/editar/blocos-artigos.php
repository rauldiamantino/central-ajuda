<div class="w-full flex flex-col gap-4">
  <h2 class="text-2xl font-semibold">
    Artigos
  </h2>
  <div class="mb-4 border border-slate-300 p-4 w-full select-none flex flex-col gap-2 bg-white rounded-md shadow">
    <?php if (empty($categoria[0]['Artigo']['id'])) { ?>
      <span class="text-xs w-full flex justify-center">Ops! Nenhum artigo adicionado</span>
    <?php } else { ?>
      <?php foreach ($categoria as $linha): ?>
        <div class="flex gap-1 w-full">
          <div class="border border-slate-200 p-4 w-full flex items-center justify-between gap-4 hover:bg-slate-50 rounded-lg">
            <div class="w-full group">
              <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $linha['Artigo']['id']); ?>" target="_blank" class="text-start group-hover:underline">
                <?php echo $linha['Artigo']['titulo'] ? $linha['Artigo']['titulo'] : '** Sem título **' ?>
              </a>
            </div>
          </div>
          <button type="button" class="w-max h-full text-red-800 flex items-center hover:text-red-600 text-xs rounded-lg js-dashboard-artigos-remover" data-empresa-id="<?php echo $this->usuarioLogado['empresaId'] ?>" data-artigo-id="<?php echo $linha['Artigo']['id'] ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="min-h-full">
              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
              <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
            </svg>
          </button>
        </div>
      <?php endforeach; ?>
    <?php } ?>
  </div>
</div>