<form method="POST" action="/empresa/<?php echo $empresa['Empresa.id'] ?>" class="border border-slate-200 w-full min-w-96 flex flex-col gap-4 p-4 rounded-lg shadow form-editar-empresa" data-empresa-id="<?php echo $empresa['Empresa.id'] ?>" data-imagem-atual="<?php echo $empresa['Empresa.logo']; ?>">
  <input type="hidden" name="_method" value="PUT">
  <div class="w-full flex flex-col gap-4">
    <div class="w-full flex justify-start gap-4">
      <div class="w-max flex gap-4">
        <label class="flex flex-col items-start gap-1 cursor-pointer">
          <span class="block text-sm font-medium text-gray-700">Status</span>
          <input type="checkbox" value="<?php echo $empresa['Empresa.ativo']; ?>" class="sr-only peer" <?php echo $empresa['Empresa.ativo'] ? 'checked' : '' ?> disabled>
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        </label>
      </div>
      <div class="w-full">
        <label for="empresa-editar-nome" class="w-full block text-sm font-medium text-gray-700">Nome</label>
        <input type="text" id="empresa-editar-nome" name="nome" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?php echo $empresa['Empresa.nome']; ?>">
      </div>
    </div>
    <div class="w-full">
      <label for="empresa-editar-cnpj" class="block text-sm font-medium text-gray-700">CNPJ</label>
      <input type="text" id="empresa-editar-cnpj" name="cnpj" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?php echo $empresa['Empresa.cnpj']; ?>">
    </div>
    <div class="w-full">
      <label for="empresa-editar-subdominio" class="block text-sm font-medium text-gray-700">Subdomínio</label>
      <input type="text" id="empresa-editar-subdominio" name="subdominio" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?php echo $empresa['Empresa.subdominio']; ?>">
    </div>
    <div class="w-full">
      <label for="empresa-editar-telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
      <input type="text" id="empresa-editar-telefone" name="telefone" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" placeholder="00 00000 0000" value="<?php echo $empresa['Empresa.telefone']; ?>">
    </div>
    <div class="w-full items-start flex flex-col gap-2">
      <input type="hidden" name="logo" value="" class="url-imagem">
      <input type="file" accept="image/*" id="empresa-editar-imagem" class="hidden empresa-editar-imagem-escolher">
      <button type="button" for="empresa-editar-imagem" class="w-full flex items-center justify-center cursor-pointer border border-gray-300 bg-gray-50 p-4 rounded-lg hover:bg-gray-100 empresa-btn-imagem-editar-escolher">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-image text-gray-500" viewBox="0 0 16 16">
          <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
          <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z" />
        </svg>
        <span class="ml-2 text-gray-700 empresa-txt-imagem-editar-escolher">Alterar Imagem</span>
      </button>
      <div class="relative flex flex-col gap-2 w-full max-h-48 opacity-50">
        <?php if ($empresa['Empresa.logo']) { ?>
          <img src="<?php echo $empresa['Empresa.logo']; ?>" class="object-cover w-full h-full empresa-alterar-logo">
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="flex gap-2">
    <a href="/dashboard" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-xs text-gray-700 rounded-lg">Cancelar</a>
    <button type="submit" class="flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg btn-gravar-empresa">Gravar</button>
  </div>
</form>