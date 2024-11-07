<!DOCTYPE html>
<html lang="pt-br">

<?php require_once 'template/cabecalho.php' ?>

<body class="min-h-screen max-w-screen flex flex-col font-normal bg-white" data-base-url="<?php echo RAIZ; ?>">

  <?php require_once 'template/topo.php' ?>

  <main class="p-4 w-full min-h-screen flex flex-col gap-4 items-center">
    <?php $notificacaoSucesso = $this->sessaoUsuario->buscar('ok'); ?>
    <?php $notificacaoErro = $this->sessaoUsuario->buscar('erro'); ?>

    <?php // Notificação de Sucesso ?>
    <?php if (isset($notificacaoSucesso) and $notificacaoSucesso) { ?>
      <div class="fixed bottom-0 w-full z-30 flex justify-center items-center text-lg js-notificacao-sucesso-publico js-dashboard-notificacao-sucesso-btn-fechar">
        <div class="w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
          <?php echo $notificacaoSucesso ?>
        </div>
      </div>
    <?php } ?>

    <?php // Notificação de erro ?>
    <?php if (isset($notificacaoErro) and $notificacaoErro) { ?>
      <div class="fixed bottom-0 w-full z-30 flex justify-center items-center text-lg js-notificacao-erro-publico js-dashboard-notificacao-erro-btn-fechar">
        <div class="w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          <?php echo $notificacaoErro ?>
        </div>
      </div>
    <?php } ?>

    <?php // Limpar notificações ?>
    <?php $this->sessaoUsuario->apagar('ok'); ?>
    <?php $this->sessaoUsuario->apagar('erro'); ?>

    <?php
    $bordaOk = false;

    if (isset($inicio) and (int) $this->buscarAjuste('publico_borda_inicio') == ATIVO) {
      $bordaOk = true;
    }

    if (! isset($inicio) and (int) $this->buscarAjuste('publico_borda_artigo') == ATIVO) {
      $bordaOk = true;
    }
    ?>
    <div class="<?php echo $bordaOk ? 'lg:border lg:border-slate-300 lg:shadow-lg ' : '' ?>w-full lg:w-8/12 min-h-screen flex rounded-md bg-white">

      <?php if ($menuLateral) { ?>
        <?php require_once 'template/menu_lateral.php' ?>
      <?php } ?>

      <?php require_once $visao ?>
    </div>
  </main>

  <?php require_once 'template/rodape.php' ?>

  <?php if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE and $this->sessaoUsuario->buscar('debugAtivo')) { ?>
    <div class="my-10 px-4 lg:px-14 flex max-w-screen">
      <div class="w-full">
        <h2 class="mb-5 text-2xl font-semibold">Debug</h2>
        <div class="border border-slate-300 w-full p-4 lg:p-10 bg-gray-200 text-gray-900 text-xs shadow rounded-md">
          <div class="py-4 overflow-x-auto">
            <?php pr($this->sessaoUsuario->buscar('debug')); ?>
          </div>
          <?php $this->sessaoUsuario->apagar('debug'); ?>
        </div>
      </div>
    </div>
  <?php } ?>

  <?php require_once 'scripts.php' ?>
</body>
</html>