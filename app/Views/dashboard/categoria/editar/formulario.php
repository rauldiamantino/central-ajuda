<form method="POST" action="/d/categoria/<?php echo $this->usuarioLogado['empresaId'] ?>/<?php echo $categoria['Categoria.id'] ?>" class="border border-slate-200 w-full min-w-96 flex flex-col gap-4 p-4 rounded-lg shadow">
  <input type="hidden" name="_method" value="PUT">
  <div class="w-full flex gap-4">
    <div>
      <label class="flex flex-col items-start gap-1 cursor-pointer">
        <span class="block text-sm font-medium text-gray-700">Status</span>
        <input type="hidden" name="ativo" value="0">
        <input type="checkbox" value="1" class="sr-only peer" <?php echo $categoria['Categoria.ativo'] ? 'checked' : '' ?> name="ativo">
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
      </label>
    </div>
    <div class="w-full flex flex-col gap-4">
      <div class="w-full">
        <label for="categoria-editar-titulo" class="block text-sm font-medium text-gray-700">Título</label>
        <input type="text" id="categoria-editar-titulo" name="nome" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?php echo $categoria['Categoria.nome']; ?>" required autofocus>
      </div>
      <div class="w-full">
        <textarea name="descricao" id="descricao" class="border border-gray-300 w-full p-2 h-56 rounded-lg"><?php echo $categoria['Categoria.descricao']; ?></textarea>
      </div>
    </div>
  </div>
  <div class="w-full flex justify-between">
    <div class="flex gap-2">
      <a href="/dashboard/categorias/<?php echo $this->usuarioLogado['empresaId'] ?>" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-6 hover:bg-slate-50 text-xs text-gray-700 rounded-lg">Voltar</a>
      <button type="submit" class="flex gap-2 items-center justify-center py-2 px-6 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg">Gravar</button>
    </div>
  </div>
</form>