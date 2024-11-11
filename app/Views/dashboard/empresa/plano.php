<div class="border-t border-slate-300 w-full h-full flex flex-col gap-4 editar-plano">

  <div class="py-8">
    <h3 class="text-lg font-semibold">Plano</h3>
    <div class="text-sm text-gray-700">Ajuste sua assinatura e mude de plano quando quiser!</div>

    <div class="pt-5 w-full flex flex-col gap-5 divide-y">
      <?php // Plano ?>
      <button class="border border-slate-200 w-full lg:w-[700px] p-5 flex flex-col gap-2 md:flex-row justify-between md:items-center rounded-lg bg-slate-50 hover:bg-slate-100">
        <div class="text-left">
          <div class="flex flex-col md:flex-row gap-1 md:gap-2 items-start md:items-end">
            <span class="text-sm font-semibold">Mensal</span>
            <span class="text-sm font-light text-gray-500 line-through">De R$ 149,00/mês</span>
            <span class="text-sm text-green-700 font-normal">por R$ 99,00/mês</span>
          </div>
          <span class="text-sm font-light text-gray-600">Renove mensalmente, sem compromisso.</span>
        </div>
        <span class="text-sm font-semibold text-blue-800">Assinar</span>
      </button>
      <?php // Plano ?>
      <button class="border border-slate-200 w-full lg:w-[700px] p-5 flex flex-col gap-2 md:flex-row justify-between md:items-center rounded-lg bg-slate-50 hover:bg-slate-100">
        <div class="text-left">
          <div class="flex flex-col md:flex-row gap-1 md:gap-2 items-start md:items-end">
            <span class="text-sm font-semibold">Anual</span>
            <span class="text-sm font-light text-gray-500 line-through">De R$ 114,00/mês</span>
            <span class="text-sm text-green-700 font-normal">por R$ 64,00/mês</span>
            <span class="text-sm font-light text-gray-500">cobrado anualmente (R$ 768,00/ano)</span>
          </div>
          <span class="text-sm font-light text-gray-600">Renove mensalmente, sem compromisso.</span>
        </div>
        <span class="text-sm font-semibold text-blue-800">Assinar</span>
      </button>
    </div>
    <?php // Cobranças ?>

    <h3 class="mt-20 text-lg font-semibold">Histórico de cobranças</h3>
    <div class="text-sm text-gray-700">Veja o que já foi cobrado e pago até agora.</div>
    <div class="w-full lg:w-[700px] py-4 items-center">
    </div>
  </div>
</div>