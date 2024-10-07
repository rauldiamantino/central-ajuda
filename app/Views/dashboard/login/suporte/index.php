<div class="border border-slate-300 w-full h-full shadow rounded-lg bg-white">
  <div class="flex min-h-full flex-col justify-center px-6 py-12 md:px-10">
    <div class="w-full justify-center flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-highlighter" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.096.644a2 2 0 0 1 2.791.036l1.433 1.433a2 2 0 0 1 .035 2.791l-.413.435-8.07 8.995a.5.5 0 0 1-.372.166h-3a.5.5 0 0 1-.234-.058l-.412.412A.5.5 0 0 1 2.5 15h-2a.5.5 0 0 1-.354-.854l1.412-1.412A.5.5 0 0 1 1.5 12.5v-3a.5.5 0 0 1 .166-.372l8.995-8.07zm-.115 1.47L2.727 9.52l3.753 3.753 7.406-8.254zm3.585 2.17.064-.068a1 1 0 0 0-.017-1.396L13.18 1.387a1 1 0 0 0-1.396-.018l-.068.065zM5.293 13.5 2.5 10.707v1.586L3.707 13.5z"/>
      </svg>
      <h2 class="text-4xl text-black font-bold">360help</h2>
    </div>
    <div class="mt-10 w-full">
      <div class="flex flex-col gap-2">
        <?php foreach($empresas as $chave => $linha): ?>
          <a href="/login/suporte/<?php echo $linha['id']; ?>" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 flex gap-5 justify-between rounded">
            <?php echo $linha['subdominio'] ?>

            <?php if ($linha['ativo'] == ATIVO) { ?>
              <span class="px-3 py-1 bg-green-50 text-green-800 text-xs rounded-full">Ativo</span>
            <?php } ?>
            <?php if ($linha['ativo'] == INATIVO) { ?>
              <span class="px-3 py-1 bg-red-50 text-red-800 text-xs rounded-full">Inativo</span>
            <?php } ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="p-4 w-full flex justify-center">
    <a href="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/artigos" class="border border-slate-300 w-full flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-sm text-gray-700 rounded-lg">Voltar</a>
  </div>
</div>