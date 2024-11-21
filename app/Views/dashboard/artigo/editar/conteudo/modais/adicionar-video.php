<?php // Adicionar vídeo ?>
<div class="border-t border-slate-300 mt-14 pt-10 max-w-full hidden modal-conteudo-video-adicionar">
  <h3>Novo vídeo</h3>
  <form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/conteudo'); ?>" class="flex flex-col items-end gap-2" enctype="multipart/form-data" onsubmit="evitarDuploClique(event)">
    <input type="hidden" name="artigo_id" value="<?php echo $artigo['Artigo']['id'] ?>">
    <input type="hidden" name="tipo" value="3">
    <input type="hidden" name="referer" value="<?php echo $botaoVoltar ?>">

    <?php if (isset($ordem['prox'])) { ?>
      <input type="hidden" name="ordem" value="<?php echo $ordem['prox'] ?>">
    <?php } ?>
    <div class="w-full">
      <label for="conteudo-adicionar-video-titulo" class="block text-sm font-medium text-gray-700">Título</label>
      <input type="text" id="conteudo-adicionar-video-titulo" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="">
    </div>
    <div class="w-full">
      <label for="conteudo-adicionar-video-url" class="block text-sm font-medium text-gray-700">URL do vídeo</label>
      <input type="text" name="url" id="conteudo-adicionar-video-url" class="<?php echo CLASSES_DASH_INPUT; ?>">
    </div>
    <div class="pb-2 w-full text-xs text-gray-700 flex flex-col items-start justify-center">
      <span>Exemplos de URLs aceitas</span>
      <span class="font-light">https://youtube.com/watch?v=dQw4w9WgXcQ</span>
      <span class="font-light">https://vimeo.com/76979871</span>
    </div>
    <div class="sticky bottom-0 py-4 w-full h-max flex flex-col lg:flex-row justify-between gap-4 bg-white">
      <div class="w-full">
        <label class="flex items-start gap-2 cursor-pointer">
          <input type="hidden" name="titulo_ocultar" value="0">
          <input type="checkbox" value="1" class="sr-only peer" name="titulo_ocultar" autofocus>
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
        </label>
      </div>
      <div class="flex gap-2">
        <button type="button" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?> botao-fechar-menu-adicionar-video" onclick="voltarAoTopo('video')">Cancelar</button>
        <button type="submit" class="w-full lg:w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-conteudo-video-btn-enviar">Gravar</button>
      </div>
    </div>
  </form>
</div>