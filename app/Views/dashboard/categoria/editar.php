<div class="relative w-full min-h-full flex flex-col bg-white p-4">
  <h2 class="text-2xl font-semibold mb-4">
    Editar categoria
    <span class="text-gray-400 font-light italic">
      #<?php echo $categoria['Categoria.id']; ?>
    </span>
  </h2>
  <div class="mb-10 text-xs font-light">
    <div>Criado em <?php echo traduzirDataPtBr($categoria['Categoria.criado']); ?></div>
    <div>Última atualização: <?php echo traduzirDataPtBr($categoria['Categoria.modificado']); ?></div>
  </div>

  <div class="w-full flex gap-4">
    <div class="w-full flex flex-col gap-10">
      <form method="POST" action="/categoria/<?php echo $categoria['Categoria.id'] ?>" class="border border-slate-200 w-full min-w-96 flex flex-col gap-4 p-4 rounded-lg shadow">
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
          <div class="w-full">
            <label for="categoria-editar-titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" id="categoria-editar-titulo" name="nome" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?php echo $categoria['Categoria.nome']; ?>" required>
          </div>
        </div>
        <div class="flex gap-4">
          <a href="/dashboard/categorias" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-xs text-gray-700 rounded-lg">Cancelar</a>
          <button type="submit" class="flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg">Gravar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php // Modal remover ?>
<dialog class="p-4 sm:w-[440px] rounded-lg shadow modal-conteudo-remover">
  <div class="w-full flex gap-4">
    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
      <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
    </div>
    <div class="text-left">
      <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-conteudo-remover-titulo">Remover conteudo</h3>
      <div class="mt-2">
        <p class="text-sm text-gray-500">
          Tem certeza que deseja remover este conteudo? Todos os dados serão permanentemente apagados. Esta ação não poderá ser revertida.
        </p>
      </div>
    </div>
  </div>
  <div class="mt-4 w-full flex flex-col sm:flex-row gap-3 font-semibold text-xs sm:justify-end justify-center">
    <button type="button" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-gray-700 rounded-lg modal-conteudo-btn-cancelar w-full">Cancelar</button>
    <button type="button" class="flex gap-2 items-center justify-center py-2 px-3 bg-red-800 hover:bg-red-600 text-white rounded-lg w-full modal-conteudo-btn-remover">Remover</button>
  </div>
</dialog>