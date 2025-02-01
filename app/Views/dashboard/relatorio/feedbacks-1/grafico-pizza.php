<?php // Biblioteca Chart.js ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mt-6 w-full flex justify-center">
  <div class="w-[400px] max-w-full">
    <canvas id="graficoPizza"></canvas>
  </div>
</div>

<?php
$totalAvaliado = $totalGostou + $totalNaoGostou;

$percentualGostou = 0;
$percentualNaoGostou = 0;

if ($totalAvaliado) {
  $percentualGostou = ($totalGostou / $totalAvaliado) * 100;
  $percentualNaoGostou = ($totalNaoGostou / $totalAvaliado) * 100;
}

$labelGostou = 'Gostou: ' . number_format($percentualGostou, 1) . '%';
$labelNaoGostou = 'Não Gostou: ' . number_format($percentualNaoGostou, 1) . '%';
?>
<script>
  const totalGostou = <?php echo $totalGostou; ?>;
  const totalNaoGostou = <?php echo $totalNaoGostou; ?>;
  const ctx = document.getElementById('graficoPizza').getContext('2d');

  new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['<?php echo $labelGostou; ?>', '<?php echo $labelNaoGostou; ?>'],
        datasets: [{
            data: [totalGostou, totalNaoGostou],
            backgroundColor: ['#4CAF50', '#F44336'],
        }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
      },
    },
  });
</script>