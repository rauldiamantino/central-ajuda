<?php 
$textoAutor = '';

if(buscarAjuste('artigo_autor') == 1) {
  $textoAutor = 'por <span class="font-semibold">' . $artigo['Usuario.nome'] . '</span>';
}
?>

<div class="w-full flex flex-col px-12 py-14">
  <div class="pb-6 border-b border-slate-200 flex gap-2 font-light text-sm publico-migalhas">
    <a href="/publico" class="hover:underline">Início</a>
    <?php if (isset($artigo['Categoria.nome'])) { ?>
      <span>></span>
      <a href="/publico/categoria/<?php echo $artigo['Artigo.categoria_id'] ?>" class="hover:underline"><?php echo $artigo['Categoria.nome'] ?></a>
    <?php } ?>
    <span>></span>
    <span class="underline"><?php echo $artigo['Artigo.titulo'] ?></span>
  </div>

  <div class="flex flex-col justify-between items-start gap-4 pt-10 publico-artigo-topo">
    <h2 class="text-4xl publico-artigo-titulo"><?php echo $artigo['Artigo.titulo'] ?></h2>
    <div class="text-xs font-light publico-artigo-datas">
      <div>Criado <?php echo $textoAutor; ?> em <?php echo traduzirDataPtBr($artigo['Artigo.criado']); ?></div>
      <div>Última atualização: <?php echo traduzirDataPtBr($artigo['Artigo.modificado']); ?></div>
    </div>
  </div>

  <div class="flex flex-col gap-5 py-10 border-b border-slate-200 publico-artigo-blocos">

    <?php if ($conteudos) { ?>
      <?php foreach ($conteudos as $chave => $linha) : ?>
        <?php if ($linha['Conteudo.tipo'] == 1) { ?>
          <div class="flex flex-col gap-2 leading-7 publico-artigo-bloco">
            <?php if ($linha['Conteudo.titulo_ocultar'] == 0) { ?>
              <h2><?php echo $linha['Conteudo.titulo'] ?></h2>
            <?php } ?>
            <?php echo htmlspecialchars_decode($linha['Conteudo.conteudo']); ?>
          </div>
        <?php } ?>

        <?php if ($linha['Conteudo.tipo'] == 2) { ?>
          <div class="publico-artigo-bloco">
            <?php if ($linha['Conteudo.titulo_ocultar'] == 0) { ?>
              <h2><?php echo $linha['Conteudo.titulo'] ?></h2>
            <?php } ?>
            <img src="/<?php echo $linha['Conteudo.url'] ?>" alt="">
          </div>
        <?php } ?>

        <?php if ($linha['Conteudo.tipo'] == 3) { ?>
          <div class="publico-artigo-bloco">
            <?php if ($linha['Conteudo.titulo_ocultar'] == 0) { ?>
              <h2><?php echo $linha['Conteudo.titulo'] ?></h2>
            <?php } ?>
            <iframe src="<?php echo str_replace('watch?v=', 'embed/', $linha['Conteudo.url']) ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="width: 100%; height: auto; aspect-ratio: 16/9"></iframe>
          </div>
        <?php } ?>
      <?php endforeach; ?>
    <?php } ?>
  </div>
  <!-- <div id="player"></div>
    <div class="w-full p-10 flex flex-col gap-4 items-center justify-center publico-artigo-feedback">
      <h3>Este artigo foi útil?</h3>
      <div class="w-full flex justify-center gap-5">
        <span class="publico-artigo-feedback-sim">Sim</span>
        <span class="publico-artigo-feedback-nao">Não</span>
      </div>
    </div>
  </div> -->