<div class="w-full h-full flex flex-col p-4">
  <h2 class="flex gap-1 text-2xl font-semibold mb-4">
    Editar
    <span class="flex items-center gap-2 text-gray-400 font-light italic text-sm">
      #<?php echo $empresa['Empresa']['id']; ?>
    </span>
  </h2>
  <div class="w-full flex flex-col md:flex-row gap-4">
    <div class="w-full flex flex-col gap-2">
      <?php require_once 'formulario.php' ?>
      <?php if ($empresa['Empresa']['subdominio'] and isset($_SERVER['SERVER_NAME']) and $_SERVER['SERVER_NAME']) { ?>
        <div class="p-4 flex flex-col flex-wrap md:flex-row md:gap-2 justify-center items-center w-full text-gray-900 text-center text-sm">
          Divulgue o endereço:
          <a href="<?php echo subdominioDominio($this->usuarioLogado['subdominio']); ?>" target="_blank" class="text-xl text-red-700"><?php echo subdominioDominio($this->usuarioLogado['subdominio'], false); ?></a>
        </div>
      <?php } ?>
    </div>
    <div class="mb-10 md:mb-0 md:block w-full empresa-bloco-assinatura" data-empresa-assinatura="<?php echo $empresa['Empresa']['ativo'] == ATIVO ? $empresa['Empresa']['assinatura_id'] : ''; ?>">
      <div class="relative border border-slate-300 w-full min-w-96 flex flex-col p-4 rounded-lg shadow bg-white">
        <div id="efeito-loader-assinatura" class="hidden loader"></div>
        <div class="px-2 w-full flex gap-4 justify-between items-center">
          <h2 class="font-bold pb-2">Assinatura</h2>

          <?php if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
            <form action="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/validar_assinatura" method="GET" class="flex items-center gap-2">
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
        <div class="w-full p-2 flex flex-col sm:flex-row items-start sm:items-center md:gap-4">
          <span class="w-full sm:w-2/12 text-xs rounded"><?php echo strtoupper('ID Assinatura') ?></span>
          <div class="<?php echo CLASSES_DASH_INPUT_BLOCK; ?> sm:w-10/12">
            <span class="text-sm empresa-assinatura-id">** vazio **</span>
          </div>
        </div>

        <div class="w-full p-2 flex flex-col sm:flex-row items-start sm:items-center md:gap-4">
          <span class="w-full sm:w-2/12 text-xs rounded"><?php echo strtoupper('ID da Primeira sessão Stripe') ?></span>
          <div class="<?php echo CLASSES_DASH_INPUT_BLOCK; ?> sm:w-10/12">
            <span class="text-sm empresa-assinatura-sessao-id">** vazio **</span>
          </div>
        </div>

        <div class="w-full p-2 flex flex-col sm:flex-row items-start sm:items-center md:gap-4">
          <span class="w-full sm:w-2/12 text-xs rounded"><?php echo strtoupper('Status') ?></span>
          <div class="<?php echo CLASSES_DASH_INPUT_BLOCK; ?> sm:w-10/12">
            <span class="text-sm empresa-assinatura-status">** vazio **</span>
          </div>
        </div>

        <div class="w-full p-2 flex flex-col sm:flex-row items-start sm:items-center md:gap-4">
          <span class="w-full sm:w-2/12 text-xs rounded"><?php echo strtoupper('Data de início') ?></span>
          <div class="<?php echo CLASSES_DASH_INPUT_BLOCK; ?> sm:w-10/12">
            <span class="text-sm empresa-assinatura-data-inicio">** vazio **</span>
          </div>
        </div>

        <div class="w-full p-2 flex flex-col sm:flex-row items-start sm:items-center md:gap-4">
          <span class="w-full sm:w-2/12 text-xs rounded"><?php echo strtoupper('Data de início do período atual') ?></span>
          <div class="<?php echo CLASSES_DASH_INPUT_BLOCK; ?> sm:w-10/12">
            <span class="text-sm empresa-assinatura-atual-inicio">** vazio **</span>
          </div>
        </div>

        <div class="w-full p-2 flex flex-col sm:flex-row items-start sm:items-center md:gap-4">
          <span class="w-full sm:w-2/12 text-xs rounded"><?php echo strtoupper('Data de fim do período atual') ?></span>
          <div class="<?php echo CLASSES_DASH_INPUT_BLOCK; ?> sm:w-10/12">
            <span class="text-sm empresa-assinatura-atual-fim">** vazio **</span>
          </div>
        </div>

        <div class="w-full p-2 flex flex-col sm:flex-row items-start sm:items-center md:gap-4">
          <span class="w-full sm:w-2/12 text-xs rounded"><?php echo strtoupper('ID Cliente') ?></span>
          <div class="<?php echo CLASSES_DASH_INPUT_BLOCK; ?> sm:w-10/12">
            <span class="text-sm empresa-assinatura-cliente-id">** vazio **</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>