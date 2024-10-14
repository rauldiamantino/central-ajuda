<form method="POST" action="/d/<?php echo $this->usuarioLogado['empresaId'] ?>/categoria/<?php echo $categoria['Categoria']['id'] ?>" class="border border-slate-300 w-full min-w-96 flex flex-col gap-4 p-4 rounded-lg shadow bg-white">
  <input type="hidden" name="_method" value="PUT">
  <div class="w-full flex gap-4">
    <div>
      <label class="flex flex-col items-start gap-1 cursor-pointer">
        <span class="block text-sm font-medium text-gray-700">Status</span>
        <input type="hidden" name="ativo" value="0">
        <input type="checkbox" value="1" class="sr-only peer" <?php echo $categoria['Categoria']['ativo'] ? 'checked' : '' ?> name="ativo">
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
      </label>
    </div>
    <div class="w-full flex flex-col gap-4">
      <div class="w-full">
        <label for="categoria-editar-titulo" class="block text-sm font-medium text-gray-700">Título</label>
        <input type="text" id="categoria-editar-titulo" name="nome" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $categoria['Categoria']['nome']; ?>" required autofocus>
      </div>
      <div class="w-full">
        <label for="descricao" class="block text-sm font-medium text-gray-700">Breve descrição</label>
        <input name="descricao" id="descricao" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $categoria['Categoria']['descricao']; ?>"></input>
      </div>
    </div>
  </div>
  <div class="w-full flex justify-between">
    <div class="flex gap-2">
      <a href="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/categorias" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
      <button type="submit" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?>">Gravar</button>
    </div>
  </div>
</form>