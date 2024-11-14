<?php
$diasGratis = 14;
$dataHoje = date('Y-m-d H:i:m');
$dataCadastro = $this->usuarioLogado['empresaCriado'];

$dataHojeObj = new DateTime($dataHoje);
$dataCadastroObj = new DateTime($dataCadastro);

$dataFinalTeste = clone $dataCadastroObj;
$dataFinalTeste->modify('+ ' . $diasGratis . ' days');

$intervaloTotal = $dataCadastroObj->diff($dataFinalTeste);
$intervaloPassado = $dataCadastroObj->diff($dataHojeObj);

$totalDiasTeste = $intervaloTotal->days;
$diasPassados = $intervaloPassado->days;

$progresso = ($diasPassados / $totalDiasTeste) * 100;
$progresso = min(100, max(0, $progresso));

$mensagemTesteFinalizado = $progresso >= 100 ? "Teste grátis finalizado!" : "Faltam " . ($diasGratis - $diasPassados) . " dias para terminar!";
?>

<li class="m-4 px-4 py-3 bg-gray-700/25 <?php echo $paginaSelecionada == 'empresa' ? 'bg-gray-700' : ''; ?> rounded-lg flex flex-col gap-0 group">
  <h2 class="text-lg font-semibold text-white">Teste grátis</h2>
  <div class="text-sm text-gray-300 "><?php echo $mensagemTesteFinalizado; ?></div>

  <?php if ($progresso < 100) { ?>
    <div class="w-full bg-gray-300 rounded-full h-2.5 mt-4">
      <div class="h-2.5 rounded-full" style="width: <?php echo $progresso; ?>%; background-color: <?php echo $progresso >= 100 ? '#1d4ed8' : '#3b82f6'; ?>;"></div>
    </div>
  <?php } ?>

  <button onclick="window.location.href='/<?php echo $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar?acao=assinar'; ?>';" class="mt-5 <?php echo CLASSES_DASH_BUTTON_ASSINAR ?>">Assinar 360Help</button>
</li>