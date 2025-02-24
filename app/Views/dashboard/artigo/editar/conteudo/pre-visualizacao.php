<?php
// Preload perfil
if (isset($artigo['Usuario']['foto']) and $artigo['Usuario']['foto']) {
  echo '<link rel="preload" href="' . $this->renderImagem($artigo['Usuario']['foto']) . '" as="image">';
}

// Preload conteúdo
if ($conteudos) {
  foreach ($conteudos as $linha):

    if (isset($linha['Conteudo']['url']) and $linha['Conteudo']['url']) {
      echo '<link rel="preload" href="' . $this->renderImagem($linha['Conteudo']['url']) . '" as="image">';
    }
  endforeach;
}

// Modo leitura
if (isset($artigo['Artigo']['editar'])) {
  $acaoBotaoEditar = 'disabled';
  $acaoCliqueBotaoEditar = '';
  $classeDivPaiBtnEditar = '';
  $classeConteudoTitulo = 'select-text';
  $classeConteudoConteudo = 'select-text';

  if ($artigo['Artigo']['editar'] == ATIVO) {
    $acaoBotaoEditar = '';
    $acaoCliqueBotaoEditar = 'onclick="abrirModalEditar(event)"';
    $classeDivPaiBtnEditar = 'hover:bg-gray-600/10';
    $classeConteudoTitulo = 'pointer-events-none';
    $classeConteudoConteudo = 'pointer-events-none group-hover:block';
  }

  if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
    $acaoBotaoEditar = 'disabled';
    $acaoCliqueBotaoEditar = '';
    $classeDivPaiBtnEditar = '';
    $classeConteudoTitulo = 'select-text';
    $classeConteudoConteudo = 'select-text';
  }
}
?>

<div
  class="border border-slate-300 w-full h-full bg-white shadow py-5 lg:p-10 rounded-md pers-publico-artigo template-cor-<?php echo Helper::ajuste('publico_cor_primaria'); ?> dashboard-pre-visualizacao"
  data-usuario-nivel="<?php echo $this->usuarioLogado['nivel']; ?>"
  data-artigo="<?php echo $artigo['Artigo']['id'] ?? ''; ?>"
