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
?>

<div
  class="w-full h-full bg-white py-5 lg:p-10 rounded-md pers-publico-artigo template-cor-<?php echo Helper::ajuste('publico_cor_primaria'); ?> dashboard-pre-visualizacao"
  data-usuario-nivel="<?php echo $this->usuarioLogado['nivel']; ?>"
>
  <div class="flex flex-col justify-between items-start gap-2 px-3">

    <div class="w-full flex justify-end items-center">
      <div class="w-max px-3">
        <button type="button" class="border border-gray-300 flex gap-2 items-center justify-center text-sm hover:bg-gray-100/25 p-1 rounded pre-visualizacao-bloqueado" onclick="desbloquearEdicao()">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5">
            <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
          </svg>
          Bloqueado
        </button>
        <button type="button" class="hidden border border-blue-900/75 text-blue-900 flex gap-2 items-center justify-center text-sm hover:bg-blue-100/25 p-1 rounded pre-visualizacao-bloquear" onclick="bloquearEdicao()">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5">
            <path d="M18 1.5c2.9 0 5.25 2.35 5.25 5.25v3.75a.75.75 0 0 1-1.5 0V6.75a3.75 3.75 0 1 0-7.5 0v3a3 3 0 0 1 3 3v6.75a3 3 0 0 1-3 3H3.75a3 3 0 0 1-3-3v-6.75a3 3 0 0 1 3-3h9v-3c0-2.9 2.35-5.25 5.25-5.25Z" />
          </svg>
          Bloquear
        </button>
      </div>
    </div>

    <div class="px-3 flex gap-2 items-center">
      <h1><?php echo $artigo['Artigo']['titulo'] ?? '' ?></h1>
      <?php if (isset($artigo['Artigo']['ativo']) and $artigo['Artigo']['ativo'] == INATIVO) { ?>
        <div class="text-red-800">
          <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
            <circle cx="8" cy="8" r="8"/>
          </svg>
        </div>
      <?php } ?>
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
          <div class="relative w-full h-full group duration-150 rounded-lg div-pai-conteudo-editar">
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
                disabled
              >
                <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == INATIVO) { ?>
                  <h2 class="max-w-full pre-visualizacao-conteudo-titulo"><?php echo $linha['Conteudo']['titulo'] ?></h2>
                <?php } ?>
                <div class="w-full pre-visualizacao-conteudo-conteudo">
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
                disabled
              >
                <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == INATIVO) { ?>
                  <h2 class="max-w-full pre-visualizacao-conteudo-titulo"><?php echo $linha['Conteudo']['titulo'] ?></h2>
                <?php } ?>

                <img src="<?php echo $this->renderImagem($linha['Conteudo']['url']); ?>" class="w-full pre-visualizacao-conteudo-conteudo" onerror="this.onerror=null; this.src='/img/sem-imagem.svg';">
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
                disabled
              >
                <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == INATIVO) { ?>
                  <h2 class="max-w-full pre-visualizacao-conteudo-titulo"><?php echo $linha['Conteudo']['titulo'] ?></h2>
                <?php } else { ?>
                  <div class="hidden px-1 py-4 group-hover:block text-2xl text-gray-400 font-extralight italic pre-visualizacao-conteudo-conteudo">*** Sem título ***</div>
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