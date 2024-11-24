<div class="w-full h-full bg-white py-5 lg:p-10 rounded-md pers-publico-artigo template-cor-<?php echo $this->usuarioLogado['corPrimaria']; ?> dashboard-pre-visualizacao">
  <div class="flex flex-col justify-between items-start gap-2 md:pt-5 px-3">
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
    <div class="px-3 text-xs font-light">
      <?php if ((! isset($artigo['Usuario']['nome']) or empty($artigo['Usuario']['nome'])) and $this->buscarAjuste('artigo_criado') == ATIVO and $this->buscarAjuste('artigo_autor') == ATIVO) { ?>
        <div>Criado em <?php echo traduzirDataPtBr($artigo['Artigo']['criado']); ?></div>
      <?php } ?>

      <?php if (isset($artigo['Usuario']['nome']) and $artigo['Usuario']['nome'] and $this->buscarAjuste('artigo_criado') == ATIVO and $this->buscarAjuste('artigo_autor') == ATIVO) { ?>
        <div>Criado por <span class="font-semibold"> <?php echo $artigo['Usuario']['nome'] ?> </span> em <?php echo traduzirDataPtBr($artigo['Artigo']['criado']); ?></div>
      <?php } ?>

      <?php if (isset($artigo['Usuario']['nome']) and $artigo['Usuario']['nome'] and $this->buscarAjuste('artigo_criado') == INATIVO and $this->buscarAjuste('artigo_autor') == ATIVO) { ?>
        <div>Criado por <span class="font-semibold"> <?php echo $artigo['Usuario']['nome'] ?> </span></div>
      <?php } ?>

      <?php if (isset($artigo['Artigo']['criado']) and $artigo['Artigo']['criado'] and $this->buscarAjuste('artigo_criado') == ATIVO and $this->buscarAjuste('artigo_autor') == INATIVO) { ?>
        <div>Criado em <?php echo traduzirDataPtBr($artigo['Artigo']['criado']); ?></div>
      <?php } ?>

      <?php if (isset($artigo['Artigo']['modificado']) and $artigo['Artigo']['modificado'] and $this->buscarAjuste('artigo_modificado') == ATIVO) { ?>
        <div>Última atualização: <?php echo traduzirDataPtBr($artigo['Artigo']['modificado']); ?></div>
      <?php } ?>
    </div>

    <div class="w-full flex flex-col pt-6 pb-2 border-b border-slate-200">
      <?php if ($conteudos) { ?>
        <?php foreach ($conteudos as $chave => $linha) : ?>
          <div class="relative w-full h-full group hover:bg-gray-600/10 duration-150 rounded-lg div-pai-conteudo-editar">
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
                onclick="abrirModalEditar(event)"
              >
                <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == INATIVO) { ?>
                  <h2 class="pointer-events-none"><?php echo $linha['Conteudo']['titulo'] ?></h2>
                <?php } ?>
                <div class="pointer-events-none">
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
                onclick="abrirModalEditar(event)"
              >
                <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == INATIVO) { ?>
                  <h2 class="pointer-events-none"><?php echo $linha['Conteudo']['titulo'] ?></h2>
                <?php } ?>
                <img src="<?php echo $linha['Conteudo']['url'] ?>" class="pointer-events-none w-full">
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
                onclick="abrirModalEditar(event)"
              >
                <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == INATIVO) { ?>
                  <h2 class="pointer-events-none"><?php echo $linha['Conteudo']['titulo'] ?></h2>
                <?php } else { ?>
                  <div class="hidden px-1 py-4 group-hover:block text-2xl pointer-events-none text-gray-400 font-extralight italic">*** Sem título ***</div>
                <?php } ?>

                <?php if (preg_match('/(youtube\.com|youtu\.be)/', $linha['Conteudo']['url'])) { ?>
                  <iframe src="<?php echo str_replace('watch?v=', 'embed/', $linha['Conteudo']['url']) ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="width: 100%; height: auto; aspect-ratio: 16/9" class="w-full"></iframe>
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