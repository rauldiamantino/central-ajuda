<dialog class="border border-slate-300 w-full md:w-[700px] rounded-md shadow menu-adicionar-conteudos">
  <form method="POST" action="<?php echo '/d/artigo/' . $artigo['Artigo']['id']; ?>" class="w-full flex flex-col gap-4 p-4 rounded-lg bg-white" onsubmit="evitarDuploClique(event)">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="referer" value="<?php echo urlencode($botaoVoltar) ?>">
    <div class="w-full flex gap-4">
      <div>
        <label class="flex flex-col items-start gap-1 cursor-pointer">
          <span class="block text-sm font-medium text-gray-700">Status</span>
          <input type="hidden" name="ativo" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $artigo['Artigo']['ativo'] ? 'checked' : '' ?> name="ativo">
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        </label>
      </div>
      <div class="w-full">
        <label for="artigo-editar-titulo" class="block text-sm font-medium text-gray-700">Título</label>
        <input type="text" id="artigo-editar-titulo" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $artigo['Artigo']['titulo']; ?>">
      </div>
    </div>
    <div>
      <label for="artigo-editar-categoria" class="block text-sm font-medium text-gray-700">Categoria</label>
      <select id="artigo-editar-categoria" name="categoria_id" class="<?php echo CLASSES_DASH_INPUT; ?>" autofocus>
        <option value="0">Sem categoria</option>
        <?php foreach ($categorias as $chave => $linha) : ?>
          <option value="<?php echo $linha['Categoria']['id']; ?>" <?php echo $linha['Categoria']['id'] == $artigo['Artigo']['categoria_id'] ? 'selected' : ''; ?>>
            <?php echo $linha['Categoria']['nome']; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-4 p-4 bg-gray-100 rounded-lg">
      <span class="text-gray-700">Meta tags (SEO)</span>
      <div class="mt-4 w-full">
        <label for="meta_titulo" class="block text-sm font-medium text-gray-700">Title</label>
        <input name="meta_titulo" id="meta_titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $artigo['Artigo']['meta_titulo']; ?>"></input>
      </div>
      <div class="mt-4 w-full">
        <label for="meta_descricao" class="block text-sm font-medium text-gray-700">Description</label>
        <input name="meta_descricao" id="meta_descricao" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $artigo['Artigo']['meta_descricao']; ?>"></input>
      </div>
    </div>
    <div class="w-full flex justify-start gap-2">
      <button type="button" class="w-full md:w-max <?php echo CLASSES_DASH_BUTTON_VOLTAR; ?> botao-fechar-menu-adicionar-conteudos">Cancelar</button>
      <button type="submit" class="w-full md:w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?>">Gravar</button>
    </div>
  </form>
</dialog>