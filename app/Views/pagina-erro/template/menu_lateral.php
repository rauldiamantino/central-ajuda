<aside class="min-w-64 max-w-64 min-h-full">
  <nav class="h-full py-10 text-sm">
    <ul class="h-full border-r border-gray-300 px-4 py-2 flex flex-col">
      <?php if (isset($categorias) and $categorias) { ?>
        <h3 class="w-full text-start mb-2 px-6 py-4 text-lg">Categorias</h3>
        <?php foreach ($categorias as $chave => $linha) : ?>
          <li class="w-full flex hover:underline hover:bg-gray-100"><a href="/categoria" class="w-full px-6 py-4"><?php echo $linha['Categoria.nome'] ?></a></li>
        <?php endforeach; ?>
      <?php } ?>

      <?php if (isset($demaisArtigos) and $demaisArtigos) { ?>
        <h3 class="w-full text-start mb-2 px-6 py-4 text-lg">Artigos relacionados</h3>
        <?php foreach ($demaisArtigos as $chave => $linha) : ?>
          <li class="w-full flex hover:underline hover:bg-gray-100"><a href="<?php echo '/artigo/' . $linha['Artigo.id']; ?>" class="w-full px-6 py-4"><?php echo $linha['Artigo.titulo'] ?></a></li>
        <?php endforeach; ?>
      <?php } ?>
    </ul>
  </nav>
</aside>