<div class="border border-slate-300 w-full h-full shadow rounded-lg bg-white">
  <div class="flex min-h-full flex-col justify-center px-6 py-12 md:px-10">
    <div class="w-full justify-center flex items-center">
      <div class="w-full justify-center flex items-center">
        <img src="./img/360help-branco.svg" class="w-44">
      </div>
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