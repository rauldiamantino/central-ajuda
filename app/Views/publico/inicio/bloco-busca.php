<?php
if (! isset($inicio)) {
  return;
}

$styleFundoBuscaInicio = '';
$classesBuscaTamanho = 'max-w-[800px]';
$classesBuscaAlinhamento = 'justify-center';

// Busca: Tamanho
if ((int) Helper::ajuste('publico_inicio_busca_tamanho') == 1) {
  $classesBuscaTamanho = 'max-w-[600px]';
}
elseif ((int) Helper::ajuste('publico_inicio_busca_tamanho') == 2) {
  $classesBuscaTamanho = 'max-w-[800px]';
}
elseif ((int) Helper::ajuste('publico_inicio_busca_tamanho') == 3) {
  $classesBuscaTamanho = 'max-w-[1244px]';
}

// Busca: Alinhamento bloco
if ((int) Helper::ajuste('publico_inicio_busca_alinhamento') == 1) {
  $classesBuscaAlinhamento = 'justify-start';
}
elseif ((int) Helper::ajuste('publico_inicio_busca_alinhamento') == 2) {
  $classesBuscaAlinhamento = 'justify-center';

}
elseif ((int) Helper::ajuste('publico_inicio_busca_alinhamento') == 3) {
  $classesBuscaAlinhamento = 'justify-end';
}

// Busca: Alinhamento texto
if ((int) Helper::ajuste('publico_inicio_busca_alinhamento_texto') == 1) {
  $classesBuscaAlinhamentoTexto = 'text-start';
}
elseif ((int) Helper::ajuste('publico_inicio_busca_alinhamento_texto') == 2) {
  $classesBuscaAlinhamentoTexto = 'text-center';

}
elseif ((int) Helper::ajuste('publico_inicio_busca_alinhamento_texto') == 3) {
  $classesBuscaAlinhamentoTexto = 'text-end';
}

// Busca: Fundo
$buscarFotoInicio = Helper::ajuste('publico_inicio_foto_desktop');

if ($buscarFotoInicio and isset($inicio)) {
  $fotoInicio = $this->renderImagem($buscarFotoInicio);
  $styleFundoBuscaInicio = 'style="color: ' . Helper::ajuste('publico_inicio_texto_cor_desktop') . ';"';
  $classesPublicoFundo = '';
}

if ((int) Helper::ajuste('publico_inicio_arredondamento') == 0) {
  $classesAlturaFundoBusca = 'pt-24 md:pt-20 h-[460px] md:h-[480px]';
}
elseif ((int) Helper::ajuste('publico_inicio_arredondamento') > 0) {
  $classesAlturaFundoBusca = 'pt-14 md:pt-5 h-[500px] md:h-[540px]';
}
else {
  $classesAlturaFundoBusca = 'h-[540px]';
}
?>

<div class="<?php echo $classesAlturaFundoBusca; ?> relative px-4 md:px-8 w-full flex items-center justify-center">

  <?php if ($buscarFotoInicio) { ?>
    <div class="absolute inset-0 top-0 -z-10 overflow-hidden">
      <img src="<?php echo $fotoInicio; ?>" alt="Imagem de fundo desktop" class="min-w-full h-full object-cover opacity-0" onload="this.style.opacity=1;" />
    </div>
  <?php } ?>

  <?php require 'formulario-busca.php' ?>
</div>