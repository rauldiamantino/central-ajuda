<form method="POST" action="/empresa/<?php echo $empresa['Empresa.id'] ?>" class="border border-slate-200 w-full min-w-96 flex flex-col gap-4 p-4 rounded-lg shadow">
  <input type="hidden" name="_method" value="PUT">
  <div class="w-full flex flex-col gap-4">
    <div class="flex gap-10">
      <div class="w-full flex gap-4">
        <label class="flex flex-col items-start gap-1 cursor-pointer">
          <span class="block text-sm font-medium text-gray-700">Status</span>
          <input type="checkbox" value="<?php echo $empresa['Empresa.ativo']; ?>" class="sr-only peer" <?php echo $empresa['Empresa.ativo'] ? 'checked' : '' ?> disabled>
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        </label>
      </div>
    </div>
    <div class="w-full">
      <label for="empresa-editar-nome" class="block text-sm font-medium text-gray-700">Nome</label>
      <input type="text" id="empresa-editar-nome" name="nome" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?php echo $empresa['Empresa.nome']; ?>">
    </div>
    <div class="w-full">
      <label for="empresa-editar-cnpj" class="block text-sm font-medium text-gray-700">CNPJ</label>
      <input type="text" id="empresa-editar-cnpj" name="cnpj" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?php echo $empresa['Empresa.cnpj']; ?>">
    </div>
    <div class="w-full">
      <label for="empresa-editar-subdominio" class="block text-sm font-medium text-gray-700">Subdomínio</label>
      <input type="text" id="empresa-editar-subdominio" name="subdominio" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?php echo $empresa['Empresa.subdominio']; ?>">
    </div>
  </div>
  <div class="flex gap-4">
    <a href="/dashboard" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-xs text-gray-700 rounded-lg">Cancelar</a>
    <button type="submit" class="flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg">Gravar</button>
  </div>
</form>