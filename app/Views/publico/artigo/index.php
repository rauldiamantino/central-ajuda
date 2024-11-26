<div class="max-w-full flex flex-col py-14 overflow-x-hidden">
  <div class="pb-6 flex flex-wrap gap-2 font-light text-sm publico-migalhas">
    <a href="<?php echo baseUrl('/' . $subdominio); ?>" class="hover:underline whitespace-nowrap">Início</a>
    <?php if (isset($artigo['Categoria']['nome'])) { ?>
      <span>></span>
      <a href="<?php echo baseUrl('/' . $subdominio . '/categoria/' . $artigo['Artigo']['categoria_id']); ?>" class="hover:underline whitespace-nowrap"><?php echo $artigo['Categoria']['nome'] ?></a>
    <?php } ?>
    <span>></span>
    <span class="underline whitespace-nowrap truncate"><?php echo $artigo['Artigo']['titulo'] ?></span>
  </div>
  <div class="w-full flex flex-col px-4 xl:px-10 bg-white rounded-lg shadow">
    <div class="flex flex-col justify-between items-start gap-2 pt-10 pers-publico-artigo template-cor-<?php echo $corPrimaria; ?> publico-artigo-blocos publico-artigo-topo">
      <div class="flex gap-2 items-center">
        <h1 class="publico-artigo-titulo"><?php echo $artigo['Artigo']['titulo'] ?></h1>
        <?php if ($artigo['Artigo']['ativo'] == INATIVO) { ?>
          <div class="text-red-800">
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
              <circle cx="8" cy="8" r="8"/>
            </svg>
          </div>
        <?php } ?>
      </div>
      <div class="text-xs font-light publico-artigo-datas">
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
    </div>
    <div class="w-full flex flex-col gap-2 py-10 pers-publico-artigo template-cor-<?php echo $corPrimaria; ?> publico-artigo-blocos">
      <?php if ($conteudos) { ?>
        <?php foreach ($conteudos as $chave => $linha) : ?>
          <?php if ($linha['Conteudo']['tipo'] == 1) { ?>
            <div class="w-full flex flex-col gap-2 leading-7 publico-artigo-bloco">
              <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == 0) { ?>
                <h2><?php echo $linha['Conteudo']['titulo'] ?></h2>
              <?php } ?>
              <div class="w-full publico-editorjs">
                <?php
                if (empty($linha['Conteudo']['conteudo'])) {
                  echo '<br>';
                }
                else {
                  echo htmlspecialchars_decode($linha['Conteudo']['conteudo']);
                }
                ?>
              </div>
            </div>
          <?php } ?>
          <?php if ($linha['Conteudo']['tipo'] == 2) { ?>
            <div class="publico-artigo-bloco">
              <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == 0) { ?>
                <h2><?php echo $linha['Conteudo']['titulo'] ?></h2>
              <?php } ?>
              <img src="<?php echo $linha['Conteudo']['url'] ?>" class="w-full">
            </div>
          <?php } ?>
          <?php if ($linha['Conteudo']['tipo'] == 3) { ?>
            <div class="publico-artigo-bloco">
              <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == 0) { ?>
                <h2><?php echo $linha['Conteudo']['titulo'] ?></h2>
              <?php } ?>
              <?php if (preg_match('/(youtube\.com|youtu\.be)/', $linha['Conteudo']['url'])) { ?>
                <iframe src="<?php echo str_replace('youtube.com/watch?v=', 'youtube-nocookie.com/embed/', $linha['Conteudo']['url']) ?>?modestbranding=1&rel=0&showinfo=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="width: 100%; height: auto; aspect-ratio: 16/9" class="w-full"></iframe>
              <?php } elseif (preg_match('/vimeo\.com/', $linha['Conteudo']['url'])) { ?>
                <iframe src="<?php echo str_replace('vimeo.com/', 'player.vimeo.com/video/', $linha['Conteudo']['url']) ?>" class="w-full" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="width: 100%; height: auto; aspect-ratio: 16/9"></iframe>
              <?php } else { ?>
                <div class="p-4 text-xs text-center bg-gray-100 rounded-lg font-light text-red-900">Vídeo não suportado</div>
              <?php } ?>
            </div>
          <?php } ?>
        <?php endforeach; ?>
      <?php } ?>
    </div>
  </div>
</div>