<div class="w-full justify-center flex items-center">
  <div class="w-full justify-center flex items-center">
    <img src="<?php echo baseUrl('/img/360help-branco.svg')?>" class="w-28">
  </div>
</div>

<div class="mt-5 w-full h-full shadow-2xl rounded-md bg-white">
  <div class="flex min-h-full flex-col justify-center p-6">
    <div class="mt-10 w-full">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        <?php foreach($empresas as $chave => $linha): ?>
          <a href="<?php echo baseUrl('/login/suporte/' . $linha['Empresa']['id']); ?>" class="w-full flex gap-2 justify-between hover:bg-gray-50 <?php echo CLASSES_DASH_INPUT; ?>">
            <?php echo $linha['Empresa']['subdominio'] ?>

            <?php if ($linha['Empresa']['ativo'] == ATIVO) { ?>
              <span class="px-3 py-1 bg-green-50 text-green-800 text-xs rounded-full">Ativo</span>
            <?php } ?>
            <?php if ($linha['Empresa']['ativo'] == INATIVO) { ?>
              <span class="px-3 py-1 bg-red-50 text-red-800 text-xs rounded-full">Inativo</span>
            <?php } ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="p-6 w-full">
    <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard'); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
  </div>
</div>