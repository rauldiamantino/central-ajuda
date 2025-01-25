<?php
date_default_timezone_set('America/Sao_Paulo');
$horaAtual = date('H');

if ($horaAtual >= 5 and $horaAtual < 12) {
  $saudacao = 'Bom dia';
}
elseif ($horaAtual >= 12 and $horaAtual < 18) {
  $saudacao = 'Boa tarde';
}
else {
  $saudacao = 'Boa noite';
}

$dominio = $this->usuarioLogado['subdominio_2'];

if (empty($dominio)) {
  $dominio = '/';
}
?>

<div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
  <div class="mb-5 w-full h-full flex flex-col justify-end">
    <h2 class="text-3xl font-semibold flex gap-2"><?php echo $saudacao; ?>! Vamos lá?</h2>
    <p class="text-gray-600">Organize ideias, inspire seu público e transforme conhecimento em ação!</p>
  </div>
</div>