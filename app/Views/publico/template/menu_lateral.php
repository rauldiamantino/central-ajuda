<?php
if (empty($menuLateral) or (int) Helper::ajuste('publico_menu_lateral') == INATIVO) {
  return;
}

$categoriaAtual = intval($artigos[0]['Artigo']['categoria_id'] ?? 0);
$artigoAtual = intval($artigo['Artigo']['id'] ?? 0);

$classesMenuLateral = 'md:max-w-72';

if ((int) Helper::ajuste('publico_largura_geral') == 2) {
  $classesMenuLateral = 'md:max-w-64';
}
elseif ((int) Helper::ajuste('publico_largura_geral') == 3) {
  $classesMenuLateral = 'md:max-w-60';
}
elseif ((int) Helper::ajuste('publico_largura_geral') == 4) {
  $classesMenuLateral = 'md:max-w-56';
}
?>

<aside class="fixed <?php echo isset($inicio) ? ' md:hidden ' : '' ?> z-40 md:z-10 inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transform-translate duration-300 md:static shadow-2xl md:shadow-none w-72 sm:w-80 md:w-full md:max-w-72 <?php echo $classesMenuLateral; ?> bg-gray-100 md:bg-transparent text-black overflow-y-auto md:rounded-l-md overflow-estilo publico-menu-lateral">
  <nav class="relative h-full text-sm px-6 md:px-0">
    <button class="absolute right-0 pt-10 px-8 h-max w-max md:hidden btn-publico-menu-lateral-fechar">
      <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="currentColor"><path d="m287-446.67 240 240L480-160 160-480l320-320 47 46.67-240 240h513v66.66H287Z"/></svg>
    </button>
    <ul class="h-full py-10 px-0 flex flex-col">

      <?php if (isset($categorias[0]) and is_array($categorias[0])) { ?>
        <h3 class="<?php echo isset($inicio) ? 'hidden md:block ' : '' ?>w-full text-start mb-2 px-4 sm:pl-0 py-4 text-gray-400 font-light text-xs">CATEGORIAS</h3>

        <?php foreach ($categorias as $chave => $linha) : ?>
          <li class="<?php echo isset($inicio) ? 'hidden md:block ' : '' ?> w-full flex <?php echo $categoriaAtual == $linha['Categoria']['id'] ? ' font-bold' : '';?> hover:underline rounded-md">
            <a href="<?php echo '/categoria/' . $linha['Categoria']['id'] . '/' . $this->gerarSlug($linha['Categoria']['nome']); ?>" class="w-full flex gap-2 items-center px-4 sm:pl-0 py-3">
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
        <h3 class="<?php echo isset($inicio) ? 'hidden md:block ' : '' ?>w-full text-start mb-2 px-4 sm:pl-0 py-4 text-gray-400 font-light text-xs">ARTIGOS RELACIONADOS</h3>
        <?php foreach ($demaisArtigos as $chave => $linha) : ?>
          <li class="<?php echo isset($inicio) ? 'hidden md:block ' : '' ?>w-full flex <?php echo $artigoAtual == $linha['Artigo']['id'] ? ' font-bold' : '';?> hover:underline rounded-md">
            <a href="<?php echo '/artigo/' . $linha['Artigo']['codigo'] . '/' . $this->gerarSlug($linha['Artigo']['titulo']); ?>" class="w-full flex items-center gap-2 px-4 sm:pl-0 py-3"><?php echo $linha['Artigo']['titulo'] ?>
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

      <h3 class="<?php echo isset($inicio) ? '' : 'border-t border-gray-400/25 ' ?>w-full text-start mt-10 mb-2 px-4 sm:pl-0 py-8 text-gray-400 font-light text-xs md:hidden">MENU</h3>
      <div class="w-full px-4 sm:pl-0 pb-10 md:hidden">
        <ul class="flex justify-start h-full gap-6">
          <?php if ($urlSite) { ?>
            <li><a href="<?php echo $urlSite ?>" target="_blank" class="hover:underline">Website</a></li>
          <?php } ?>
          <li><a href="/dashboard/login" target="_blank" class="hover:underline">Login</a></li>
        </ul>
      </div>
    </ul>
  </nav>
</aside>