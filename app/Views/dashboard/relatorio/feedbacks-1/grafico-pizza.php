<?php
$totalAvaliado = $totalGostou + $totalNaoGostou;
$percentualGostou = ($totalGostou / $totalAvaliado) * 100;
$percentualNaoGostou = ($totalNaoGostou / $totalAvaliado) * 100;

$dados = [
  number_format($percentualNaoGostou, 1) . '%' => $totalNaoGostou,
  number_format($percentualGostou, 1) . '%' => $totalGostou,
];

$valores = implode(',', array_values($dados));
$labels = implode('|', array_keys($dados));
$graficoUrl = "https://image-charts.com/chart?cht=p3&chs=500x300&chd=t:$valores&chl=$labels&chco=F44336|4CAF50&chf=bg,s,F3F4F6";
?>

<div class="w-full flex flex-col lg:flex-row justify-between gap-10 py-10">
  <div class="w-full">
    <ul>
      <li class="flex gap-2 items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="border border-black text-green-600 rounded" viewBox="0 0 16 16">
          <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2z"/>
        </svg>
        <span>Total gostou</span>
      </li>
      <li class="flex gap-2 items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="border border-black text-red-600 rounded" viewBox="0 0 16 16">
          <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2z"/>
        </svg>
        <span>Total não gostou</span>
      </li>
    </ul>
  </div>
  <img src="<?php echo $graficoUrl; ?>" alt="Gráfico de Pizza - Feedbacks de artigos publicados" class="w-max">
</div>