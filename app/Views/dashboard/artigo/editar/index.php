<?php
$referer = '';

if ($botaoVoltar) {
  $referer = '?referer=' . urlencode($botaoVoltar);
}

$dominio = '';

if ($dominio) {
  $dominio = $this->usuarioLogado['subdominio_2'];
}
?>

<div class="mb-10 relative w-full h-max flex flex-col">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex flex-wrap sm:gap-2 items-end">
        Editar artigo

        <?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
          <span class="text-gray-400 font-extralight">#<?php echo $artigo['Artigo']['id'] . '-' . $artigo['Artigo']['codigo'] ?></span>
        <?php } ?>
      </h2>
      <p class="text-gray-600">Vamos deixar seus tutoriais ainda melhores?</p>
    </div>
    <div class="py-2 w-full h-full flex gap-2 items-start justify-end">
      <a href="<?php echo $botaoVoltar ? $botaoVoltar : '/dashboard/artigos' . $referer; ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
      <?php require_once 'conteudo/menu-auxiliar.php'; ?>
    </div>
  </div>

  <div class="w-full border-t border-slate-300 pt-4 flex flex-col lg:justify-between gap-5">

    <div class="w-full max-w-[990px] bg-white duration-350">
      <div class="relative pb-10 w-full bg-gray-100">
        <div class="w-full flex flex-col items-start sm:flex-row sm:justify-between sm:items-center">
          <div class="w-full flex flex-wrap gap-6 justify-start items-center pt-2 pb-6">
            <?php require_once 'conteudo/menu-status.php'; ?>
            <?php require_once 'conteudo/menu-adicionar.php'; ?>
          </div>
        </div>
        <?php require_once 'conteudo/pre-visualizacao.php' ?>
      </div>
    </div>
  </div>
</div>

<?php require_once 'modais/formulario.php' ?>
<?php require_once 'modais/remover-artigo.php' ?>
<?php require_once 'conteudo/modais/organizar.php' ?>
<?php require_once 'conteudo/modais/remover.php' ?>
<?php require_once 'conteudo/modais/fechar.php' ?>