<div class="border border-slate-200 w-full h-full shadow rounded-lg">
  <div class="flex min-h-full flex-col justify-center px-6 py-12 md:px-10">
    <div class="w-full">
      <img class="mx-auto h-10 w-auto" src="/public/img/luminaOn.png" alt="">
    </div>
    <div class="mt-10 w-full">
      <div class="flex flex-col gap-2">
        <?php foreach($empresas as $chave => $linha): ?>
          <a href="/login/suporte/<?php echo $linha['id']; ?>" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 flex gap-5 justify-between rounded">
            <?php echo $linha['subdominio'] ?>

            <?php if ($linha['ativo'] == ATIVO) { ?>
              <div class="flex items-center gap-2">
                <span class="text-green-800">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="8"/>
                  </svg>
                </span>
              </div>
            <?php } ?>
            <?php if ($linha['ativo'] == INATIVO) { ?>
              <div class="flex items-center gap-2">
                <span class="text-red-800">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="8"/>
                  </svg>
                </span>
              </div>
            <?php } ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="p-4 w-full flex justify-center">
    <a href="/dashboard/artigos/<?php echo $this->usuarioLogado['empresaId'] ?>" class="border border-slate-300 w-full flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-sm text-gray-700 rounded-lg">Voltar</a>
  </div>
</div>