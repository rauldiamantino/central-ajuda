<div class="border border-slate-300 w-full h-full shadow rounded-lg bg-white">
  <div class="flex min-h-full flex-col justify-center p-6">
    <div class="w-full justify-center flex items-center">
      <div class="w-full justify-center flex items-center">
        <img src="./img/360help-branco.svg" class="w-44">
      </div>
    </div>
    <div class="mt-10 w-full">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        <?php foreach($empresas as $chave => $linha): ?>
          <a href="/login/suporte/<?php echo $linha['id']; ?>" class="w-full flex gap-2 justify-between hover:bg-gray-50 <?php echo CLASSES_DASH_INPUT; ?>">
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
  <div class="p-6 w-full flex justify-start">
    <a href="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/artigos" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
  </div>
</div>