<div class="pt-10 border-t border-gray-300 w-full h-full flex flex-col gap-4 editar-plano">

  <?php if ($this->sessaoUsuario->buscar('teste-expirado-' . $this->empresaPadraoId) == true) { ?>
    <div class="p-10 w-full lg:w-[700px] flex flex-col bg-red-600/5 rounded-xl">
      <div class="w-full text-red-800 font-semibold flex gap-2">
        <div class="w-6">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
          </svg>
        </div>
        <span class="w-full">Ops! Seu acesso foi bloqueado</span>
      </div>
      <div class="mt-2 text-sm text-gray-700 font-light">
        Para desbloquear assine um plano abaixo.<br>
        <div class="mt-2 text-xs">
          Caso tenha alguma dúvida, entre em contato com o nosso suporte:<br>
          Email: suporte@360help.com.br<br>
          WhatsApp: (11) 93433-2319
        </div>
      </div>
    </div>
  <?php } ?>

  <?php if ($this->sessaoUsuario->buscar('pagamento-vencido-' . $this->empresaPadraoId) == true) { ?>
    <div class="p-10 w-full lg:w-[700px] flex flex-col bg-red-600/5 rounded-xl">
      <div class="w-full text-red-800 font-semibold flex gap-2">
        <div class="w-6">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
          </svg>
        </div>
        <span class="w-full">Ops! Seu acesso foi bloqueado</span>
      </div>
      <div class="mt-2 text-sm text-gray-700 font-light">
        Parece que seu pagamento não foi realizado.<br>
        <div class="mt-2 text-xs">
          Caso tenha alguma dúvida, entre em contato com o nosso suporte:<br>
          Email: suporte@360help.com.br<br>
          WhatsApp: (11) 93433-2319
        </div>
      </div>
    </div>
  <?php } ?>

  <div class="py-8">
    <h3 class="text-lg font-semibold">Plano</h3>
    <?php if ($assinaturaId and $cobrancas) { ?>
      <div class="text-sm text-gray-700">Gostaria de alterar o plano? <a href="https://api.whatsapp.com/send/?phone=5511934332319&text=Olá!%20Tudo%20bem?%20Gostaria%20de%20saber%20como%20posso%20alterar%20meu%20plano.%20Obrigado!&type=phone_number&app_absent=0" target="_blank" class="underline font-semibold">Fale com a gente! :)</a></div>
    <?php } elseif ($assinaturaId) { ?>
      <div class="text-sm text-gray-700">Ops! Algo deu errado. Por favor, entre em contato para atualizar sua assinatura.</div>
    <?php } else { ?>
      <div class="text-sm text-gray-700">Ajuste sua assinatura e mude de plano quando quiser!</div>
    <?php } ?>

    <?php // Plano ?>
    <div class="pt-5 w-full flex flex-col gap-5">
      <button <?php echo ($assinaturaId) ? 'disabled' : ''; ?> onclick="assinarPlano('mensal')" class="border border-gray-200 w-full lg:w-[700px] p-8 flex flex-col gap-2 md:flex-row justify-between md:items-center rounded-lg bg-white hover:bg-gray-100">
        <div class="text-left">
          <div class="flex flex-col md:flex-row gap-1 md:gap-2 items-start md:items-end">
            <span class="text-sm font-semibold">Mensal</span>
            <span class="text-sm font-light text-gray-500 line-through">De R$ 119,00/mês</span>
            <span class="text-sm text-green-700 font-normal">por R$ 99,00/mês</span>
          </div>
        </div>
        <span class="text-sm font-semibold text-blue-800"><?php echo $assinaturaId ? '' : 'Assinar'; ?></span>
      </button>
      <button <?php echo ($assinaturaId) ? 'disabled' : ''; ?> onclick="assinarPlano('anual')" class="border border-gray-200 w-full lg:w-[700px] p-8 flex flex-col gap-2 md:flex-row justify-between md:items-center rounded-lg bg-white hover:bg-gray-100">
        <div class="text-left">
          <div class="h-full flex flex-col md:flex-row gap-1 md:gap-2 items-start md:items-end">
            <span class="text-sm font-semibold">Anual</span>
            <span class="text-sm font-light text-gray-500 line-through">De R$ 99,00/mês</span>
            <span class="text-sm text-green-700 font-normal">por R$ 79,00/mês</span>
            <span class="text-sm font-light text-gray-500">cobrado anualmente (R$ 948,00/ano)</span>
          </div>
        </div>
        <span class="text-sm font-semibold text-blue-800"><?php echo $assinaturaId ? '' : 'Assinar'; ?></span>
      </button>
    </div>

    <?php // Armazenamento ?>
    <h3 class="mt-12 text-lg font-semibold">Armazenamento</h3>
    <div class="text-sm text-gray-700">Confira quanto espaço você já utilizou até agora</div>
    <div class="pt-5 w-full flex flex-col gap-5 armazenamento-geral">
      <div class="border border-gray-200 w-full lg:w-[700px] p-8 flex gap-2 justify-between md:items-center rounded-lg bg-white hover:bg-gray-100">
        <span class="flex items-center p-3 text-blue-800 bg-blue-100/75 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
          </svg>
        </span>
        <div class="w-full flex flex-col justify-center items-start">
          <span class="font-semibold text-gray-800">Espaço utilizado</span>
          <div class="w-full h-full flex flex-col justify-start">
            <div class="h-full flex mb-2 w-full">
              <div class="w-full bg-gray-200 rounded-full">
                <div class="max-w-full p-1 rounded-md barra-progresso transition-all duration-300"></div>
              </div>
            </div>
            <p class="h-full block text-xs text-gray-600 espaco-utilizado opacity-50 transition-all duration-300">Calculando...</p>
          </div>
        </div>
      </div>
    </div>

    <?php // Cobranças ?>
    <h3 class="mt-12 text-lg font-semibold">Histórico de cobranças</h3>
    <div class="mb-6 text-sm text-gray-700">Veja o que já foi cobrado e pago até agora.</div>
    <div class="border border-gray-300 w-full lg:w-[700px] min-h-max rounded-t-2xl rounded-b-lg shadow bg-white overflow-x-auto overflow-y-hidden">
      <div class="inline-block min-w-full align-middle">
        <table class="table-fixed min-w-full text-sm text-left text-gray-500">
          <thead class="text-xs font-light text-gray-500 uppercase">
            <colgroup>
              <col class="w-[100px]">
              <col class="w-[100px]">
              <col class="w-[100px]">
              <col class="w-[100px]">
              <col class="w-[50px]">
            </colgroup>
            <tr class="bg-gray-100 w-full border-b text-xs">
              <th class="py-4 px-6">Vencimento</th>
              <th class="py-4 px-4">Descrição</th>
              <th class="py-4 px-4">Status</th>
              <th class="py-4 px-4">Valor</th>
              <th class="py-4 px-4"></th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php $temCobranca = false ?>
            <?php foreach($cobrancas as $chave => $linha): ?>

              <?php
              if (! isset($linha['dueDate']) or empty($linha['dueDate'])) {
                continue;
              }

              if (! isset($linha['description']) or empty($linha['description'])) {
                continue;
              }

              if (! isset($linha['status']) or empty($linha['status'])) {
                continue;
              }

              if (! isset($linha['value']) or empty($linha['value'])) {
                continue;
              }

              if (! isset($linha['invoiceUrl']) or empty($linha['invoiceUrl'])) {
                continue;
              }

              $botao = '';
              $status = $linha['status'];
              $valor = number_format($linha['value'], 2, ',', '.');
              $vencimento = $linha['dueDate'];
              $vencimento = new DateTime($vencimento);
              $vencimento = $vencimento->format('d/m/Y');
              $pagamentoLink = $linha['invoiceUrl'];

              $temCobranca = true;

              if ($status == 'CONFIRMED') {
                $status = 'Pago';
                $classeStatus = 'bg-green-50 text-green-600';
              }
              elseif ($status == 'OVERDUE') {
                $botao = 'Pagar';
                $status = 'Vencido';
                $classeStatus = 'bg-red-50 text-red-600';
              }
              else {
                $botao = 'Pagar';
                $status = 'Pendente';
                $classeStatus = 'bg-orange-50 text-orange-600';
              }
              ?>

              <tr class="hover:bg-gray-100">
                <td class="py-6 px-6"><?php echo $vencimento ?></td>
                <td class="py-6 px-4 font-semibold"><?php echo $linha['description'] ?></td>
                <td class="py-6 px-4">
                  <div class="flex items-center gap-2">
                    <span class="px-3 py-1 text-xs rounded-full <?php echo $classeStatus; ?>"><?php echo $status ?></span>
                  </div>
                </td>
                <td class="py-6 px-4 text-green-700 whitespace-nowrap">R$ <?php echo $valor ?></td>
                <td class="py-6 px-4 font-semibold text-blue-800">
                  <a href="<?php echo $pagamentoLink; ?>" target="_blank" class="font-semibold hover:underline"><?php echo $botao ?></a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <?php if ($temCobranca == false) { ?>
          <div class="w-full p-4 flex justify-center items-center text-gray-700 text-xs">...</div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>