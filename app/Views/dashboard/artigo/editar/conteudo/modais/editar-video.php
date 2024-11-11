<?php // Editar vídeo ?>
<dialog class="border border-slate-300 p-4 bg-white w-full md:w-[500px] rounded-lg shadow modal-conteudo-video-adicionar modal-conteudo-video-editar">
  <form method="POST" class="flex flex-col items-end gap-2" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="artigo_id" value="<?php echo $artigo['Artigo']['id'] ?>">
    <input type="hidden" name="tipo" value="3">
    <input type="hidden" name="referer" value="<?php echo $botaoVoltar; ?>">

    <div class="w-full">
      <label for="conteudo-editar-video-titulo" class="block text-sm font-medium text-gray-700">Título</label>
      <input type="text" id="conteudo-editar-video-titulo" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="">
    </div>
    <input type="text" name="url" id="conteudo-editar-video-url" class="<?php echo CLASSES_DASH_INPUT; ?>" placeholder="https://www.youtube.com/watch?v=00000000000" value="">
    <div class="sticky bottom-0 py-4 w-full h-max flex flex-col lg:flex-row justify-between gap-4 bg-white">
      <div class="flex gap-4 bg-white">
        <label class="flex items-start gap-2 cursor-pointer">
          <input type="hidden" name="titulo_ocultar" value="0">
          <input type="checkbox" value="1" class="sr-only peer conteudo-editar-video-titulo-ocultar" name="titulo_ocultar">
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700" autofocus>Ocultar título na publicação</span>
        </label>
      </div>
      <div class="flex gap-2">
        <button type="button" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>" onclick="document.querySelector('.modal-conteudo-video-editar').close()">Cancelar</button>
        <button type="submit" class="w-full md-w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-conteudo-video-btn-enviar">Gravar</button>
      </div>
    </div>
  </form>
</dialog>