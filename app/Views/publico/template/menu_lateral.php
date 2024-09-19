
<aside class="fixed z-40 md:z-10 inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transform-translate duration-100 md:static w-72 md:min-w-64 md:max-w-64 min-h-full bg-white md:bg-transparent overflow-y-auto publico-menu-lateral">
  <nav class="h-full text-sm">
    <ul class="h-full py-10 border-r border-slate-300 px-4 flex flex-col">
      <?php if (isset($categorias[0]) and is_array($categorias[0])) { ?>
        <h3 class="w-full text-start mb-2 px-6 py-4 text-lg">Categorias</h3>
        <?php foreach ($categorias as $chave => $linha) : ?>
          <li class="w-full flex hover:underline hover:bg-slate-100"><a href="/<?php echo $subdominio ?>/categoria/<?php echo $linha['Categoria.id'] ?>" class="w-full px-6 py-4"><?php echo $linha['Categoria.nome'] ?></a></li>
        <?php endforeach; ?>
      <?php } ?>

      <?php if (isset($demaisArtigos) and $demaisArtigos) { ?>
        <h3 class="w-full text-start mb-2 px-6 py-4 text-lg">Artigos relacionados</h3>
        <?php foreach ($demaisArtigos as $chave => $linha) : ?>
          <li class="w-full flex hover:underline hover:bg-slate-100"><a href="/<?php echo $subdominio ?>/artigo/<?php echo $linha['Artigo.id'] ?>" class="w-full px-6 py-4"><?php echo $linha['Artigo.titulo'] ?></a></li>
        <?php endforeach; ?>
      <?php } ?>
    </ul>
  </nav>
</aside>