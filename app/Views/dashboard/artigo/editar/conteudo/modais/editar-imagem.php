<?php // Editar imagem ?>
<div class="p-3 w-full hidden container-pre-visualizar container-conteudo-imagem-editar" data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>" data-conteudo-tipo="<?php echo $linha['Conteudo']['tipo'] ?>">
  <div class="font-extralight pb-10 flex flex-col">
    <div class="font-normal text-2xl">Edição de imagem</div>
    <span>Não esqueca de clicar em gravar :)</span>
  </div>
  <form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/conteudo/' . $linha['Conteudo']['id']); ?>" class="flex flex-col items-end gap-2" enctype="multipart/form-data" data-artigo-id=<?php echo $linha['Conteudo']['artigo_id'] ?> data-empresa-id=<?php echo $linha['Conteudo']['empresa_id'] ?> onsubmit="evitarDuploClique(event)">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="artigo_id" value="<?php echo $linha['Conteudo']['artigo_id'] ?>">
    <input type="hidden" name="tipo" value="2">
    <input type="hidden" name="url" value="<?php echo $linha['Conteudo']['url'] ?>" class="url-imagem">
    <input type="hidden" name="referer" value="<?php echo urlencode($botaoVoltar); ?>">

    <div class="w-full">
      <label for="conteudo-editar-imagem-titulo-<?php echo $linha['Conteudo']['id'] ?>" class="block text-sm font-medium text-gray-700">Título</label>
      <input type="text" id="conteudo-editar-imagem-titulo" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $linha['Conteudo']['titulo']; ?>">
    </div>
    <div class="w-full items-start flex flex-col gap-2">
      <input type="file" accept="image/*" id="conteudo-editar-imagem" name="arquivo-imagem" class="hidden conteudo-editar-imagem-escolher" onchange="mostrarImagemConteudoEditar(event)">
      <button type="button" for="conteudo-editar-imagem" class="w-full flex items-center justify-center cursor-pointer border border-gray-300 bg-white p-4 rounded-lg hover:bg-gray-100 conteudo-btn-imagem-editar-escolher" onclick="alterarImagemConteudoEditar(event)">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-image text-gray-500" viewBox="0 0 16 16">
          <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
          <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z" />
        </svg>
        <span class="ml-2 text-gray-700 conteudo-txt-imagem-editar-escolher">Alterar Imagem</span>
      </button>
      <div class="border border-slate-300 p-4 relative flex flex-col gap-2 w-full bg-white rounded-md">
        <img src="<?php echo $this->renderImagem($linha['Conteudo']['url'], $linha['Conteudo']['id']); ?>" data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>" class="object-cover w-full h-full min-h-48 bloco-imagem-elemento" onerror="this.onerror=null; this.src='/img/sem-imagem.svg';">
      </div>
    </div>
    <div class="sticky bottom-0 py-4 px-2 w-full h-max flex flex-col lg:flex-row justify-between gap-4 bg-white rounded-lg">
      <div class="flex gap-4 bg-white">
        <label class="flex items-start gap-2 cursor-pointer">
          <input type="hidden" name="titulo_ocultar" value="0">
          <input type="checkbox" value="1" class="sr-only peer conteudo-editar-imagem-titulo-ocultar" name="titulo_ocultar" <?php echo $linha['Conteudo']['titulo_ocultar'] ? 'checked' : '' ?> autofocus>
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
        </label>
      </div>
      <div class="w-full flex flex-col sm:flex-row gap-1 justify-end sm:gap-2 bg-white div-botoes">
        <button type="button" class="js-dashboard-conteudo-remover <?php echo CLASSES_DASH_BUTTON_REMOVER_2; ?> remover" data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>" data-conteudo-url="<?php echo $linha['Conteudo']['url'] ?>" data-conteudo-tipo="<?php echo $linha['Conteudo']['tipo'] ?>">Remover</button>
        <button type="button" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?> cancelar" data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>" data-conteudo-tipo="<?php echo $linha['Conteudo']['tipo'] ?>" onclick="fecharModalEditar(event)">Cancelar</button>
        <button type="submit" class="w-full md-w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> gravar modal-conteudo-imagem-btn-enviar">Gravar</button>
      </div>
    </div>
  </form>
</div>