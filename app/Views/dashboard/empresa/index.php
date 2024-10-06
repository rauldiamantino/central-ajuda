<?php
$assinatura = [
  'id' => $stripeAssinatura['id'] ?? '',
  'status' => $stripeAssinatura['status'] ?? '',
  'dataInicio' => $stripeAssinatura['start_date'] ?? 0,
  'periodoAtualInicio' => $stripeAssinatura['current_period_start'] ?? 0,
  'periodoAtualFim' => $stripeAssinatura['current_period_end'] ?? 0,
  'clienteId' => $stripeAssinatura['customer'] ?? '',
  'urlStripe' => $stripeAssinatura['items']['url'] ?? '',
];

$sessaoStripe = $empresa['Empresa.sessao_stripe_id'] ?? '';
?>

<div class="w-full h-full flex flex-col bg-white p-4">
  <h2 class="flex gap-1 text-2xl font-semibold mb-4">
    Editar
    <span class="flex items-center gap-2 text-gray-400 font-light italic text-sm">
      #<?php echo $empresa['Empresa.id']; ?>
    </span>
  </h2>
  <?php require_once 'datas.php' ?>
  <div class="w-full flex flex-col md:flex-row gap-4">
    <div class="w-full flex flex-col gap-2">
      <?php require_once 'formulario.php' ?>
      <?php if ($empresa['Empresa.subdominio'] and isset($_SERVER['SERVER_NAME']) and $_SERVER['SERVER_NAME']) { ?>
        <div class="p-4 flex flex-col flex-wrap md:flex-row md:gap-2 justify-center items-center w-full border-b border-slate-200 text-gray-900 text-center text-sm">
          Divulgue o endereço:
          <a href="<?php echo subdominioDominio($this->usuarioLogado['subdominio']); ?>" target="_blank" class="text-xl text-red-700"><?php echo subdominioDominio($this->usuarioLogado['subdominio'], false); ?></a>
        </div>
      <?php } ?>
    </div>
    <div class="mb-10 md:mb-0 md:block w-full empresa-bloco-assinatura" data-empresa-assinatura="<?php echo $empresa['Empresa.assinatura_id']; ?>">
      <div class="relative border border-slate-200 w-full min-w-96 flex flex-col p-4 rounded-lg shadow">
        <div id="efeito-loader-assinatura" class="hidden loader"></div>

        <h2 class="font-bold pb-2">Assinatura</h2>
        <div class="w-full p-2 flex items-center gap-4">
          <span class="w-2/12 text-xs rounded"><?php echo strtoupper('ID Assinatura') ?></span>
          <div class="w-10/12 px-4 py-2 bg-slate-50">
            <span class="text-sm empresa-assinatura-id"><?php echo $assinatura['id'] ? $assinatura['id'] : '** vazio **' ?></span>
          </div>
        </div>

        <div class="w-full p-2 flex items-center gap-4">
          <span class="w-2/12 text-xs rounded"><?php echo strtoupper('ID da Primeira sessão Stripe') ?></span>
          <div class="w-10/12 px-4 py-2 bg-slate-50">
            <span class="text-sm empresa-assinatura-sessao-id"><?php echo $sessaoStripe ? $sessaoStripe : '** vazio **'?></span>
          </div>
        </div>

        <div class="w-full p-2 flex items-center gap-4">
          <span class="w-2/12 text-xs rounded"><?php echo strtoupper('Status') ?></span>
          <div class="w-10/12 px-4 py-2 bg-slate-50">
            <span class="text-sm empresa-assinatura-status"><?php echo $assinatura['status'] ? strtoupper($assinatura['status']) : '** vazio **'?></span>
          </div>
        </div>

        <div class="w-full p-2 flex items-center gap-4">
          <span class="w-2/12 text-xs rounded"><?php echo strtoupper('Data de início') ?></span>
          <div class="w-10/12 px-4 py-2 bg-slate-50">
            <span class="text-sm empresa-assinatura-data-inicio"><?php echo $assinatura['dataInicio'] ? traduzirDataTimestamp($assinatura['dataInicio']) : '** vazio **'?></span>
          </div>
        </div>

        <div class="w-full p-2 flex items-center gap-4">
          <span class="w-2/12 text-xs rounded"><?php echo strtoupper('Data de início do período atual') ?></span>
          <div class="w-10/12 px-4 py-2 bg-slate-50">
            <span class="text-sm empresa-assinatura-atual-inicio"><?php echo $assinatura['periodoAtualInicio'] ? traduzirDataTimestamp($assinatura['periodoAtualInicio']) : '** vazio **'?></span>
          </div>
        </div>

        <div class="w-full p-2 flex items-center gap-4">
          <span class="w-2/12 text-xs rounded"><?php echo strtoupper('Data de fim do período atual') ?></span>
          <div class="w-10/12 px-4 py-2 bg-slate-50">
            <span class="text-sm empresa-assinatura-atual-fim"><?php echo $assinatura['periodoAtualFim'] ? traduzirDataTimestamp($assinatura['periodoAtualFim']) : '** vazio **'?></span>
          </div>
        </div>

        <div class="w-full p-2 flex items-center gap-4">
          <span class="w-2/12 text-xs rounded"><?php echo strtoupper('ID Cliente') ?></span>
          <div class="w-10/12 px-4 py-2 bg-slate-50">
            <span class="text-sm empresa-assinatura-cliente-id"><?php echo $assinatura['clienteId'] ? $assinatura['clienteId'] : '** vazio **'?></span>
          </div>
        </div>
      </div>

      <?php if (empty($sessaoStripe) and $assinatura['id']) { ?>
        <form action="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/validar_assinatura" method="GET">
          <input type="hidden" name="assinatura_id" value="<?php echo $assinatura['id']; ?>">
          <button type="submit" class="w-full mt-2 border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-xs text-gray-700 rounded-lg">Reprocessar assinatura</button>
        </form>
      <?php } ?>

      <?php if ($sessaoStripe and empty($assinatura['id'])) { ?>
        <form action="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/confirmar_assinatura" method="GET">
          <input type="hidden" name="sessao_stripe_id" value="<?php echo $sessaoStripe; ?>">
          <button type="submit" class="w-full mt-2 border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-xs text-gray-700 rounded-lg">Confirmar assinatura</button>
        </form>
      <?php } ?>
    </div>
  </div>
</div>