>
  <div class="flex flex-col justify-between items-start gap-2 px-3">
    <div class="px-3 flex gap-2 items-center">
      <h1><?php echo $artigo['Artigo']['titulo'] ?? '' ?></h1>
    </div>

    <div class="w-max flex items-center gap-2 pt-4 px-3">

      <?php if (isset($artigo['Usuario']['nome']) and $artigo['Usuario']['nome'] and isset($artigo['Usuario']['foto']) and $artigo['Usuario']['foto'] and (int) Helper::ajuste('artigo_autor') == ATIVO) { ?>
        <span class="w-10 h-10 rounded-full">
          <img src="<?php echo $this->renderImagem($artigo['Usuario']['foto']) ?>" class="rounded-full" alt="foto-usuario" onerror="this.onerror=null; this.src='/img/sem-imagem-perfil.svg';">
        </span>
      <?php } ?>
      <div class="w-max flex flex-col justify-center items-start">
        <?php if (isset($artigo['Usuario']['nome']) and $artigo['Usuario']['nome'] and (int) Helper::ajuste('artigo_autor') == ATIVO) { ?>
          <span><?php echo $artigo['Usuario']['nome'] ?></span>
        <?php } ?>
        <div class="text-xs font-light">
          Atualizado há <?php echo traduzirDataPtBrAmigavel($artigo['Artigo']['modificado']); ?>
        </div>
      </div>
    </div>
    <div class="w-full flex flex-col pt-6 pb-2 border-b border-slate-200">
      <?php if ($conteudos) { ?>
        <?php foreach ($conteudos as $chave => $linha) : ?>
          <div class="relative w-full h-full group duration-150 rounded-lg div-pai-conteudo-editar <?php echo $classeDivPaiBtnEditar; ?>">
            <?php if ($linha['Conteudo']['tipo'] == 1) { ?>
              <button
                class="p-3 w-full flex flex-col gap-2 items-start text-left leading-7 bloco-editar-conteudo-texto"
                data-conteudo-ordem="<?php echo $linha['Conteudo']['ordem'] ?>"
                data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>"
                data-conteudo-url="<?php echo $linha['Conteudo']['url'] ?>"
                data-conteudo-tipo="<?php echo $linha['Conteudo']['tipo'] ?>"
                data-conteudo-titulo="<?php echo $linha['Conteudo']['titulo'] ?>"
                data-ordem-prox="<?php echo $ordem['prox'] ?? 0; ?>"
                data-conteudo-titulo-ocultar="<?php echo $linha['Conteudo']['titulo_ocultar']; ?>"
                data-conteudo-texto="<?php echo $linha['Conteudo']['conteudo'] ?>"
                <?php echo $acaoBotaoEditar; ?>
                <?php echo $acaoCliqueBotaoEditar; ?>
              >
                <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == INATIVO) { ?>
                  <h2 class="max-w-full <?php echo $classeConteudoTitulo; ?> pre-visualizacao-conteudo-titulo"><?php echo $linha['Conteudo']['titulo'] ?></h2>
                <?php } ?>
                <div class="w-full <?php echo $classeConteudoConteudo; ?> pre-visualizacao-conteudo-conteudo">
                  <?php if (empty($linha['Conteudo']['conteudo'])) { ?><br>
                  <?php } else { echo htmlspecialchars_decode($linha['Conteudo']['conteudo']); } ?>
                </div>
              </button>

              <?php // Edição escondido ?>
              <?php require 'modais/editar-texto.php' ?>
            <?php } ?>

            <?php if ($linha['Conteudo']['tipo'] == 2) { ?>
              <button
                class="p-3 text-left w-full bloco-editar-conteudo-imagem"
                data-conteudo-ordem="<?php echo $linha['Conteudo']['ordem'] ?>"
                data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>"
                data-conteudo-url="<?php echo $linha['Conteudo']['url'] ?>"
                data-conteudo-tipo="<?php echo $linha['Conteudo']['tipo'] ?>"
                data-conteudo-titulo="<?php echo $linha['Conteudo']['titulo'] ?>"
                data-ordem-prox="<?php echo $ordem['prox'] ?? 0; ?>"
                data-conteudo-titulo-ocultar="<?php echo $linha['Conteudo']['titulo_ocultar']; ?>"
                data-conteudo-texto="<?php echo $linha['Conteudo']['conteudo'] ?>"
                <?php echo $acaoBotaoEditar; ?>
                <?php echo $acaoCliqueBotaoEditar; ?>
              >
                <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == INATIVO) { ?>
                  <h2 class="max-w-full <?php echo $classeConteudoTitulo; ?> pre-visualizacao-conteudo-titulo"><?php echo $linha['Conteudo']['titulo'] ?></h2>
                <?php } ?>

                <img src="<?php echo $this->renderImagem($linha['Conteudo']['url']); ?>" class="w-full <?php echo $classeConteudoConteudo; ?> pre-visualizacao-conteudo-conteudo" onerror="this.onerror=null; this.src='/img/sem-imagem.svg';">
              </button>

              <?php // Edição escondido ?>
              <?php require 'modais/editar-imagem.php' ?>
            <?php } ?>

            <?php if ($linha['Conteudo']['tipo'] == 3) { ?>
              <button
                class="p-3 text-left w-full bloco-editar-conteudo-video"
                data-conteudo-ordem="<?php echo $linha['Conteudo']['ordem'] ?>"
                data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>"
                data-conteudo-url="<?php echo $linha['Conteudo']['url'] ?>"
                data-conteudo-tipo="<?php echo $linha['Conteudo']['tipo'] ?>"
                data-conteudo-titulo="<?php echo $linha['Conteudo']['titulo'] ?>"
                data-ordem-prox="<?php echo $ordem['prox'] ?? 0; ?>"
                data-conteudo-titulo-ocultar="<?php echo $linha['Conteudo']['titulo_ocultar']; ?>"
                data-conteudo-texto="<?php echo $linha['Conteudo']['conteudo'] ?>"
                <?php echo $acaoBotaoEditar; ?>
                <?php echo $acaoCliqueBotaoEditar; ?>
              >
                <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == INATIVO) { ?>
                  <h2 class="max-w-full <?php echo $classeConteudoTitulo; ?> pre-visualizacao-conteudo-titulo"><?php echo $linha['Conteudo']['titulo'] ?></h2>
                <?php } else { ?>
                  <div class="hidden px-1 py-4 group-hover:block text-2xl text-gray-400 font-extralight italic <?php echo $classeConteudoConteudo; ?> pre-visualizacao-conteudo-conteudo">*** Sem título ***</div>
                <?php } ?>

                <?php if (preg_match('/(youtube\.com|youtu\.be)/', $linha['Conteudo']['url'])) { ?>
                  <iframe src="<?php echo str_replace('youtube.com/watch?v=', 'youtube-nocookie.com/embed/', $linha['Conteudo']['url']) ?>?modestbranding=1&rel=0&showinfo=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="width: 100%; height: auto; aspect-ratio: 16/9" class="w-full"></iframe>
                <?php } elseif (preg_match('/vimeo\.com/', $linha['Conteudo']['url'])) { ?>
                  <iframe src="<?php echo str_replace('vimeo.com/', 'player.vimeo.com/video/', $linha['Conteudo']['url']) ?>" class="w-full" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="width: 100%; height: auto; aspect-ratio: 16/9"></iframe>
                <?php } else { ?>
                  <div class="p-4 text-xs text-center bg-gray-100 rounded-lg font-light text-red-900">Vídeo não suportado</div>
                <?php } ?>
              </button>

              <?php // Edição escondido ?>
              <?php require 'modais/editar-video.php' ?>
            <?php } ?>
          </div>
        <?php endforeach; ?>
      <?php } ?>
      <span class="alvo-adicionar"></span>
      <?php require_once 'modais/adicionar-texto.php' ?>
      <?php require_once 'modais/adicionar-imagem.php' ?>
      <?php require_once 'modais/adicionar-video.php' ?>
    </div>
  </div>
</div>