<form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/empresa/editar/' . $empresa['Empresa']['id']); ?>" class="border-t border-slate-300 w-full h-full flex flex-col gap-4 form-editar-empresa" data-empresa-id="<?php echo $empresa['Empresa']['id'] ?>" data-imagem-atual="<?php echo $empresa['Empresa']['logo']; ?>" data-favicon-atual="<?php echo $empresa['Empresa']['favicon']; ?>">
  <input type="hidden" name="_method" value="PUT">

  <div class="w-full flex flex-col divide-y">
    <?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
      <?php // Status da empresa ?>
      <div class="w-full lg:w-[700px] py-8 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
        <span class="block text-sm font-medium text-gray-700">Status</span>
        <label class="w-250px flex flex-col items-start gap-1 cursor-pointer">
          <input type="hidden" name="ativo" value="0">
          <input type="checkbox" name="ativo" value="1" class="sr-only peer" <?php echo $empresa['Empresa']['ativo'] ? 'checked' : '' ?> <?php echo $this->usuarioLogado['padrao'] != USUARIO_SUPORTE ? 'disabled' : '' ?>>
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        </label>
      </div>

      <?php // Assinatura Stripe ?>
      <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
        <label for="empresa-editar-assinatura-id" class="w-full flex flex-col text-sm font-medium text-gray-700">
          <span>ID da assinatura Stripe</span>
          <?php if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
            <form action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/validar_assinatura'); ?>" method="GET" class="w-full">
              <input type="hidden" name="assinatura_id" value="<?php echo $empresa['Empresa']['assinatura_id']; ?>">
              <input type="hidden" name="sessao_stripe_id" value="<?php echo $empresa['Empresa']['sessao_stripe_id']; ?>">
              <button type="submit" class="w-max font-extralight hover:underline">
                Reprocessar
              </button>
            </form>
          <?php } ?>
        </label>
        <input type="text" id="empresa-editar-assinatura-id" name="assinatura_id" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $empresa['Empresa']['assinatura_id']; ?>">
      </div>
    <?php } ?>

    <?php // Nome da empresa ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="empresa-editar-assinatura-id" class="w-full block text-sm font-medium text-gray-700">Nome da empresa <span class="font-extralight">(opcional)</span></label>
      <input type="text" id="empresa-editar-nome" name="nome" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $empresa['Empresa']['nome']; ?>">
    </div>

    <?php // CNPJ ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="empresa-editar-cnpj" class="block text-sm font-medium text-gray-700">CNPJ <span class="font-extralight">(opcional)</span></label>
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
      <label for="empresa-editar-imagem" class="flex flex-col text-sm font-medium text-gray-700">
        <span>Logo</span>
        <span class="font-extralight">Envie uma imagem para representar a sua empresa. O arquivo deve ter até 2MB e estar no formato .jpg ou .png. Tamanho ideal: 200px de largura por 70px de altura.</span>
      </label>
      <button type="button" for="empresa-editar-imagem" class="w-full h-24 flex items-center justify-center cursor-pointer border border-gray-300 bg-gray-50 p-4 rounded-lg hover:bg-gray-100 empresa-btn-imagem-editar-escolher">
        <div class="h-full">
          <img src="<?php echo $empresa['Empresa']['logo']; ?>" class="object-contain w-full h-full empresa-alterar-logo <?php echo $empresa['Empresa']['logo'] ? '' : 'hidden' ?>">
        </div>
        <span class="ml-2 text-gray-700 h-max w-max empresa-txt-imagem-editar-escolher"><?php echo $empresa['Empresa']['logo'] ? '' : 'Adicionar'; ?></span>
        <h3 class="hidden text-left text-sm text-red-800 erro-empresa-imagem"></h3>
      </button>
    </div>

    <?php // Favicon ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <input type="hidden" name="favicon" value="" class="url-favicon">
      <input type="file" accept="image/*" id="empresa-editar-favicon" class="hidden empresa-editar-favicon-escolher">
      <label for="empresa-editar-imagem" class="flex flex-col text-sm font-medium text-gray-700">
        <span>Favicon</span>
        <span class="font-extralight">Adicione um ícone personalizado para a barra de navegação. Deve ser uma imagem .jpg ou .png com 48px de largura por 48px de altura.</span>
      </label>
      <button type="button" for="empresa-editar-favicon" class="w-full h-24 flex items-center justify-center cursor-pointer border border-gray-300 bg-gray-50 p-4 rounded-lg hover:bg-gray-100 empresa-btn-favicon-editar-escolher">
        <div class="h-full">
          <img src="<?php echo $empresa['Empresa']['favicon'] ? $empresa['Empresa']['favicon'] : ''; ?>" class="object-contain w-full h-full empresa-alterar-favicon <?php echo $empresa['Empresa']['favicon'] ? '' : 'hidden' ?>">
        </div>
        <span class="ml-2 text-gray-700 h-max w-max empresa-txt-favicon-editar-escolher"><?php echo $empresa['Empresa']['favicon'] ? '' : 'Adicionar'; ?></span>
        <h3 class="hidden text-left text-sm text-red-800 erro-empresa-favicon"></h3>
      </button>
    </div>
  </div>

  <?php // Não apagar ?>
  <button type="submit" class="hidden btn-gravar-empresa"></button>
</form>