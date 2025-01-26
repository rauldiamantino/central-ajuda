<?php $totalGostou = 0; ?>
<?php $totalNaoGostou = 0; ?>

<div class="dashboard-tabela-feedbacks">
  <div class="w-full min-h-max border border-gray-300 rounded-t-2xl rounded-b-lg shadow bg-white overflow-x-auto overflow-y-hidden overflow-estilo-tabela">
    <div class="inline-block min-w-full align-middle">
      <table class="table-fixed min-w-full text-sm text-left text-gray-500">
        <thead class="text-xs font-light text-gray-500 uppercase">
          <colgroup>
            <col class="w-[60px]">
            <col class="w-[60px]">
            <col class="w-[600px]">
            <col class="w-[400px]">
            <col class="w-[150px]">
            <col class="w-[150px]">
          </colgroup>
          <tr class="bg-gray-100 w-full border-b text-xs">
            <th class="text-gray-400 font-extralight py-4 px-6">ID</th>
            <th class="py-4 px-6">Código</th>
            <th class="py-4 px-4">Título</th>
            <th class="py-4 px-4">Categoria</th>
            <th class="py-4 px-4">Gostou</th>
            <th class="py-4 px-4">Não gostou</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <?php foreach ($relFeedbacks as $chave => $linha) : ?>
            <?php if (isset($linha['Artigo']['id'])) { ?>
              <tr class="hover:bg-gray-100">
                <td class="text-gray-400 font-extralight py-6 px-6"><?php echo $linha['Artigo']['id']; ?></td>
                <td class="py-6 px-6"><?php echo $linha['Artigo']['codigo']; ?></td>
                <td class="py-6 px-4 font-semibold text-gray-900 break-words"><?php echo $linha['Artigo']['titulo']; ?></td>
                <td class="py-6 px-4 break-words"><?php echo $linha['Categoria']['nome']; ?></td>
                <td class="py-6 px-4 whitespace-nowrap"><?php echo $linha['Feedback']['gostou']; ?></td>
                <td class="py-6 px-4 whitespace-nowrap"><?php echo $linha['Feedback']['nao_gostou']; ?></td>
              </tr>

              <?php $totalGostou += (int) $linha['Feedback']['gostou']; ?>
              <?php $totalNaoGostou += (int) $linha['Feedback']['nao_gostou']; ?>
            <?php } ?>
          <?php endforeach; ?>

          <tr class="hover:bg-gray-100 bg-gray-100">
            <td class="text-gray-400 font-extralight py-6 px-6">Totais</td>
            <td class="py-6 px-6"></td>
            <td class="py-6 px-4 font-semibold text-gray-900 break-words"></td>
            <td class="py-6 px-4 break-words"></td>
            <td class="py-6 px-4 whitespace-nowrap font-bold"><?php echo $totalGostou; ?></td>
            <td class="py-6 px-4 whitespace-nowrap font-bold"><?php echo $totalNaoGostou; ?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- <?php require_once 'grafico-pizza.php' ?> -->

<?php // Biblioteca Chart.js ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mt-10 w-full flex justify-center">
  <div class="w-[400px] max-w-full">
    <canvas id="graficoPizza"></canvas>
  </div>
</div>

<?php
$totalAvaliado = $totalGostou + $totalNaoGostou;
$percentualGostou = ($totalGostou / $totalAvaliado) * 100;
$percentualNaoGostou = ($totalNaoGostou / $totalAvaliado) * 100;

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