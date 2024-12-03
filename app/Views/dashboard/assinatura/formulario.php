<?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
  <form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/assinatura/editar/' . $assinatura['Assinatura']['id']); ?>" class="border-t border-slate-300 w-full h-full flex flex-col gap-4 form-editar-assinatura" id="form-editar-assinatura" data-assinatura-id="<?php echo $assinatura['Assinatura']['empresa_id'] ?>">
    <input type="hidden" name="_method" value="PUT">
    <div class="w-full flex flex-col divide-y">
        <?php // Status da assinatura ?>
        <div class="w-full lg:w-[700px] py-8 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
          <div class="flex flex-col text-sm font-medium text-gray-700">
            <span class="block text-sm font-medium text-gray-700">Status assinatura</span>
            <span class="font-extralight">Campo restrito somente para o suporte. Ao desativá-lo, a página pública é desativada e apenas as telas de início e empresa estarão liberadas</span>
          </div>
          <label class="w-max flex flex-col items-start gap-1 cursor-pointer">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" name="status" value="1" class="sr-only peer" <?php echo $assinatura['Assinatura']['status'] ? 'checked' : '' ?> <?php echo $this->usuarioLogado['padrao'] != USUARIO_SUPORTE ? 'disabled' : '' ?>>
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          </label>
        </div>

        <?php // Assinatura ID Asaas ?>
        <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
          <div class="flex flex-col text-sm font-medium text-gray-700">
            <span class="block text-sm font-medium text-gray-700">ID da assinatura</span>
            <span class="font-extralight">Campo restrito somente para o suporte.</span>
          </div>
          <input type="text" id="assinatura-asaas-id" name="asaas_id" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $assinatura['Assinatura']['asaas_id']; ?>">
        </div>

        <?php // Assinatura ciclo ?>
        <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
          <div class="flex flex-col text-sm font-medium text-gray-700">
            <span class="block text-sm font-medium text-gray-700">Ciclo da assinatura</span>
            <span class="font-extralight">Campo restrito somente para o suporte.</span>
          </div>
          <input type="text" id="assinatura-ciclo" name="ciclo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $assinatura['Assinatura']['ciclo']; ?>">
        </div>

        <?php // Assinatura valor ?>
        <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
          <div class="flex flex-col text-sm font-medium text-gray-700">
            <span class="block text-sm font-medium text-gray-700">Valor da assinatura</span>
            <span class="font-extralight">Campo restrito somente para o suporte.</span>
          </div>
          <input type="text" id="assinatura-valor" name="valor" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $assinatura['Assinatura']['valor'] ? str_replace('.', ',', $assinatura['Assinatura']['valor']) : $assinatura['Assinatura']['valor']; ?>">
        </div>

        <?php // Prazo do teste grátis ?>
        <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
          <div class="flex flex-col text-sm font-medium text-gray-700">
            <span class="block text-sm font-medium text-gray-700">Prazo do teste grátis</span>
            <span class="font-extralight">Campo restrito somente para o suporte.</span>
          </div>
          <input type="datetime-local" id="assinatura-gratis-prazo" name="gratis_prazo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $assinatura['Assinatura']['gratis_prazo']; ?>">
        </div>

        <?php // Espaço de armazenamento ?>
        <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
          <div class="flex flex-col text-sm font-medium text-gray-700">
            <span class="block text-sm font-medium text-gray-700">Espaço de armazenamento</span>
            <span class="font-extralight">Campo restrito somente para o suporte. Exemplo: 1024(1GB), 2048(2GB), 4096(4GB), etc.</span>
          </div>
          <input type="text" id="assinatura-espaco" name="espaco" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $assinatura['Assinatura']['espaco']; ?>">
        </div>
    </div>
  </form>
<?php } ?>