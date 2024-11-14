<?php
$dataHoje = strtotime('today');
$dataCadastro = strtotime($this->usuarioLogado['empresaCriado']);
$dataFinalTeste = strtotime($this->usuarioLogado['gratisPrazo']);

$totalDiasTeste = ($dataFinalTeste - $dataCadastro) / (60 * 60 * 24);
$diasPassados = ($dataHoje - $dataCadastro) / (60 * 60 * 24);

if ($totalDiasTeste > 0) {
  $progresso = ($diasPassados / $totalDiasTeste) * 100;
  $progresso = min(100, max(0, $progresso));

  $diasRestantes = max(0, round($totalDiasTeste - $diasPassados));
  $mensagemTesteFinalizado = $progresso >= 100 ? 'Teste grátis finalizado!' : ($diasRestantes == 1 ? 'Falta 1 dia para terminar!' : 'Faltam ' . $diasRestantes . ' dias para terminar!');
}
else {
  $progresso = 100;
  $mensagemTesteFinalizado = "Teste grátis finalizado!";
}
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