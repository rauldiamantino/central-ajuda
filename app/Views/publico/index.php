<!DOCTYPE html>
<html lang="pt-br">

<?php require_once 'template/cabecalho.php' ?>

<body class="min-h-screen max-w-screen flex flex-col font-normal bg-white">

  <?php require_once 'template/topo.php' ?>

  <main class="p-4 w-full min-h-screen flex flex-col gap-4 items-center">
    <?php $notificacaoErro = $this->sessaoUsuario->buscar('erro'); ?>

    <?php // Notificação de erro ?>
    <?php if (isset($notificacaoErro) and $notificacaoErro) { ?>
      <div class="fixed bottom-0 w-full z-30 flex justify-center items-center text-lg js-notificacao-erro-publico js-dashboard-notificacao-erro-btn-fechar">
        <div class="w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          <?php echo $notificacaoErro ?>
        </div>
      </div>
      <?php // Limpar erros ?>
      <?php $this->sessaoUsuario->apagar('erro'); ?>
    <?php } ?>

    <div class="border border-slate-300 w-full lg:w-9/12 min-h-screen flex rounded-md shadow-lg bg-white">
      <?php if ($menuLateral) { ?>
        <?php require_once 'template/menu_lateral.php' ?>
      <?php } ?>

      <?php require_once $visao ?>
    </div>
  </main>

  <?php require_once 'template/rodape.php' ?>
  <?php require_once 'scripts.php' ?>
</body>
</html>