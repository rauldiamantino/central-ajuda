<div class="pt-10 border-t border-gray-300 w-full h-full flex flex-col gap-4 editar-plano">
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
      <button <?php echo ($assinaturaId) ? 'disabled' : ''; ?> onclick="window.location.href='/<?php echo $this->usuarioLogado['subdominio'] . '/d/assinaturas/gerar?plano=mensal'; ?>';" class="border border-gray-200 w-full lg:w-[700px] p-8 flex flex-col gap-2 md:flex-row justify-between md:items-center rounded-lg bg-white hover:bg-gray-100">
        <div class="text-left">
          <div class="flex flex-col md:flex-row gap-1 md:gap-2 items-start md:items-end">
            <span class="text-sm font-semibold">Mensal</span>
            <span class="text-sm font-light text-gray-500 line-through">De R$ 119,00/mês</span>
            <span class="text-sm text-green-700 font-normal">por R$ 99,00/mês</span>
          </div>
        </div>
        <span class="text-sm font-semibold text-blue-800"><?php echo $assinaturaId ? '' : 'Assinar'; ?></span>
      </button>
      <button <?php echo ($assinaturaId) ? 'disabled' : ''; ?> onclick="window.location.href='/<?php echo $this->usuarioLogado['subdominio'] . '/d/assinaturas/gerar?plano=anual'; ?>';" class="border border-gray-200 w-full lg:w-[700px] p-8 flex flex-col gap-2 md:flex-row justify-between md:items-center rounded-lg bg-white hover:bg-gray-100">
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