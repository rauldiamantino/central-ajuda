<div class="relative w-full min-h-screen flex flex-col hidden adicionar-fundo">
  <div class="mb-5 w-full flex flex-col">
    <h2 class="text-3xl font-semibold flex gap-2 items-center">Adicionar conteúdo<span class="text-gray-400 font-light italic hover:underline text-sm"><a href="/<?php echo $this->usuarioLogado['subdominio']; ?>/artigo/<?php echo $artigoId ?>" target="_blank">(Artigo #<?php echo $artigoId; ?>)</a></span></h2>
    <p class="text-gray-600">Vamos dar aquele toque nos seus tutoriais de ajuda e deixá-los ainda melhores!</p>
  </div>
  <div class="mt-4 mb-10 w-full <?php echo $tipo == 1 ? '' : 'lg:w-1/2'?>">
  <?php // Adicionar texto ?>
  <?php if ($tipo == 1) { ?>
    <div class="border border-slate-300 p-4 md:w-full rounded-lg shadow editor-container modal-conteudo-texto-adicionar bg-white">
      <form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/conteudo'); ?>" class="flex flex-col items-end gap-2 editor-container__editor" enctype="multipart/form-data">
        <input type="hidden" name="tipo" value="1">
        <input type="hidden" name="artigo_id" value="<?php echo $artigoId ?>">
        <input type="hidden" name="referer" value="<?php echo $botaoVoltar ?>">

        <?php if (isset($ordem['prox'])) { ?>
          <input type="hidden" name="ordem" value="<?php echo $ordem['prox'] ?>">
        <?php } ?>
        <div class="w-full">
          <label for="conteudo-adicionar-texto-titulo" class="block text-sm font-medium text-gray-700">Título</label>
          <input type="text" id="conteudo-adicionar-texto-titulo" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="">
        </div>
        <div class="w-full editor-container_classic-editor">
          <textarea name="conteudo" class="border border-gray-300 w-full p-2 min-h-56 rounded-lg ckeditor" name="conteudo"></textarea>
        </div>
        <div class="sticky z-20 bottom-0 py-4 w-full h-max flex flex-col lg:flex-row justify-between gap-4 bg-white">
          <div class="w-full bg-white">
            <label class="flex items-start gap-2 cursor-pointer">
              <input type="hidden" name="titulo_ocultar" value="0">
              <input type="checkbox" value="1" class="sr-only peer" name="titulo_ocultar">
              <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
              <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
            </label>
          </div>
          <div class="flex gap-2">
            <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $artigoId . '?referer=' . $botaoVoltar); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?> modal-texto-adicionar-btn-cancelar">Voltar</a>
            <button type="submit" class="w-full lg:w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-conteudo-texto-btn-enviar">Gravar</button>
          </div>
        </div>
      </form>
    </div>
  <?php } ?>

  <?php // Adicionar imagem ?>
  <?php if ($tipo == 2) { ?>
    <div class="border border-slate-300 p-4 md:w-full rounded-lg shadow modal-conteudo-imagem-adicionar bg-white">
      <form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/conteudo'); ?>" class="flex flex-col items-end gap-2 form-conteudo-imagem-adicionar" enctype="multipart/form-data" data-artigo-id=<?php echo $artigoId ?> data-empresa-id="<?php echo $this->usuarioLogado['empresaId'] ?>">
        <input type="hidden" name="artigo_id" value="<?php echo $artigoId ?>">
        <input type="hidden" name="tipo" value="2">
        <input type="hidden" name="url" value="" class="url-imagem">
        <input type="hidden" name="referer" value="<?php echo $botaoVoltar ?>">

        <?php if (isset($ordem['prox'])) { ?>
          <input type="hidden" name="ordem" value="<?php echo $ordem['prox'] ?>">
        <?php } ?>
        <div class="w-full bg-white">
          <label for="conteudo-adicionar-imagem-titulo" class="block text-sm font-medium text-gray-700">Título</label>
          <input type="text" id="conteudo-adicionar-imagem-titulo" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="">
        </div>
        <div class="w-full items-start flex flex-col gap-2">
          <input type="file" accept="image/*" id="conteudo-adicionar-imagem" class="hidden conteudo-adicionar-imagem-escolher">
          <button type="button" for="conteudo-adicionar-imagem" class="w-full flex items-center justify-center cursor-pointer border border-gray-300 bg-gray-50 p-4 rounded-lg hover:bg-gray-100 conteudo-btn-imagem-adicionar-escolher">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-image text-gray-500" viewBox="0 0 16 16">
              <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
              <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z" />
            </svg>
            <span class="ml-2 text-gray-700 conteudo-txt-imagem-adicionar-escolher">Escolher Imagem</span>
          </button>
          <div class="relative flex flex-col gap-2 w-full min-h-48 hidden bloco-imagem">
            <img src="" class="object-cover w-full h-full">
          </div>
        </div>
        <div class="sticky bottom-0 py-4 w-full h-max flex flex-col lg:flex-row justify-between gap-4 bg-white">
          <div class="w-full bg-white">
            <label class="flex items-start gap-2 cursor-pointer">
              <input type="hidden" name="titulo_ocultar" value="0">
              <input type="checkbox" value="1" class="sr-only peer" name="titulo_ocultar">
              <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
              <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
            </label>
          </div>
          <div class="flex gap-2">
            <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $artigoId . '?referer=' . $botaoVoltar); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?> modal-conteudo-imagem-btn-cancelar-adicionar">Voltar</a>
            <button type="submit" class="w-full lg:w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-conteudo-imagem-btn-enviar">Gravar</button>
          </div>
        </div>
      </form>
    </div>
  <?php } ?>

  <?php // Adicionar vídeo ?>
  <?php if ($tipo == 3) { ?>
    <div class="border border-slate-300 p-4 md:w-full rounded-lg shadow modal-conteudo-video-adicionar bg-white">
      <form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/conteudo'); ?>" class="flex flex-col items-end gap-2" enctype="multipart/form-data">
        <input type="hidden" name="artigo_id" value="<?php echo $artigoId ?>">
        <input type="hidden" name="tipo" value="3">
        <input type="hidden" name="referer" value="<?php echo $botaoVoltar ?>">

        <?php if (isset($ordem['prox'])) { ?>
          <input type="hidden" name="ordem" value="<?php echo $ordem['prox'] ?>">
        <?php } ?>
        <div class="w-full">
          <label for="conteudo-adicionar-video-titulo" class="block text-sm font-medium text-gray-700">Título</label>
          <input type="text" id="conteudo-adicionar-video-titulo" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="">
        </div>
        <input type="text" name="url" id="conteudo-adicionar-video-url" class="<?php echo CLASSES_DASH_INPUT; ?>" placeholder="https://www.youtube.com/watch?v=00000000000">
        <div class="sticky bottom-0 py-4 w-full h-max flex flex-col lg:flex-row justify-between gap-4 bg-white">
          <div class="w-full">
            <label class="flex items-start gap-2 cursor-pointer">
              <input type="hidden" name="titulo_ocultar" value="0">
              <input type="checkbox" value="1" class="sr-only peer" name="titulo_ocultar">
              <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
              <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
            </label>
          </div>
          <div class="flex gap-2">
            <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $artigoId . '?referer=' . $botaoVoltar); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?> modal-conteudo-video-btn-cancelar-adicionar">Voltar</a>
            <button type="submit" class="w-full lg:w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-conteudo-video-btn-enviar">Gravar</button>
          </div>
        </div>
      </form>
    </div>
  <?php } ?>
</div>
