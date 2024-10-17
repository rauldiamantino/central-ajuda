<?php $categoriaNome = $artigos[0]['Categoria']['nome'] ?? ''; ?>

<div class="w-full flex flex-col px-6 md:px-12 py-14">
  <div class="pb-6 border-b border-slate-200 flex gap-2 font-light text-sm publico-migalhas">
    <a href="<?php echo baseUrl('/'); ?>" class="hover:underline">Início</a>
    <span>></span>
    <span class="underline"><?php echo $categoriaNome ?></span>
  </div>

  <div class="flex justify-start items-center gap-2 pt-10 publico-artigo-topo">

    <?php if ($categoriaNome) { ?>
      <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-tags" viewBox="0 0 16 16">
        <path d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z"/>
        <path d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z"/>
      </svg>
    <?php } ?>
    <h2 class="text-4xl publico-artigo-titulo"><?php echo $categoriaNome ?></h2>
  </div>

  <div class="pt-4 w-full italic text-lg font-extralight">
    <?php echo $artigos[0]['Categoria']['descricao'] ?? ''; ?>
  </div>

  <?php if ($artigos) { ?>
    <div class="py-12">
      <div class="flex flex-col gap-2 leading-8">
        <ul class="leading-9 flex flex-col gap-2">
          <?php foreach ($artigos as $chave => $linha): ?>
            <li class="flex gap-2 items-center">
              <a href="<?php echo baseUrl('/artigo/' . $linha['Artigo']['id']); ?>" class="border border-slate-200 w-full h-full px-4 py-2 hover:bg-slate-100 rounded-md"><?php echo $linha['Artigo']['titulo'] ?></a>

              <?php if ($linha['Artigo']['ativo'] == INATIVO) { ?>
                <div class="text-red-800">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="8"/>
                  </svg>
                </div>
              <?php } ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  <?php } ?>
</div>