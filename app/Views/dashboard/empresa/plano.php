<div class="mt-10 border-t border-slate-300 w-full h-full flex flex-col gap-4 editar-plano">

  <div class="py-8">
    <h3 class="text-lg font-semibold">Plano</h3>
    <div class="text-sm text-gray-700">Ajuste sua assinatura e mude de plano quando quiser!</div>

    <?php // Plano ?>
    <div class="pt-5 w-full flex flex-col gap-5">
      <button class="border border-slate-200 w-full lg:w-[700px] p-8 flex flex-col gap-2 md:flex-row justify-between md:items-center rounded-lg bg-slate-50 hover:bg-slate-100">
        <div class="text-left">
          <div class="flex flex-col md:flex-row gap-1 md:gap-2 items-start md:items-end">
            <span class="text-sm font-semibold">Mensal</span>
            <span class="text-sm font-light text-gray-500 line-through">De R$ 149,00/mês</span>
            <span class="text-sm text-green-700 font-normal">por R$ 99,00/mês</span>
          </div>
        </div>
        <span class="text-sm font-semibold text-blue-800">Assinar</span>
      </button>
      <button class="border border-slate-200 w-full lg:w-[700px] p-8 flex flex-col gap-2 md:flex-row justify-between md:items-center rounded-lg bg-slate-50 hover:bg-slate-100">
        <div class="text-left">
          <div class="h-full flex flex-col md:flex-row gap-1 md:gap-2 items-start md:items-end">
            <span class="text-sm font-semibold">Anual</span>
            <span class="text-sm font-light text-gray-500 line-through">De R$ 114,00/mês</span>
            <span class="text-sm text-green-700 font-normal">por R$ 64,00/mês</span>
            <span class="text-sm font-light text-gray-500">cobrado anualmente (R$ 768,00/ano)</span>
          </div>
        </div>
        <span class="text-sm font-semibold text-blue-800">Assinar</span>
      </button>
    </div>

    <?php // Cobranças ?>
    <h3 class="mt-12 text-lg font-semibold">Histórico de cobranças</h3>
    <div class="mb-6 text-sm text-gray-700">Veja o que já foi cobrado e pago até agora.</div>
    <div class="border border-slate-300 w-full lg:w-[700px] min-h-max rounded-md shadow bg-white overflow-x-auto overflow-y-hidden">
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
            <tr class="bg-slate-100 w-full border-b">
              <th class="py-5 px-4">Vencimento</th>
              <th class="py-5 px-4">Descrição</th>
              <th class="py-5 px-4">Status</th>
              <th class="py-5 px-4">Valor</th>
              <th class="py-5 px-4"></th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <tr class="hover:bg-slate-100">
              <td class="py-5 px-4">12/12/2024</td>
              <td class="py-5 px-4 font-semibold">Mensal</td>
              <td class="py-5 px-4">
                <div class="flex items-center gap-2">
                  <span class="px-3 py-1 bg-orange-50 text-orange-600 text-xs rounded-full">Pendente</span>
                </div>
              </td>
              <td class="py-5 px-4 text-green-700">R$ 69,90</td>
              <td class="py-5 px-4 font-semibold text-blue-800">
                <a href="" class="font-semibold hover:underline">Pagar</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>