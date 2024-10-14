<?php $categoriaAtual = intval($artigos[0]['Artigo']['categoria_id'] ?? 0) ?>
<?php $artigoAtual = intval($artigo['Artigo']['id'] ?? 0); ?>

<aside class="fixed z-40 md:z-10 inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transform-translate duration-300 md:static w-full md:max-w-80 min-h-full bg-gray-800 text-gray-400 md:bg-white md:text-black overflow-y-auto publico-menu-lateral md:rounded-l-md">
  <nav class="relative h-full text-sm">
    <button class="absolute right-0 pt-10 px-8 h-max w-max md:hidden btn-publico-menu-lateral-fechar">
      <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="currentColor"><path d="m287-446.67 240 240L480-160 160-480l320-320 47 46.67-240 240h513v66.66H287Z"/></svg>
    </button>
    <ul class="h-full py-10 border-r border-slate-200 px-4 flex flex-col gap-2">

      <?php if (isset($categorias[0]) and is_array($categorias[0])) { ?>
        <h3 class="w-full text-start mb-2 px-6 py-4 text-lg text-gray-600 font-light text-xs">CATEGORIAS</h3>

        <?php foreach ($categorias as $chave => $linha) : ?>
          <li class="w-full flex <?php echo $categoriaAtual == $linha['Categoria']['id'] ? ' font-bold' : '';?> hover:underline rounded-md">
            <a href="/categoria/<?php echo $linha['Categoria']['id'] ?>" class="w-full flex gap-2 items-center px-6 py-4">
              <?php echo $linha['Categoria']['nome'] ?>

              <?php if ($linha['Categoria']['ativo'] == INATIVO) { ?>
                <div class="text-red-800">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="8"/>
                  </svg>
                </div>
              <?php } ?>
            </a>
          </li>
        <?php endforeach; ?>
      <?php } ?>

      <?php if (isset($demaisArtigos) and $demaisArtigos) { ?>
        <h3 class="w-full text-start mb-2 px-6 py-4 text-lg text-gray-600 font-light text-xs">ARTIGOS RELACIONADOS</h3>
        <?php foreach ($demaisArtigos as $chave => $linha) : ?>
          <li class="w-full flex <?php echo $artigoAtual == $linha['Artigo']['id'] ? ' font-bold' : '';?> hover:underline rounded-md">
            <a href="/artigo/<?php echo $linha['Artigo']['id'] ?>" class="w-full flex items-center gap-2 px-6 py-4"><?php echo $linha['Artigo']['titulo'] ?>
              <?php if ($linha['Artigo']['ativo'] == INATIVO) { ?>
                <div class="text-red-800">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="8"/>
                  </svg>
                </div>
              <?php } ?>
            </a>
          </li>
        <?php endforeach; ?>
      <?php } ?>

      <h3 class="w-full text-start mt-10 border-t border-gray-900 mb-2 px-6 py-8 text-lg text-gray-600 font-light text-xs md:hidden">MENU</h3>
      <div class="w-full px-6 pb-10 md:hidden">
        <ul class="flex justify-start h-full gap-6">
          <li><a href="http://localhost/login" target="_blank" class="hover:underline">Dashboard</a></li>
          <li><a href="<?php echo 'https://www.google.com.br' ?>" target="_blank" class="hover:underline">Website</a></li>
        </ul>
      </div>
    </ul>
  </nav>
</aside>