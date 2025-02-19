<?php
// Largura geral
if ((int) Helper::ajuste('publico_largura_geral') == 4) {
  $classesLarguraGeral = 'max-w-[800px]';
}
elseif ((int) Helper::ajuste('publico_largura_geral') == 3) {
  $classesLarguraGeral = 'max-w-[900px]';
}
elseif ((int) Helper::ajuste('publico_largura_geral') == 2) {
  $classesLarguraGeral = 'max-w-[1024px]';
}
else {
  $classesLarguraGeral = 'max-w-[1244px]';
}

// Estilo template
$classesPublicoFundo = '';
$stylePublicoTexto = 'style="color: ' . Helper::ajuste('publico_inicio_texto_cor_desktop') . ';"';;

if ((int) Helper::ajuste('publico_inicio_cor_fundo') == ATIVO) {
  $classesPublicoFundo = 'pers-publico-fundo template-cor-' . Helper::ajuste('publico_cor_primaria');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
  <?php require_once 'template/cabecalho.php'; ?>

  <body class="min-h-screen max-w-screen flex flex-col font-normal bg-white text-black" data-base-url="<?php echo RAIZ; ?>">
    <?php require_once 'template/efeito-loader.php'; ?>

    <div class="hidden <?php echo $classesPublicoFundo; ?>" <?php echo $stylePublicoTexto; ?> id="conteudo-publico">
      <?php require_once 'template/topo.php'; ?>
      <?php require_once 'inicio/bloco-busca.php'; ?>
      <?php require_once 'template/notificacoes.php' ?>
      <?php require_once 'template/conteudo-principal.php' ?>
      <?php require_once 'template/rodape.php' ?>
      <?php require_once 'template/debug.php' ?>
    </div>

    <?php require_once 'scripts.php' ?>
  </body>
</html>