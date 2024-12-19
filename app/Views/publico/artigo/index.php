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
  <div class="w-full flex flex-col bg-white"> <?php // rounded-lg shadow px-4 xl:px-10 ?>
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

  <?php require_once 'feedback.php'; ?>
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