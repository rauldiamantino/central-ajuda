<div class="w-full max-w-full flex flex-col py-14 overflow-x-hidden">
  <div class="pb-6 flex flex-wrap gap-2 font-light text-sm publico-migalhas">
    <a href="/" class="hover:underline whitespace-nowrap">Início</a>
    <?php if (isset($artigo['Categoria']['nome'])) { ?>
      <span>></span>
      <a href="<?php echo '/categoria/' . $artigo['Artigo']['categoria_id'] . '/' . $this->gerarSlug($artigo['Categoria']['nome']); ?>" class="hover:underline whitespace-nowrap"><?php echo $artigo['Categoria']['nome'] ?></a>
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
    <div class="w-max flex items-center gap-2 pt-4 publico-artigo-datas">

      <?php if (isset($artigo['Usuario']['nome']) and $artigo['Usuario']['nome'] and isset($artigo['Usuario']['foto']) and $artigo['Usuario']['foto'] and (int) $this->buscarAjuste('artigo_autor') == ATIVO) { ?>
        <span class="border border-gray-200 w-10 h-10 rounded-full">
          <img src="<?php echo $this->renderImagem($artigo['Usuario']['foto']) ?>" class="rounded-full" alt="foto-usuario" onerror="this.onerror=null; this.src='/img/sem-imagem-perfil.svg';">
        </span>
      <?php } ?>
      <div class="w-max h-full flex flex-col justify-center items-start">
        <?php if (isset($artigo['Usuario']['nome']) and $artigo['Usuario']['nome'] and (int) $this->buscarAjuste('artigo_autor') == ATIVO) { ?>
          <span><?php echo $artigo['Usuario']['nome'] ?></span>
        <?php } ?>
        <div class="text-xs font-light">Atualizado há <?php echo traduzirDataPtBrAmigavel($artigo['Artigo']['modificado']); ?></div>
      </div>
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
              <img src="<?php echo $this->renderImagem($linha['Conteudo']['url']); ?>" alt="<?php echo $linha['Conteudo']['titulo']; ?>" class="w-full" onerror="this.onerror=null; this.src='/img/sem-imagem.svg';">
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

  <div class="relative w-full flex items-center justify-center">
    <div class="w-full mt-6 border border-gray-100 bg-gray-200/40 rounded-lg">
      <div class="w-full p-6 <?php echo isset($feedback) ? ' hidden ' : ''; ?> flex gap-4 flex-col items-center container-feedback-inicio">
        <span class="text-gray-700 text-lg font-light">Esse artigo foi útil?</span>
        <div class="w-full h-full flex gap-4 items-center justify-center">
          <button class="text-gray-600 hover:scale-110 duration-150 w-10 feedback btn-feedback" data-feedback="1" onclick="enviarFeedback(<?php echo $artigo['Artigo']['id']; ?>, 1)">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" class="vazio">
              <path d="M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.288 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.114 1.564.415 2.068.723l.048.03c.272.165.578.348.97.484.397.136.861.217 1.466.217h3.5c.937 0 1.599-.477 1.934-1.064a1.86 1.86 0 0 0 .254-.912c0-.152-.023-.312-.077-.464.201-.263.38-.578.488-.901.11-.33.172-.762.004-1.149.069-.13.12-.269.159-.403.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2 2 0 0 0-.138-.362 1.9 1.9 0 0 0 .234-1.734c-.206-.592-.682-1.1-1.2-1.272-.847-.282-1.803-.276-2.516-.211a10 10 0 0 0-.443.05 9.4 9.4 0 0 0-.062-4.509A1.38 1.38 0 0 0 9.125.111zM11.5 14.721H8c-.51 0-.863-.069-1.14-.164-.281-.097-.506-.228-.776-.393l-.04-.024c-.555-.339-1.198-.731-2.49-.868-.333-.036-.554-.29-.554-.55V8.72c0-.254.226-.543.62-.65 1.095-.3 1.977-.996 2.614-1.708.635-.71 1.064-1.475 1.238-1.978.243-.7.407-1.768.482-2.85.025-.362.36-.594.667-.518l.262.066c.16.04.258.143.288.255a8.34 8.34 0 0 1-.145 4.725.5.5 0 0 0 .595.644l.003-.001.014-.003.058-.014a9 9 0 0 1 1.036-.157c.663-.06 1.457-.054 2.11.164.175.058.45.3.57.65.107.308.087.67-.266 1.022l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.414-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.047.047.109.177.005.488a2.2 2.2 0 0 1-.505.805l-.353.353.353.354c.006.005.041.05.041.17a.9.9 0 0 1-.121.416c-.165.288-.503.56-1.066.56z"/>
            </svg>
          </button>
          <button class="text-gray-600 hover:scale-110 duration-150 w-10 btn-feedback" data-feedback="0" onclick="enviarFeedback(<?php echo $artigo['Artigo']['id']; ?>, 0)">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" class="vazio">
              <path d="M8.864 15.674c-.956.24-1.843-.484-1.908-1.42-.072-1.05-.23-2.015-.428-2.59-.125-.36-.479-1.012-1.04-1.638-.557-.624-1.282-1.179-2.131-1.41C2.685 8.432 2 7.85 2 7V3c0-.845.682-1.464 1.448-1.546 1.07-.113 1.564-.415 2.068-.723l.048-.029c.272-.166.578-.349.97-.484C6.931.08 7.395 0 8 0h3.5c.937 0 1.599.478 1.934 1.064.164.287.254.607.254.913 0 .152-.023.312-.077.464.201.262.38.577.488.9.11.33.172.762.004 1.15.069.13.12.268.159.403.077.27.113.567.113.856s-.036.586-.113.856c-.035.12-.08.244-.138.363.394.571.418 1.2.234 1.733-.206.592-.682 1.1-1.2 1.272-.847.283-1.803.276-2.516.211a10 10 0 0 1-.443-.05 9.36 9.36 0 0 1-.062 4.51c-.138.508-.55.848-1.012.964zM11.5 1H8c-.51 0-.863.068-1.14.163-.281.097-.506.229-.776.393l-.04.025c-.555.338-1.198.73-2.49.868-.333.035-.554.29-.554.55V7c0 .255.226.543.62.65 1.095.3 1.977.997 2.614 1.709.635.71 1.064 1.475 1.238 1.977.243.7.407 1.768.482 2.85.025.362.36.595.667.518l.262-.065c.16-.04.258-.144.288-.255a8.34 8.34 0 0 0-.145-4.726.5.5 0 0 1 .595-.643h.003l.014.004.058.013a9 9 0 0 0 1.036.157c.663.06 1.457.054 2.11-.163.175-.059.45-.301.57-.651.107-.308.087-.67-.266-1.021L12.793 7l.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581s-.027-.414-.075-.581c-.05-.174-.111-.273-.154-.315l-.353-.354.353-.354c.047-.047.109-.176.005-.488a2.2 2.2 0 0 0-.505-.804l-.353-.354.353-.354c.006-.005.041-.05.041-.17a.9.9 0 0 0-.121-.415C12.4 1.272 12.063 1 11.5 1"/>
            </svg>
          </button>
        </div>
      </div>
      <div class="w-full <?php echo isset($feedback) ? '' : ' hidden '; ?> flex justify-center items-center rounded-lg container-feedback-final">
        <span class="py-4 text-gray-700 text-lg font-light flex items-center justify-center feedback-retorno-mensagem">
          <span class="<?php echo isset($feedback) ? ' hidden ' : ''; ?> efeito-loader"></span>
          <span class="hidden text-xs erro">Não foi possível registrar seu Feedback, tente novamente por favor</span>
          <span class="<?php echo isset($feedback) ? '' : ' hidden '; ?> text-base sucesso">Obrigado pelo seu feedback! 😊</span>
        </span>
      </div>
    </div>
  </div>
</div>

<?php if ($this->usuarioLogado['id'] and $this->usuarioLogado['subdominio'] and $this->usuarioLogado['empresaId'] and $this->usuarioLogado['empresaId'] == $this->empresaPadraoId) { ?>
  <div class="fixed bottom-32 right-4 sm:right-6 md:right-10 z-20 rounded-full hover:scale-110 duration-100 pers-publico-botao-editar template-cor-<?php echo $corPrimaria; ?>">
    <button type="button" onclick="window.open('<?php echo '/dashboard/artigo/editar/' . $artigo['Artigo']['codigo']; ?>')" class="flex items-center p-3 bg-black/15 rounded-full">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-10 h-10">
        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
      </svg>
    </button>
  </div>
<?php } ?>