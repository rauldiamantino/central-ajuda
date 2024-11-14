<form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/empresa/editar/' . $empresa['Empresa']['id']); ?>" class="border-t border-slate-300 w-full h-full flex flex-col gap-4 form-editar-empresa" data-empresa-id="<?php echo $empresa['Empresa']['id'] ?>" data-imagem-atual="<?php echo $empresa['Empresa']['logo']; ?>" data-favicon-atual="<?php echo $empresa['Empresa']['favicon']; ?>">
  <input type="hidden" name="_method" value="PUT">
  <div class="w-full flex flex-col divide-y">
    <?php // Nome da empresa ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="empresa-editar-assinatura-id" class="w-full block text-sm font-medium text-gray-700">Nome da empresa <span class="text-red-800 font-extralight">(obrigatório)</span></label>
      <input type="text" id="empresa-editar-nome" name="nome" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $empresa['Empresa']['nome']; ?>">
    </div>

    <?php // CNPJ ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="empresa-editar-cnpj" class="block text-sm font-medium text-gray-700">CNPJ <span class="text-red-800 font-extralight">(obrigatório)</span></label>
      <input type="text" id="empresa-editar-cnpj" name="cnpj" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $empresa['Empresa']['cnpj']; ?>">
    </div>

    <?php // Telefone ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="empresa-editar-telefone" class="block text-sm font-medium text-gray-700">Celular com DDD <span class="font-extralight">(opcional)</span></label>
      <input type="text" id="empresa-editar-telefone" name="telefone" class="<?php echo CLASSES_DASH_INPUT; ?>" placeholder="00 00000 0000" value="<?php echo $empresa['Empresa']['telefone']; ?>">
    </div>

    <?php // Logo ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <input type="hidden" name="logo" value="" class="url-imagem">
      <input type="file" accept="image/*" id="empresa-editar-imagem" class="hidden empresa-editar-imagem-escolher">
      <div class="flex flex-col text-sm font-medium text-gray-700">
        <span>Logo</span>
        <span class="font-extralight">Envie uma imagem para representar a sua empresa. O arquivo deve ter até 2MB e estar no formato .jpg ou .png. Tamanho ideal: 200px de largura por 70px de altura.</span>
      </div>
      <button type="button" for="empresa-editar-imagem" class="w-full h-24 flex items-center justify-center <?php echo CLASSES_DASH_INPUT; ?> empresa-btn-imagem-editar-escolher">
        <div class="h-full">
          <img src="<?php echo $empresa['Empresa']['logo']; ?>" class="w-full h-full empresa-alterar-logo <?php echo $empresa['Empresa']['logo'] ? '' : 'hidden' ?>">
        </div>
        <span class="ml-2 text-gray-700 h-max w-max empresa-txt-imagem-editar-escolher"><?php echo $empresa['Empresa']['logo'] ? '' : 'Adicionar'; ?></span>
        <h3 class="hidden font-light text-left text-sm text-red-800 erro-empresa-imagem"></h3>
      </button>
    </div>

    <?php // Favicon ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <input type="hidden" name="favicon" value="" class="url-favicon">
      <input type="file" accept="image/*" id="empresa-editar-favicon" class="hidden empresa-editar-favicon-escolher">
      <div class="flex flex-col text-sm font-medium text-gray-700">
        <span>Favicon</span>
        <span class="font-extralight">Adicione um ícone personalizado para a barra de navegação. Deve ser uma imagem .jpg ou .png com 48px de largura por 48px de altura.</span>
      </div>
      <button type="button" for="empresa-editar-favicon" class="w-full h-24 flex items-center justify-center <?php echo CLASSES_DASH_INPUT; ?> empresa-btn-favicon-editar-escolher">
        <div class="h-full">
          <img src="<?php echo $empresa['Empresa']['favicon'] ? $empresa['Empresa']['favicon'] : ''; ?>" class="w-full h-full empresa-alterar-favicon <?php echo $empresa['Empresa']['favicon'] ? '' : 'hidden' ?>">
        </div>
        <span class="ml-2 text-gray-700 h-max w-max empresa-txt-favicon-editar-escolher"><?php echo $empresa['Empresa']['favicon'] ? '' : 'Adicionar'; ?></span>
        <h3 class="hidden font-light text-left text-sm text-red-800 erro-empresa-favicon"></h3>
      </button>
    </div>

    <?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
      <?php // Status da empresa ?>
      <div class="w-full lg:w-[700px] py-8 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
        <div class="flex flex-col text-sm font-medium text-gray-700">
          <span class="block text-sm font-medium text-gray-700">Status plataforma</span>
          <span class="font-extralight">Campo restrito somente para o suporte. Ao desativá-lo, todos os usuários perdem o acesso e a página pública é desativada</span>
        </div>
        <label class="w-max flex flex-col items-start gap-1 cursor-pointer">
          <input type="hidden" name="ativo" value="0">
          <input type="checkbox" name="ativo" value="1" class="sr-only peer" <?php echo $empresa['Empresa']['ativo'] ? 'checked' : '' ?> <?php echo $this->usuarioLogado['padrao'] != USUARIO_SUPORTE ? 'disabled' : '' ?>>
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        </label>
      </div>

      <?php // Status da assinatura ?>
      <div class="w-full lg:w-[700px] py-8 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
        <div class="flex flex-col text-sm font-medium text-gray-700">
          <span class="block text-sm font-medium text-gray-700">Status assinatura</span>
          <span class="font-extralight">Campo restrito somente para o suporte. Ao desativá-lo, apenas as telas de início e empresa estarão liberadas</span>
        </div>
        <label class="w-max flex flex-col items-start gap-1 cursor-pointer">
          <input type="hidden" name="assinatura_status" value="0">
          <input type="checkbox" name="assinatura_status" value="1" class="sr-only peer" <?php echo $empresa['Empresa']['assinatura_status'] ? 'checked' : '' ?> <?php echo $this->usuarioLogado['padrao'] != USUARIO_SUPORTE ? 'disabled' : '' ?>>
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        </label>
      </div>

      <?php // Assinatura ID Asaas ?>
      <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
        <div class="flex flex-col text-sm font-medium text-gray-700">
          <span class="block text-sm font-medium text-gray-700">ID da assinatura</span>
          <span class="font-extralight">Campo restrito somente para o suporte.</span>
        </div>
        <input type="text" id="empresa-assinatura-id" name="assinatura_id_asaas" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $empresa['Empresa']['assinatura_id_asaas']; ?>">
      </div>
    <?php } ?>
  </div>

  <?php // Não apagar ?>
  <button type="submit" class="hidden btn-gravar-empresa"></button>
</form>

<form action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/validar_assinatura'); ?>" method="GET" class="mt-5 w-max h-max flex justify-start items-center gap-2">
  <input type="hidden" name="assinatura_id" value="<?php echo $empresa['Empresa']['assinatura_id_asaas']; ?>">
  <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-cloud-arrow-down" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M7.646 10.854a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 9.293V5.5a.5.5 0 0 0-1 0v3.793L6.354 8.146a.5.5 0 1 0-.708.708z"/>
    <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383m.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
  </svg>
  <button type="submit" class="w-max text-sm hover:underline">Reprocessar assinatura</button>
</form>