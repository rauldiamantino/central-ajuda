<?php
$notificacaoSucesso = $this->sessaoUsuario->buscar('ok');
$notificacaoErro = $this->sessaoUsuario->buscar('erro');
$notificacaoNeutra = $this->sessaoUsuario->buscar('neutra');
?>

<?php // Notificação Sucesso ?>
<?php if (isset($notificacaoSucesso)) { ?>
  <div class="fixed left-1/2 bottom-4 w-full md:w-max z-30 pb-4 px-5 sm:px-0 transform -translate-x-1/2 js-dashboard-notificacao-sucesso" onload="fecharNotificacao()">
    <div class="bg-green-100 border border-green-500 text-green-700 px-4 py-3 rounded relative shadow" role="alert">
      <span class="block sm:inline"><?php echo $notificacaoSucesso; ?></span>
      <span class="absolute top-0 bottom-0 right-0 md:-right-10 px-4 py-3 js-dashboard-notificacao-sucesso-btn-fechar">
        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <title>Fechar</title>
          <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
        </svg>
      </span>
    </div>
  </div>
<?php } ?>
<?php // Notificação Erro ?>
<?php if (isset($notificacaoErro)) { ?>
  <div class="fixed left-1/2 bottom-4 w-full md:w-max z-30 pb-4 px-5 sm:px-0 transform -translate-x-1/2 js-dashboard-notificacao-erro">
    <div class="bg-red-100 border border-red-500 text-red-700 px-4 py-3 rounded relative" role="alert">
      <?php if (is_array($notificacaoErro)) { ?>
        <?php foreach($notificacaoErro as $linha): ?>
          <div class="flex flex-col gap-1">
            <span class="block sm:inline"><?php echo $linha; ?></span>
          </div>
        <?php endforeach; ?>
      <?php } ?>
      <?php if (is_string($notificacaoErro)) { ?>
        <span class="block sm:inline break-words"><?php echo $notificacaoErro; ?></span>
      <?php } ?>
      <span class="absolute top-0 bottom-0 right-0 md:-right-10 px-4 py-3 js-dashboard-notificacao-erro-btn-fechar">
        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <title>Fechar</title>
          <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
        </svg>
      </span>
    </div>
  </div>
<?php } ?>
<?php // Notificação Neutra ?>
<?php if (isset($notificacaoNeutra)) { ?>
  <div class="fixed left-1/2 bottom-4 w-full md:w-max z-30 pb-4 px-5 sm:px-0 transform -translate-x-1/2 js-dashboard-notificacao-neutra">
    <div class="bg-blue-100 border border-blue-500 text-blue-700 px-4 py-3 rounded relative" role="alert">
      <?php if (is_array($notificacaoNeutra)) { ?>
        <?php foreach($notificacaoNeutra as $linha): ?>
          <span class="block sm:inline"><?php echo $linha; ?></span>
        <?php endforeach; ?>
      <?php } ?>
      <?php if (is_string($notificacaoNeutra)) { ?>
        <span class="block sm:inline"><?php echo $notificacaoNeutra; ?></span>
      <?php } ?>
      <span class="absolute top-0 bottom-0 right-0 md:-right-10 px-4 py-3 js-dashboard-notificacao-neutra-btn-fechar">
        <svg class="fill-current h-6 w-6 text-blue-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <title>Fechar</title>
          <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
        </svg>
      </span>
    </div>
  </div>
<?php } ?>
<?php // Limpa notificações ?>
<?php $this->sessaoUsuario->apagar('ok'); ?>
<?php $this->sessaoUsuario->apagar('erro'); ?>
<?php $this->sessaoUsuario->apagar('neutra'); ?>

<?php // Assinatura vencida ?>
<div class="p-10 w-full mb-10 bg-red-600/5 rounded-xl <?php echo $this->sessaoUsuario->buscar('pagamento-vencido-' . $this->empresaPadraoId) == true ? 'flex' : 'hidden'; ?> flex-col notificacao-vencido">
  <div class="w-full text-red-800 font-semibold flex gap-2">
    <div class="w-6">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
      </svg>
    </div>
    <span class="w-full">Ops! Seu acesso foi bloqueado</span>
  </div>
  <div class="mt-2 text-sm text-gray-700 font-light">
    Parece que seu pagamento não foi realizado.<br>
    <div class="mt-2 text-xs">
      Caso tenha alguma dúvida, entre em contato com o nosso suporte:<br>
      Email: suporte@360help.com.br<br>
      WhatsApp: (11) 93433-2319
    </div>
  </div>
</div>

<?php // Teste grátis expirado ?>
<div class="p-10 w-full mb-10 bg-red-600/5 rounded-xl <?php echo $this->sessaoUsuario->buscar('teste-expirado-' . $this->empresaPadraoId) == true ? 'flex' : 'hidden'; ?> flex-col notificacao-expirado">
  <div class="w-full text-red-800 font-semibold flex gap-2">
    <div class="w-6">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
      </svg>
    </div>
    <span class="w-full">Ops! Seu acesso foi bloqueado</span>
  </div>
  <div class="mt-2 text-sm text-gray-700 font-light">
    Para desbloquear, assine um dos planos disponíveis no menu <a href="/dashboard/assinatura/editar" class="hover:underline text-blue-600">Assinatura</a>.<br>
    <div class="mt-2 text-xs">
      Caso tenha alguma dúvida, entre em contato com o nosso suporte:<br>
      Email: suporte@360help.com.br<br>
      WhatsApp: (11) 93433-2319
    </div>
  </div>
</div>

<?php // Limite de armazenamento atingido ?>
<div class="p-10 w-full mb-10 bg-red-600/5 rounded-xl <?php echo $this->sessaoUsuario->buscar('bloqueio-espaco-' . $this->empresaPadraoId) == true ? 'flex' : 'hidden'; ?> flex-col notificacao-espaco">
  <div class="w-full text-red-800 font-semibold flex gap-2">
    <div class="w-6">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
      </svg>
    </div>
    <span class="w-full">Ops! Seu armazenamento está cheio</span>
  </div>
  <div class="mt-2 text-sm text-gray-700 font-light">
    Tente remover alguns conteúdos ou fale com o suporte para obter mais espaço.<br>
    <div class="mt-2 text-xs">
      Email: suporte@360help.com.br<br>
      WhatsApp: (11) 93433-2319
    </div>
  </div>
</div>