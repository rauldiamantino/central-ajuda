<div class="relative mb-10 md:mb-0 md:block w-full md:w-max md:min-w-72 h-max empresa-bloco-assinatura" data-empresa-assinatura="<?php echo $empresa['Empresa']['ativo'] == ATIVO ? $empresa['Empresa']['assinatura_id'] : ''; ?>">
  <div class="border border-slate-300 w-full h-full flex flex-col p-4 rounded-lg shadow">
    <div id="efeito-loader-assinatura" class="hidden loader"></div>
    <div class="pb-2 px-2 w-full flex gap-4 justify-between items-center">
      <h2 class="font-bold">
        Assinatura
      </h2>
    </div>
    <div class="p-2 flex flex-col gap-2">
      <div class="flex flex-col">
        <span class="w-full text-xs rounded">Status</span>
        <span class="text-sm font-bold empresa-assinatura-status"></span>
      </div>
      <div class="flex flex-col">
        <span class="w-full text-xs rounded">Início</span>
        <span class="text-sm font-bold empresa-assinatura-data-inicio"></span>
      </div>
      <div class="flex flex-col">
        <span class="w-full text-xs rounded">Período corrente</span>
        <span class="text-sm font-bold empresa-assinatura-periodo-corrente"></span>
      </div>
      <div class="flex flex-col">
        <span class="w-full text-xs rounded">Plano</span>
        <span class="text-sm font-bold empresa-assinatura-plano-nome"></span>
      </div>
      <div class="flex flex-col">
        <span class="w-full text-xs rounded">Valor</span>
        <span class="text-sm font-bold empresa-assinatura-plano-valor"></span>
      </div>
    </div>

    <?php if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
      <form action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/validar_assinatura'); ?>" method="GET" class="mt-4 w-full flex justify-center items-center gap-2">
        <input type="hidden" name="assinatura_id" value="<?php echo $empresa['Empresa']['assinatura_id']; ?>">
        <input type="hidden" name="sessao_stripe_id" value="<?php echo $empresa['Empresa']['sessao_stripe_id']; ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-cloud-arrow-down" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M7.646 10.854a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 9.293V5.5a.5.5 0 0 0-1 0v3.793L6.354 8.146a.5.5 0 1 0-.708.708z"/>
          <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383m.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
        </svg>
        <button type="submit" class="text-sm hover:underline">Reprocessar</button>
      </form>
    <?php } ?>
  </div>
</div>