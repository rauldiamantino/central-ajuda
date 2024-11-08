<div class="w-full min-h-full flex flex-col p-4 hidden editar-fundo">
  <h2 class="relative text-2xl font-semibold mb-4">
    Editar <span class="text-gray-400 font-light italic text-sm">(Conteúdo #<?php echo $conteudo['Conteudo']['id']; ?></span>
    <span class="text-gray-400 font-light italic hover:underline text-sm"><a href="/<?php echo $this->usuarioLogado['subdominio']; ?>/artigo/<?php echo $conteudo['Conteudo']['artigo_id'] ?>" target="_blank">- Artigo #<?php echo $conteudo['Conteudo']['artigo_id']; ?>)</a></span>
  </h2>
  <div class="w-full lg:w-1/2">
    <?php // Editar texto ?>
    <?php if ($conteudo['Conteudo']['tipo'] == 1) { ?>
      <div class="border border-slate-300 p-4 md:w-full rounded-lg shadow editor-container bg-white">
        <form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/conteudo/' . $conteudo['Conteudo']['id']); ?>" class="flex flex-col items-end gap-2 editor-container__editor form-conteudo-texto-editar" enctype="multipart/form-data">
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="artigo_id" value="<?php echo $conteudo['Conteudo']['artigo_id'] ?>">
          <input type="hidden" name="tipo" value="1">
          <input type="hidden" name="referer" value="<?php echo $botaoVoltar; ?>">

          <div class="w-full">
            <label for="conteudo-editar-texto-titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" id="conteudo-editar-texto-titulo" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $conteudo['Conteudo']['titulo'] ?>">
          </div>
          <div class="w-full editor-container_classic-editor">
            <textarea name="conteudo" class="border border-gray-300 w-full p-2 min-h-56 rounded-lg bg-slate-50 conteudo-texto-editar ckeditor" data-conteudo="<?php echo $conteudo['Conteudo']['conteudo'] ?>"></textarea>
          </div>
          <div class="sticky bottom-0 py-4 w-full h-max flex flex-col lg:flex-row justify-between gap-4 bg-white">
            <div class="w-full bg-white">
              <label class="flex items-start gap-2 cursor-pointer">
                <input type="hidden" name="titulo_ocultar" value="0">
                <input type="checkbox" value="1" class="sr-only peer conteudo-editar-texto-titulo-ocultar" name="titulo_ocultar" <?php echo $conteudo['Conteudo']['titulo_ocultar'] ? 'checked' : '' ?>>
                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
                <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
              </label>
            </div>
            <div class="flex gap-2 bg-white">
              <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $conteudo['Conteudo']['artigo_id'] . '?referer=' . $botaoVoltar); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>modal-texto-editar-btn-cancelar">Voltar</a>
              <button type="submit" class="w-full lg:w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-conteudo-texto-btn-enviar">Gravar</button>
            </div>
          </div>
        </form>
      </div>
    <?php } ?>

    <?php // Editar imagem ?>
    <?php if ($conteudo['Conteudo']['tipo'] == 2) { ?>
      <div class="border border-slate-300 p-4 md:w-full rounded-lg shadow modal-conteudo-imagem-editar bg-white">
        <form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/conteudo/' . $conteudo['Conteudo']['id']); ?>" class="flex flex-col items-end gap-2" enctype="multipart/form-data" data-artigo-id=<?php echo $conteudo['Conteudo']['artigo_id'] ?> data-empresa-id=<?php echo $conteudo['Conteudo']['empresa_id'] ?>>
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="artigo_id" value="<?php echo $conteudo['Conteudo']['artigo_id'] ?>">
          <input type="hidden" name="tipo" value="2">
          <input type="hidden" name="url" value="" class="url-imagem">
          <input type="hidden" name="referer" value="<?php echo $botaoVoltar; ?>">

          <div class="w-full">
            <label for="conteudo-editar-imagem-titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" id="conteudo-editar-imagem-titulo" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $conteudo['Conteudo']['titulo'] ?>">
          </div>
          <div class="w-full items-start flex flex-col gap-2">
            <input type="file" accept="image/*" id="conteudo-editar-imagem" class="hidden conteudo-editar-imagem-escolher">
            <button type="button" for="conteudo-editar-imagem" class="w-full flex items-center justify-center cursor-pointer border border-gray-300 bg-gray-50 p-4 rounded-lg hover:bg-gray-100 conteudo-btn-imagem-editar-escolher">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-image text-gray-500" viewBox="0 0 16 16">
                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z" />
              </svg>
              <span class="ml-2 text-gray-700 conteudo-txt-imagem-editar-escolher">Alterar Imagem</span>
            </button>
            <div class="border border-slate-300 p-4 relative flex flex-col gap-2 w-full min-h-48 rounded-md">
              <img src="<?php echo $conteudo['Conteudo']['url'] ?>" class="object-cover w-full h-full opacity-0 transition-opacity duration-300 ease-in-out">
            </div>
          </div>
          <div class="sticky bottom-0 py-4 w-full h-max flex flex-col lg:flex-row justify-between gap-4 bg-white">
            <div class="flex gap-4 bg-white">
              <label class="flex items-start gap-2 cursor-pointer">
                <input type="hidden" name="titulo_ocultar" value="0">
                <input type="checkbox" value="1" class="sr-only peer conteudo-editar-imagem-titulo-ocultar" name="titulo_ocultar" <?php echo $conteudo['Conteudo']['titulo_ocultar'] ? 'checked' : '' ?>>
                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
                <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
              </label>
            </div>
            <div class="flex gap-2">
              <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $conteudo['Conteudo']['artigo_id'] . '?referer=' . $botaoVoltar); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>modal-conteudo-imagem-btn-cancelar">Voltar</a>
              <button type="submit" class="w-full md-w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-conteudo-imagem-btn-enviar">Gravar</button>
            </div>
          </div>
        </form>
      </div>
    <?php } ?>

    <?php // Editar vídeo ?>
    <?php if ($conteudo['Conteudo']['tipo'] == 3) { ?>
      <div class="border border-slate-300 p-4 md:w-full rounded-lg shadow modal-conteudo-video-editar bg-white">
        <form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/conteudo/' . $conteudo['Conteudo']['id']); ?>" class="flex flex-col items-end gap-2" enctype="multipart/form-data">
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="artigo_id" value="<?php echo $conteudo['Conteudo']['artigo_id'] ?>">
          <input type="hidden" name="tipo" value="3">
          <input type="hidden" name="referer" value="<?php echo $botaoVoltar; ?>">

          <div class="w-full">
            <label for="conteudo-editar-video-titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" id="conteudo-editar-video-titulo" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $conteudo['Conteudo']['titulo'] ?>">
          </div>
          <input type="text" name="url" id="conteudo-editar-video-url" class="<?php echo CLASSES_DASH_INPUT; ?>" placeholder="https://www.youtube.com/watch?v=00000000000" value="<?php echo $conteudo['Conteudo']['url'] ?>">
          <div class="sticky bottom-0 py-4 w-full h-max flex flex-col lg:flex-row justify-between gap-4 bg-white">
            <div class="flex gap-4 bg-white">
              <label class="flex items-start gap-2 cursor-pointer">
                <input type="hidden" name="titulo_ocultar" value="0">
                <input type="checkbox" value="1" class="sr-only peer conteudo-editar-video-titulo-ocultar" name="titulo_ocultar" <?php echo $conteudo['Conteudo']['titulo_ocultar'] ? 'checked' : '' ?>>
                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
                <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
              </label>
            </div>
            <div class="flex gap-2">
              <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $conteudo['Conteudo']['artigo_id'] . '?referer=' . $botaoVoltar); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>modal-conteudo-video-btn-cancelar">Voltar</a>
              <button type="submit" class="w-full md-w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-conteudo-video-btn-enviar">Gravar</button>
            </div>
          </div>
        </form>
      </div>
    <?php } ?>
    <div class="w-full"></div>
  </div>
</div>