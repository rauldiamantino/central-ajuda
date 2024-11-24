<dialog class="border border-slate-300 w-full md:w-[500px] rounded-md shadow ease-in-out menu-adicionar-artigo">
  <form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/artigo'); ?>" class="border border-slate-200 w-full flex flex-col gap-4 p-4 rounded-lg shadow bg-white" onsubmit="evitarDuploClique(event)">
    <input type="hidden" name="referer" value="<?php echo urlencode($botaoVoltar) ?>">
    <input type="hidden" name="usuario_id" value="<?php echo $this->usuarioLogado['id'] ?>">
    <?php if (isset($ordem['prox'])) { ?>
      <input type="hidden" name="ordem" value="<?php echo $ordem['prox'] ?>">
    <?php } ?>
    <div class="w-full flex gap-4">
      <div>
        <label class="flex flex-col items-start gap-1 cursor-pointer">
          <span class="block text-sm font-medium text-gray-700">Status</span>
          <input type="hidden" name="ativo" value="0">
          <input type="checkbox" value="1" class="sr-only peer" name="ativo" checked>
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        </label>
      </div>
      <div class="w-full">
        <label for="artigo-editar-titulo" class="">Título</label>
        <input type="text" id="artigo-editar-titulo" name="titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="" required autofocus>
      </div>
    </div>
    <div class="mb-4">
      <label for="artigo-editar-categoria" class="">Categoria</label>
      <select id="artigo-editar-categoria" name="categoria_id" class="<?php echo CLASSES_DASH_INPUT; ?>">
        <option value="0">Sem categoria</option>
        <?php foreach ($categorias as $chave => $linha) : ?>
          <option value="<?php echo $linha['Categoria']['id']; ?>">
            <?php echo $linha['Categoria']['nome']; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="flex gap-2">
      <button type="button" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>" onclick="document.querySelector('.menu-adicionar-artigo').close()">Voltar</button>
      <button type="submit" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?>">Gravar</button>
    </div>
  </form>
</dialog>