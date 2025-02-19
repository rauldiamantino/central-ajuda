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