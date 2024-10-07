<div class="w-full h-full bg-white p-5 lg:p-10 rounded-md dashboard-pre-visualizacao">
  <div class="w-full flex justify-center items-center gap-4">
    <span class="font-light text-gray-400">Pré-visualização do artigo</span>
  </div>
  <div class="flex flex-col justify-between items-start gap-4 pt-10">
    <div class="flex gap-2 items-center">
      <h2 class="text-4xl"><?php echo $artigo['Artigo.titulo'] ?></h2>
      <?php if ($artigo['Artigo.ativo'] == INATIVO) { ?>
        <div class="text-red-800">
          <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
            <circle cx="8" cy="8" r="8"/>
          </svg>
        </div>
      <?php } ?>
    </div>
    <div class="text-xs font-light">
      <div>Criado por <span class="font-semibold"><?php echo $artigo['Usuario.nome']; ?></span> em <?php echo traduzirDataPtBr($artigo['Artigo.criado']); ?></div>
      <div>Última atualização: <?php echo traduzirDataPtBr($artigo['Artigo.modificado']); ?></div>
    </div>

    <div class="w-full flex flex-col gap-5 pt-6 pb-10 border-b border-slate-200">
      <?php if ($conteudos) { ?>
        <?php foreach ($conteudos as $chave => $linha) : ?>
          <?php if ($linha['Conteudo.tipo'] == 1) { ?>
            <div class="flex flex-col gap-2 leading-7">
              <?php if ($linha['Conteudo.titulo_ocultar'] == 0) { ?>
                <h2><?php echo $linha['Conteudo.titulo'] ?></h2>
              <?php } ?>
              <div>
                <?php echo htmlspecialchars_decode($linha['Conteudo.conteudo']); ?>
              </div>
            </div>
          <?php } ?>

          <?php if ($linha['Conteudo.tipo'] == 2) { ?>
            <div>
              <?php if ($linha['Conteudo.titulo_ocultar'] == 0) { ?>
                <h2><?php echo $linha['Conteudo.titulo'] ?></h2>
              <?php } ?>
              <img src="<?php echo $linha['Conteudo.url'] ?>" class="w-full">
            </div>
          <?php } ?>

          <?php if ($linha['Conteudo.tipo'] == 3) { ?>
            <div>
              <?php if ($linha['Conteudo.titulo_ocultar'] == 0) { ?>
                <h2><?php echo $linha['Conteudo.titulo'] ?></h2>
              <?php } ?>
              <iframe src="<?php echo str_replace('watch?v=', 'embed/', $linha['Conteudo.url']) ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="width: 100%; height: auto; aspect-ratio: 16/9"></iframe>
            </div>
          <?php } ?>
        <?php endforeach; ?>
      <?php } ?>
    </div>
  </div>
</div>