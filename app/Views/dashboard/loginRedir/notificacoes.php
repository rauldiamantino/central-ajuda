<?php
$notificacaoSucesso = $this->sessaoUsuario->buscar('ok');
$notificacaoErro = $this->sessaoUsuario->buscar('erro');
?>

<div class="w-full flex justify-center fixed inset-x-0 bottom-0">
  <div class="w-full">
    <?php // Notificação Sucesso ?>
    <?php if (isset($notificacaoSucesso)) { ?>
      <div class="">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
          <strong class="font-bold">Ok!</strong>
          <span class="block sm:inline"><?php echo $notificacaoSucesso; ?></span>
        </div>
      </div>
    <?php } ?>
    <?php // Notificação Erro ?>
    <?php if (isset($notificacaoErro)) { ?>
      <div class="">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
          <strong class="font-bold">Erro!</strong>
          <?php if (is_array($notificacaoErro)) { ?>
            <?php foreach($notificacaoErro as $linha): ?>
              <div class="flex flex-col gap-1">
                <span class="block sm:inline"><?php echo $linha; ?></span>
              </div>
            <?php endforeach; ?>
          <?php } ?>
          <?php if (is_string($notificacaoErro)) { ?>
            <span class="block sm:inline"><?php echo $notificacaoErro; ?></span>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
    <?php // Limpa notificações ?>
    <?php $this->sessaoUsuario->apagar('ok'); ?>
    <?php $this->sessaoUsuario->apagar('erro'); ?>
    <?php $this->sessaoUsuario->apagar('neutra'); ?>
  </div>
</div>
