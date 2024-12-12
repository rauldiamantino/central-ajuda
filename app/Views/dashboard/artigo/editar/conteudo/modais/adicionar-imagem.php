<?php // Adicionar imagem ?>
<div class="mt-14 p-3 max-w-full hidden bg-white hover:bg-gray-600/10 duration-150 rounded-lg container-pre-visualizar-adicionar modal-conteudo-imagem-adicionar">
  <h3>Nova imagem</h3>
  <form method="POST" action="/d/conteudo" class="flex flex-col items-end gap-2 form-conteudo-imagem-adicionar" enctype="multipart/form-data" data-artigo-id=<?php echo $artigo['Artigo']['id'] ?> data-empresa-id="<?php echo $this->usuarioLogado['empresaId'] ?>" onsubmit="evitarDuploClique(event)">
    <input type="hidden" name="artigo_id" value="<?php echo $artigo['Artigo']['id'] ?>">
    <input type="hidden" name="tipo" value="2">
    <input type="hidden" name="url" value="" class="url-imagem">
    <input type="hidden" name="referer" value="<?php echo urlencode($botaoVoltar) ?>">

    <?php if (isset($ordem['prox'])) { ?>
      <input type="hidden" name="ordem" value="<?php echo $ordem['prox'] ?>">
    <?php } ?>
    <div class="w-full">
      <label for="conteudo-adicionar-imagem-titulo" class="block text-sm font-medium text-gray-700">Título</label>
      <input type="text" id="conteudo-adicionar-imagem-titulo" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="">
    </div>
    <div class="w-full items-start flex flex-col gap-2">
      <input type="file" accept="image/*" id="conteudo-adicionar-imagem" name="arquivo-imagem" class="hidden conteudo-adicionar-imagem-escolher" onchange="mostrarImagemConteudo(event)">
      <button type="button" for="conteudo-adicionar-imagem" class="w-full flex items-center justify-center cursor-pointer border border-gray-300 bg-white p-4 rounded-lg hover:bg-gray-100 conteudo-btn-imagem-adicionar-escolher" onclick="alterarImagemConteudo(event)">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-image text-gray-500" viewBox="0 0 16 16">
          <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
          <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z" />
        </svg>
        <span class="ml-2 text-gray-700 conteudo-txt-imagem-adicionar-escolher">Escolher Imagem</span>
      </button>
      <img src="" class="hidden object-cover w-full h-full min-h-48 bloco-imagem-elemento">
    </div>
    <div class="sticky bottom-0 px-2 py-4 w-full h-max flex flex-col lg:flex-row justify-between gap-4 bg-white rounded-lg">
      <div class="w-full bg-white">
        <label class="flex items-start gap-2 cursor-pointer">
          <input type="hidden" name="titulo_ocultar" value="0">
          <input type="checkbox" value="1" class="sr-only peer" name="titulo_ocultar" autofocus>
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
        </label>
      </div>
      <div class="w-full flex flex-col sm:flex-row gap-1 justify-end sm:gap-2 bg-white div-botoes">
        <button type="button" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?> botao-fechar-menu-adicionar-imagem" onclick="fecharModalAdicionar('imagem')">Cancelar</button>
        <button type="submit" class="w-full lg:w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-conteudo-imagem-btn-enviar">Gravar</button>
      </div>
    </div>
  </form>
</div>