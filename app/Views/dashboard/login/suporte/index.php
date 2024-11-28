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
  <div class="p-6 w-full flex items-center justify-end">
    <button type="button" onclick="window.location.href='<?php echo baseUrl('/logout'); ?>'" class="w-max flex gap-3 items-center text-red-800 hover:text-red-950">
      <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" fill="currentColor"><path d="M186.67-120q-27 0-46.84-19.83Q120-159.67 120-186.67v-586.66q0-27 19.83-46.84Q159.67-840 186.67-840h292.66v66.67H186.67v586.66h292.66V-120H186.67Zm470.66-176.67-47-48 102-102H360v-66.66h351l-102-102 47-48 184 184-182.67 182.66Z"/></svg>
      <span class="whitespace-nowrap">Sair</span>
    </button type="button">
  </div>
</div>