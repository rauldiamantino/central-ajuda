<!DOCTYPE html>
<html lang="pt-br">
<?php require_once 'template/cabecalho.php' ?>
<body class="w-full min-h-screen flex justify-start justify-center items-center font-normal bg-white">
  <main class="p-4">
    <div class="h-full w-full flex flex-col md:flex-row md:gap-5 md:items-center">
      <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-primary-600"><?php echo $codigoErro; ?></h1>

      <div class="flex flex-col items-start">
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
              <p class="mb-4 w-full text-lg font-light text-start text-gray-500"><?php echo $notificacaoErro; ?></p>
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
  </main>
</body>
</html>