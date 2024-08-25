<?php // Adicionar texto ?>
<dialog class="relative pt-4 px-4 w-full sm:w-[1000px] rounded-lg shadow editor-container modal-conteudo-texto-adicionar">
  <form method="POST" action="/conteudo" class="flex flex-col items-end gap-2 editor-container__editor" enctype="multipart/form-data">
    <input type="hidden" name="tipo" value="1">
    <input type="hidden" name="artigo_id" value="<?php echo $artigo['Artigo.id'] ?>">
    <?php if (isset($ordem['prox'])) { ?>
      <input type="hidden" name="ordem" value="<?php echo $ordem['prox'] ?>">
    <?php } ?>
    <div class="w-full">
      <label for="conteudo-adicionar-texto-titulo" class="block text-sm font-medium text-gray-700">Título</label>
      <input type="text" id="conteudo-adicionar-texto-titulo" name="titulo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="">
    </div>
    <div class="editor-container_classic-editor">
      <textarea name="conteudo" id="conteudo-adicionar-texto-conteudo" class="border border-gray-300 w-full p-2 h-56 rounded-lg ckeditor"></textarea>
    </div>
    <div class="sticky bottom-0 py-4 w-full h-max flex flex-col md:flex-row justify-between gap-4 bg-white">
      <div class="w-full">
        <label class="flex items-start gap-2 cursor-pointer">
          <input type="hidden" name="titulo_ocultar" value="0">
          <input type="checkbox" value="1" class="sr-only peer" name="titulo_ocultar">
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
        </label>
      </div>
      <div class="flex gap-4">
        <button type="button" class="w-full md:w-max border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-gray-700 text-xs rounded-lg modal-texto-adicionar-btn-cancelar">Cancelar</button>
        <button type="submit" class="w-full md:w-max flex gap-2 items-center justify-center py-2 px-4 bg-green-800 hover:bg-green-600 text-white text-xs rounded-lg modal-conteudo-texto-btn-enviar">Adicionar</button>
      </div>
    </div>
  </form>
</dialog>

<?php // Adicionar imagem ?>
<dialog class="p-4 w-full sm:w-[600px] rounded-lg shadow modal-conteudo-imagem-adicionar">
  <form method="POST" action="/conteudo" class="flex flex-col items-end gap-2 form-conteudo-imagem-adicionar" enctype="multipart/form-data" data-artigo-id=<?php echo $artigo['Artigo.id'] ?> data-empresa-id=<?php echo $artigo['Artigo.empresa_id'] ?>>
    <input type="hidden" name="artigo_id" value="<?php echo $artigo['Artigo.id'] ?>">
    <input type="hidden" name="tipo" value="2">
    <input type="hidden" name="url" value="" class="url-imagem">
    <?php if (isset($ordem['prox'])) { ?>
      <input type="hidden" name="ordem" value="<?php echo $ordem['prox'] ?>">
    <?php } ?>
    <div class="w-full">
      <label for="conteudo-adicionar-imagem-titulo" class="block text-sm font-medium text-gray-700">Título</label>
      <input type="text" id="conteudo-adicionar-imagem-titulo" name="titulo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="">
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
      <div class="relative flex flex-col gap-2 w-full h-48 hidden bloco-imagem">
        <img src="" class="object-cover w-full h-full">
      </div>
    </div>
    <div class="pt-2 w-full flex flex-col md:flex-row justify-between gap-4">
      <div class="w-full">
        <label class="flex items-start gap-2 cursor-pointer">
          <input type="hidden" name="titulo_ocultar" value="0">
          <input type="checkbox" value="1" class="sr-only peer" name="titulo_ocultar">
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
        </label>
      </div>
      <div class="flex gap-4">
        <button type="button" class="w-full md:w-max border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-gray-700 text-xs rounded-lg modal-conteudo-imagem-btn-cancelar-adicionar">Cancelar</button>
        <button type="submit" class="w-full md:w-max flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg modal-conteudo-imagem-btn-enviar">Adicionar</button>
      </div>
    </div>
  </form>
</dialog>

<?php // Adicionar vídeo ?>
<dialog class="p-4 w-full sm:w-[600px] rounded-lg shadow modal-conteudo-video-adicionar">
  <form method="POST" action="/conteudo" class="flex flex-col items-end gap-2" enctype="multipart/form-data">
    <input type="hidden" name="artigo_id" value="<?php echo $artigo['Artigo.id'] ?>">
    <input type="hidden" name="tipo" value="3">
    <?php if (isset($ordem['prox'])) { ?>
      <input type="hidden" name="ordem" value="<?php echo $ordem['prox'] ?>">
    <?php } ?>
    <div class="w-full">
      <label for="conteudo-adicionar-video-titulo" class="block text-sm font-medium text-gray-700">Título</label>
      <input type="text" id="conteudo-adicionar-video-titulo" name="titulo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="">
    </div>
    <input type="text" name="url" id="conteudo-adicionar-video-url" class="border border-gray-300 w-full p-2 rounded-lg text-sm" placeholder="https://www.youtube.com/watch?v=00000000000">
    <div class="pt-2 w-full flex flex-col md:flex-row justify-between gap-4">
      <div class="w-full">
        <label class="flex items-start gap-2 cursor-pointer">
          <input type="hidden" name="titulo_ocultar" value="0">
          <input type="checkbox" value="1" class="sr-only peer" name="titulo_ocultar">
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
        </label>
      </div>
      <div class="flex gap-4">
        <button type="button" class="w-full md:w-max border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-gray-700 text-xs rounded-lg modal-conteudo-video-btn-cancelar-adicionar">Cancelar</button>
        <button type="submit" class="w-full md:w-max flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg modal-conteudo-video-btn-enviar">Adicionar</button>
      </div>
    </div>
  </form>
</dialog>