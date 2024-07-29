<div class="relative w-full min-h-full flex flex-col bg-white p-4">
  <h2 class="text-2xl font-semibold mb-4">
    Editar artigo
    <span class="text-gray-400 font-light italic">
      #<?php echo $artigo['Artigo.id']; ?>
    </span>
  </h2>
  <div class="mb-10 text-xs font-light">
    <div>Criado por <span class="font-semibold"><?php echo $artigo['Usuario.nome']; ?></span> em <?php echo traduzirDataPtBr($artigo['Artigo.criado']); ?></div>
    <div>Última atualização: <?php echo traduzirDataPtBr($artigo['Artigo.modificado']); ?></div>
  </div>
  
  <div class="w-full flex gap-10 divide-x">
    <form method="POST" action="/artigo/<?php echo $artigo['Artigo.id'] ?>" class="border border-slate-200 w-1/2 min-w-96 flex flex-col gap-4 p-4 rounded-lg shadow">
      <input type="hidden" name="_method" value="PUT">
      <div class="w-full flex gap-4">
        <div>
          <label class="flex flex-col items-start gap-1 cursor-pointer">
            <span class="block text-sm font-medium text-gray-700">Status</span>
            <input type="hidden" name="ativo" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $artigo['Artigo.ativo'] ? 'checked' : '' ?> name="ativo">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          </label>
        </div>
        <div class="w-full">
          <label for="artigo-editar-titulo" class="block text-sm font-medium text-gray-700">Título</label>
          <input type="text" id="artigo-editar-titulo" name="titulo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?php echo $artigo['Artigo.titulo']; ?>" required>
        </div>
      </div>
      <div class="mb-4">
        <label for="artigo-editar-categoria" class="block text-sm font-medium text-gray-700">Categoria</label>
        <select id="artigo-editar-categoria" name="categoria_id" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required>
          <?php foreach ($categorias as $chave => $linha) : ?>
            <option value="<?php echo $linha['Categoria.id']; ?>" <?php echo $linha['Categoria.id'] == $artigo['Artigo.categoria_id'] ? 'selected' : ''; ?>>
              <?php echo $linha['Categoria.nome']; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="flex gap-4">
        <a href="/dashboard/artigos" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-xs text-gray-700 rounded-lg">Cancelar</a>
        <button type="submit" class="flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg">Gravar</button>
      </div>
    </form>
  </div>
</div>