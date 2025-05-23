<dialog class="border border-slate-300 w-full md:w-[700px] rounded-md shadow menu-editar-categoria">
  <form method="POST" action="<?php echo '/d/categoria/' . $categoria['Categoria']['id']; ?>" class="w-full flex flex-col gap-4 p-4 rounded-lg shadow bg-white" onsubmit="evitarDuploClique(event)">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="referer" value="<?php echo $botaoVoltar; ?>">
    <div class="w-full flex gap-4">
      <div>
        <label class="flex flex-col items-start gap-1 cursor-pointer">
          <span class="block text-sm font-medium text-gray-700">Status</span>
          <input type="hidden" name="ativo" value="0" autofocus>
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $categoria['Categoria']['ativo'] ? 'checked' : '' ?> name="ativo">
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        </label>
      </div>
      <div class="w-full flex flex-col gap-4">
        <div class="w-full">
          <label for="categoria-editar-titulo" class="block text-sm font-medium text-gray-700">Título</label>
          <input type="text" id="categoria-editar-titulo" name="nome" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $categoria['Categoria']['nome']; ?>">
        </div>
      </div>
    </div>
    <div class="w-full flex flex-col gap-4">
      <div class="w-full">
        <label for="descricao" class="block text-sm font-medium text-gray-700">Breve descrição</label>
        <input name="descricao" id="descricao" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $categoria['Categoria']['descricao']; ?>"></input>
      </div>
      <div class="p-4 bg-gray-100 rounded-lg">
        <span class="text-gray-700">Meta tags (SEO)</span>
        <div class="mt-4 w-full">
          <label for="meta_titulo" class="block text-sm font-medium text-gray-700">Title</label>
          <input name="meta_titulo" id="meta_titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $categoria['Categoria']['meta_titulo']; ?>"></input>
        </div>
        <div class="mt-4 w-full">
          <label for="meta_descricao" class="block text-sm font-medium text-gray-700">Description</label>
          <input name="meta_descricao" id="meta_descricao" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $categoria['Categoria']['meta_descricao']; ?>"></input>
        </div>
      </div>
      <div class="flex flex-col">
        <span class="block text-sm font-medium text-gray-700">Ícone</span>
        <div class="border border-gray-200 p-2 w-full h-56 overflow-y-auto overflow-estilo-tabela rounded-lg">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
            <label class="p-2 border border-gray-100 w-full hover:bg-gray-50 duration-100 cursor-pointer rounded-lg">
              <div class="w-full grid grid-cols-[auto,1fr] items-center gap-2">
                <input type="radio" name="icone" value="" <?php echo empty($categoria['Categoria']['icone']) ? 'checked' : ''; ?> class="hidden peer">
                <div class="w-8 h-8 flex items-center justify-center border border-gray-300 peer-checked:border-blue-800 peer-checked:ring-2 peer-checked:ring-blue-800 rounded-lg">
                  <div class="w-6 text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                  </div>
                </div>
                <span class="text-xs">Nenhum</span>
              </div>
            </label>
            <?php foreach ($this->buscarIcones() as $chave => $linha): ?>
              <?php if ($this->iconeExiste($linha)) { ?>
                <label class="p-2 border border-gray-100 w-full hover:bg-gray-50 duration-100 cursor-pointer rounded-lg">
                  <div class="w-full grid grid-cols-[auto,1fr] items-center gap-2">
                    <input type="radio" name="icone" value="<?php echo $chave; ?>" <?php echo $categoria['Categoria']['icone'] == $chave ? 'checked' : ''; ?> class="hidden peer" >
                    <div class="w-8 h-8 flex items-center justify-center border border-gray-300 peer-checked:border-blue-800 peer-checked:ring-2 peer-checked:ring-blue-800 rounded-lg">
                      <?php echo $this->renderIcone($linha); ?>
                    </div>
                    <span class="text-xs"><?php echo $chave; ?></span>
                  </div>
                </label>
              <?php } ?>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="w-full flex justify-between">
      <div class="flex gap-2 div-botoes">
        <button type="button" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>" onclick="document.querySelector('.menu-editar-categoria').close()">Fechar</a>
        <button type="submit" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?>">Gravar</button>
      </div>
    </div>
  </form>
</dialog>