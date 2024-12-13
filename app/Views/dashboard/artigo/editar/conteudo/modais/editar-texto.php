<?php // Editar texto ?>
<div class="p-3 w-full editor-container hidden container-pre-visualizar container-conteudo-texto-editar" data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>" data-conteudo-tipo="<?php echo $linha['Conteudo']['tipo'] ?>">
  <div class="font-extralight pb-10 flex flex-col">
    <div class="font-normal text-2xl">Edição de texto</div>
    <span>Não esqueca de clicar em gravar :)</span>
  </div>
  <form method="POST" action="<?php echo '/d/conteudo/' . $linha['Conteudo']['id']; ?>" class="flex flex-col items-end gap-2 editor-container__editor form-conteudo-texto-editar" enctype="multipart/form-data" onsubmit="evitarDuploClique(event)">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="referer" value="<?php echo urlencode($botaoVoltar) ?>">
    <input type="hidden" name="artigo_id" value="<?php echo $linha['Conteudo']['artigo_id'] ?>">
    <input type="hidden" name="tipo" value="1">

    <div class="w-full">
      <label for="conteudo-editar-texto-titulo" class="block text-sm font-medium text-gray-700">Título</label>
      <input type="text" id="conteudo-editar-texto-titulo-<?php echo $linha['Conteudo']['id'] ?>" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $linha['Conteudo']['titulo'] ?>">
    </div>
    <div class="w-full editor-container_classic-editor">
      <textarea name="conteudo" class="overflow-estilo-tabelaborder border-gray-300 w-full min-h-56 rounded-lg bg-slate-50 conteudo-texto-editar ckeditor" data-conteudo="<?php echo $linha['Conteudo']['conteudo'] ?>" data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>"></textarea>
    </div>
    <div class="sticky bottom-0 px-2 py-4 w-full h-max flex flex-col lg:flex-row justify-between gap-4 bg-white rounded-lg">
      <div class="w-full bg-white">
        <label class="flex items-start gap-2 cursor-pointer">
          <input type="hidden" name="titulo_ocultar" value="0">
          <input type="checkbox" value="1" class="sr-only peer conteudo-editar-texto-titulo-ocultar" name="titulo_ocultar" <?php echo $linha['Conteudo']['titulo_ocultar'] ? 'checked' : '' ?>>
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Ocultar título na publicação</span>
        </label>
      </div>
      <div class="w-full flex flex-col sm:flex-row gap-1 justify-end sm:gap-2 bg-white div-botoes">
        <button type="button" class="js-dashboard-conteudo-remover <?php echo CLASSES_DASH_BUTTON_REMOVER_2; ?>" data-conteudo-id="" data-conteudo-url="" data-conteudo-tipo="">Remover</button>
        <button type="button" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>" data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>" data-conteudo-tipo="<?php echo $linha['Conteudo']['tipo'] ?>" onclick="fecharModalEditar(event)">Cancelar</button>
        <button type="submit" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-conteudo-texto-btn-enviar">Gravar</button>
      </div>
    </div>
  </form>
</div>