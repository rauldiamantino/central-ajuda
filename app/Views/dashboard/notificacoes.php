<?php // Notificação Sucesso ?>
<?php if (isset($_SESSION['ok'])) { ?>
  <div class="p-4 js-dashboard-notificacao-sucesso" onload="fecharNotificacao()">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
      <strong class="font-bold">Ok!</strong>
      <span class="block sm:inline"><?php echo $_SESSION['ok']; ?></span>
      <span class="absolute top-0 bottom-0 right-0 px-4 py-3 js-dashboard-notificacao-sucesso-btn-fechar">
        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <title>Fechar</title>
          <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
        </svg>
      </span>
    </div>
  </div>
<?php } ?>
<?php // Notificação Erro ?>
<?php if (isset($_SESSION['erro'])) { ?>
  <div class="p-4 js-dashboard-notificacao-erro">
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      <strong class="font-bold">Erro!</strong>
      <?php if (is_array($_SESSION['erro'])) { ?>
        <?php foreach($_SESSION['erro'] as $linha): ?>
          <div class="flex flex-col gap-1">
            <span class="block sm:inline"><?php echo $linha; ?></span>
          </div>
        <?php endforeach; ?>
      <?php } ?>
      <?php if (is_string($_SESSION['erro'])) { ?>
        <span class="block sm:inline"><?php echo $_SESSION['erro']; ?></span>
      <?php } ?>
      <span class="absolute top-0 bottom-0 right-0 px-4 py-3 js-dashboard-notificacao-erro-btn-fechar">
        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <title>Fechar</title>
          <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
        </svg>
      </span>
    </div>
  </div>
<?php } ?>
<?php // Notificação Neutra ?>
<?php if (isset($_SESSION['neutra'])) { ?>
  <div class="p-4 js-dashboard-notificacao-neutra">
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
      <?php if (is_array($_SESSION['neutra'])) { ?>
        <?php foreach($_SESSION['neutra'] as $linha): ?>
          <span class="block sm:inline"><?php echo $linha; ?></span>
        <?php endforeach; ?>
      <?php } ?>
      <?php if (is_string($_SESSION['neutra'])) { ?>
        <span class="block sm:inline"><?php echo $_SESSION['neutra']; ?></span>
      <?php } ?>
      <span class="absolute top-0 bottom-0 right-0 px-4 py-3 js-dashboard-notificacao-neutra-btn-fechar">
        <svg class="fill-current h-6 w-6 text-blue-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <title>Fechar</title>
          <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
        </svg>
      </span>
    </div>
  </div>
<?php } ?>
<?php // Limpa notificações ?>
<?php $_SESSION['ok'] = null; ?>
<?php $_SESSION['erro'] = null; ?>
<?php $_SESSION['neutra'] = null; ?>