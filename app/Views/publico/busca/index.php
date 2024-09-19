<div class="w-full flex flex-col px-6 md:px-12 py-14">
  <div class="border-b border-slate-200 flex flex-col justify-between items-start gap-4 py-5 mb-10 publico-artigo-topo">
    <?php if ($textoBusca) { ?>
      <h2 class="text-4xl publico-artigo-titulo"> Exibindo resultados para "<?php echo $textoBusca ?>"</h2>
    <?php } ?>
    <?php if (empty($textoBusca)) { ?>
      <h2 class="text-4xl publico-artigo-titulo"> Exibindo todos os artigos</h2>
    <?php } ?>
  </div>

  <?php if ($resultadoBuscar) { ?>
    <div class="flex flex-col gap-5 border-b border-slate-200">
      <?php foreach ($resultadoBuscar as $chave => $linha) : ?>
          <div class="mb-10 flex flex-col gap-2 publico-artigo-bloco">
            <?php if (isset($linha['Artigo.ativo']) and $linha['Artigo.ativo'] == 1) { ?>
                <div class="pb-2 flex gap-2 font-light text-sm publico-migalhas">
                <?php if (empty($linha['Categoria.nome']) or empty($linha['Artigo.categoria_id'])) { ?>
                  <a href="" class="italic hover:underline">Sem categoria</a>
                <?php } ?>
                <?php if ($linha['Categoria.nome'] and $linha['Artigo.categoria_id']) { ?>
                  <a href="/<?php echo $subdominio ?>/categoria/<?php echo $linha['Artigo.categoria_id']; ?>" class="italic hover:underline"><?php echo $linha['Categoria.nome'] ?></a>
                <?php } ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-tags" viewBox="0 0 16 16">
                  <path d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z"/>
                  <path d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z"/>
                </svg>
                </div>
              <h2 class="text-2xl"><a href="/<?php echo $subdominio ?>/artigo/<?php echo $linha['Artigo.id'] ?>" class="hover:underline"><?php echo $linha['Artigo.titulo'] ?></a></h2>
            <?php } ?>
          </div>
      <?php endforeach; ?>

      <?php require_once 'paginacao.php' ?>
    </div>
  <?php } ?>