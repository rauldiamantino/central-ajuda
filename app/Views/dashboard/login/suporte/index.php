<div class="w-full h-full shadow rounded-lg">
  <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img class="mx-auto h-10 w-auto" src="/public/img/luminaOn.png" alt="">
    </div>
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <div class="flex flex-col gap-2">
        <?php foreach($empresas as $chave => $linha): ?>
          <a href="/login/suporte/<?php echo $linha['id'] ?>" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 rounded">
            <?php echo $linha['subdominio'] ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<div class="mt-10 w-full flex justify-center">
  <a href="/logout" class="w-max flex items-center justify-center p-2">
    <div class="flex items-center gap-3 text-red-800">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
        <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
      </svg>
      <span>Sair</span>
    </div>
  </a>
</div>