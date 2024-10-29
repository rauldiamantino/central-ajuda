<div class="w-full flex flex-col md:px-12 py-14">
  <div class="pb-6 border-b border-slate-200 flex flex-wrap gap-2 font-light text-sm publico-migalhas">
    <a href="<?php echo baseUrl('/' . $subdominio); ?>" class="hover:underline whitespace-nowrap">Início</a>
    <?php if (isset($artigo['Categoria']['nome'])) { ?>
      <span>></span>
      <a href="<?php echo baseUrl('/' . $subdominio . '/categoria/' . $artigo['Artigo']['categoria_id']); ?>" class="hover:underline whitespace-nowrap"><?php echo $artigo['Categoria']['nome'] ?></a>
    <?php } ?>
    <span>></span>
    <span class="underline whitespace-nowrap truncate"><?php echo $artigo['Artigo']['titulo'] ?></span>
  </div>
  <div class="flex flex-col justify-between items-start gap-4 pt-10 publico-artigo-topo">
    <div class="flex gap-2 items-center">
      <h2 class="text-4xl publico-artigo-titulo"><?php echo $artigo['Artigo']['titulo'] ?></h2>

      <?php if ($artigo['Artigo']['ativo'] == INATIVO) { ?>
        <div class="text-red-800">
          <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
            <circle cx="8" cy="8" r="8"/>
          </svg>
        </div>
      <?php } ?>
    </div>
    <div class="text-xs font-light publico-artigo-datas">
      <?php if ($this->buscarAjuste('artigo_criado') == 1 and $this->buscarAjuste('artigo_autor') == 1) { ?>
        <div>Criado por <span class="font-semibold"> <?php echo $artigo['Usuario']['nome'] ?> </span> em <?php echo traduzirDataPtBr($artigo['Artigo']['criado']); ?></div>
      <?php } ?>

      <?php if ($this->buscarAjuste('artigo_criado') == 1 and $this->buscarAjuste('artigo_autor') == 0) { ?>
        <div>Criado em <?php echo traduzirDataPtBr($artigo['Artigo.criado']); ?></div>
      <?php } ?>

      <?php if ($this->buscarAjuste('artigo_modificado') == 1) { ?>
        <div>Última atualização: <?php echo traduzirDataPtBr($artigo['Artigo']['modificado']); ?></div>
      <?php } ?>
    </div>
  </div>

  <div class="flex flex-col gap-5 py-10 border-b border-slate-200 publico-artigo-blocos">
    <?php if ($conteudos) { ?>
      <?php foreach ($conteudos as $chave => $linha) : ?>
        <?php if ($linha['Conteudo']['tipo'] == 1) { ?>
          <div class="flex flex-col gap-2 leading-7 publico-artigo-bloco">
            <?php if ($linha['Conteudo']['titulo_ocultar'] == 0) { ?>
              <h2><?php echo $linha['Conteudo']['titulo'] ?></h2>
            <?php } ?>
            <div class="publico-editorjs">
              <?php echo htmlspecialchars_decode($linha['Conteudo']['conteudo']); ?>
            </div>
          </div>
        <?php } ?>

        <?php if ($linha['Conteudo']['tipo'] == 2) { ?>
          <div class="publico-artigo-bloco">
            <?php if ($linha['Conteudo']['titulo_ocultar'] == 0) { ?>
              <h2><?php echo $linha['Conteudo']['titulo'] ?></h2>
            <?php } ?>
            <img src="<?php echo $linha['Conteudo']['url'] ?>" class="w-full">
          </div>
        <?php } ?>

        <?php if ($linha['Conteudo']['tipo'] == 3) { ?>
          <div class="publico-artigo-bloco">
            <?php if ($linha['Conteudo']['titulo_ocultar'] == 0) { ?>
              <h2><?php echo $linha['Conteudo']['titulo'] ?></h2>
            <?php } ?>
            <iframe src="<?php echo str_replace('watch?v=', 'embed/', $linha['Conteudo']['url']) ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="width: 100%; height: auto; aspect-ratio: 16/9"></iframe>
          </div>
        <?php } ?>
      <?php endforeach; ?>
    <?php } ?>
  </div>
</div>