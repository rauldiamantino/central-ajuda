<?php $categoriaNome = $artigos[0]['Categoria']['nome'] ?? ''; ?>
<?php $categoriaIcone = $artigos[0]['Categoria']['icone'] ?? ''; ?>

<div class="w-full flex flex-col py-14">
  <div class="pb-6 flex gap-2 font-light text-sm publico-migalhas">
    <a href="<?php echo baseUrl('/' . $subdominio); ?>" class="hover:underline">Início</a>
    <span>></span>
    <span class="underline"><?php echo $categoriaNome ?></span>
  </div>

  <div class="flex justify-start items-center gap-2 pt-6 publico-artigo-blocos publico-artigo-topo">

    <?php if ($categoriaNome and $categoriaIcone and $this->iconeExiste($categoriaIcone)) { ?>
      <div class="w-8 pers-publico-icones template-cor-<?php echo $corPrimaria; ?>">
        <?php echo file_get_contents($categoriaIcone); ?>
      </div>
    <?php } elseif ($categoriaNome) { ?>
      <span class="pers-publico-icones template-cor-<?php echo $corPrimaria; ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
          <path d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z"/>
          <path d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z"/>
        </svg>
      </span>
    <?php } ?>
    <h1 class="text-base publico-artigo-titulo"><?php echo $categoriaNome ?></h1>
  </div>

  <div class="pt-1 w-full text-lg font-extralight">
    <?php echo $artigos[0]['Categoria']['descricao'] ?? ''; ?>
  </div>

  <?php if ($artigos) { ?>
    <div class="pb-12 pt-6">
    <div class="flex flex-col gap-2 leading-8">
      <ul class="leading-9 flex flex-col gap-2">
        <?php foreach ($artigos as $chave => $linha): ?>
          <li class="flex gap-3 items-center border border-slate-100 rounded-lg bg-white shadow">
            <a href="<?php echo baseUrl('/' . $subdominio . '/artigo/' . $linha['Artigo']['id']); ?>" class="flex gap-2 justify-between items-center w-full h-full px-4 py-2 hover:bg-gray-100 rounded-md transition-colors duration-200 text-gray-800">
              <?php echo $linha['Artigo']['titulo'] ?>
              <span class="pers-publico-seta-artigo template-cor-<?php echo $corPrimaria; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"><path d="M530-481 332-679l43-43 241 241-241 241-43-43 198-198Z" fill="currentColor" /></svg>
              </span>
            </a>

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

<?php if ($this->usuarioLogado['id'] and $this->usuarioLogado['subdominio']) { ?>
  <div class="fixed bottom-32 right-4 sm:right-6 md:right-10 z-50 rounded-full hover:scale-110 duration-100 pers-publico-botao-editar template-cor-<?php echo $corPrimaria; ?>">
    <button type="button" onclick="window.open('/<?php echo $this->usuarioLogado['subdominio'] . '/dashboard/categoria/editar/' . $artigos[0]['Categoria']['id']; ?>')" class="flex items-center p-3 bg-black/15 rounded-full">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-10 h-10">
        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
      </svg>
    </button>
  </div>
<?php } ?>