<!DOCTYPE html>
<html lang="pt-br">
<?php require_once 'template/cabecalho.php' ?>
<body class="min-h-screen max-w-screen flex flex-col gap-4 font-normal bg-gray-200">
  <main class="w-full min-h-screen flex flex-col gap-4 items-center">

    <div class="w-8/12 min-h-screen flex items-center justify-center bg-white rounded">
        <div class="w-full h-full">
            <div class="mx-auto max-w-screen-sm text-center">
                <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-primary-600"><?php echo $codigoErro; ?></h1>
                <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl">Ops! Algo deu errado.</p>

                <div class="w-full">
                  <?php
                  $notificacaoSucesso = $this->sessaoUsuario->buscar('ok');
                  $notificacaoErro = $this->sessaoUsuario->buscar('erro');
                  $notificacaoNeutra = $this->sessaoUsuario->buscar('neutra');
                  $mensagemErro = 'Desculpe, não conseguimos encontrar essa página.';
                  ?>

                  <?php // Notificação Erro ?>
                  <?php if (isset($notificacaoErro) and $notificacaoErro) { ?>
                    <?php if (is_array($notificacaoErro)) { ?>
                      <?php foreach($notificacaoErro as $linha): ?>
                        <div class="flex flex-col gap-1 items-center">
                          <p class="mb-4 text-lg font-light text-gray-500"><?php echo $linha; ?></p>
                        </div>
                      <?php endforeach; ?>
                    <?php } ?>
                    <?php if (is_string($notificacaoErro)) { ?>
                      <p class="mb-4 w-full text-lg font-light text-center text-gray-500"><?php echo $notificacaoErro; ?></p>
                    <?php } ?>
                  <?php } ?>

                  <?php if (empty($notificacaoErro)) { ?>
                    <p class="mb-4 text-lg font-light text-gray-500"><?php echo $mensagemErro; ?></p>
                  <?php } ?>

                  <?php // Limpa notificações ?>
                  <?php $this->sessaoUsuario->apagar('ok'); ?>
                  <?php $this->sessaoUsuario->apagar('erro'); ?>
                  <?php $this->sessaoUsuario->apagar('neutra'); ?>
                </div>
            </div>
        </div>
    </div>
  </main>
</body>
</html>