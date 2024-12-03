<?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
  <?php // Status da empresa ?>
  <div class="w-max">
    <div class="w-full lg:w-[700px] py-8 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <span class="block text-sm font-medium text-gray-700">Status plataforma</span>
      <label class="w-max flex flex-col items-start gap-1 cursor-pointer">
        <input type="hidden" name="ativo" value="0">
        <input type="checkbox" name="ativo" value="1" class="sr-only peer" <?php echo $empresa['Empresa']['ativo'] ? 'checked' : '' ?> <?php echo $this->usuarioLogado['padrao'] != USUARIO_SUPORTE ? 'disabled' : '' ?>>
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
      </label>
    </div>

    <?php // Status da assinatura ?>
    <div class="w-full lg:w-[700px] py-8 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <span class="block text-sm font-medium text-gray-700">Status da assinatura</span>
      <label class="w-max flex flex-col items-start gap-1 cursor-pointer">
        <input type="hidden" name="assinatura_status" value="0">
        <input type="checkbox" name="assinatura_status" value="1" class="sr-only peer" <?php echo $empresa['Empresa']['assinatura_status'] ? 'checked' : '' ?> <?php echo $this->usuarioLogado['padrao'] != USUARIO_SUPORTE ? 'disabled' : '' ?>>
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
      </label>
    </div>

    <?php // Assinatura ID Asaas ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="empresa-assinatura-id" class="block text-sm font-medium text-gray-700">ID da assinatura</label>
      <input type="text" id="empresa-assinatura-id" name="assinatura_id_asaas" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $empresa['Empresa']['assinatura_id_asaas']; ?>">
    </div>
  </div>
<?php } ?>