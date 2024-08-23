<div class="w-full flex justify-center absolute inset-x-0 -bottom-10">
  <div class="w-max">
    <?php // Notificação Sucesso ?>
    <?php if (isset($_SESSION['ok'])) { ?>
      <div class="js-dashboard-notificacao-sucesso js-dashboard-notificacao-sucesso-btn-fechar"">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
          <strong class="font-bold">Ok!</strong>
          <span class="block sm:inline"><?php echo $_SESSION['ok']; ?></span>
        </div>
      </div>
    <?php } ?>
    <?php // Notificação Erro ?>
    <?php if (isset($_SESSION['erro'])) { ?>
      <div class="js-dashboard-notificacao-erro js-dashboard-notificacao-erro-btn-fechar">
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
        </div>
      </div>
    <?php } ?>
    <?php // Limpa notificações ?>
    <?php $_SESSION['ok'] = null; ?>
    <?php $_SESSION['erro'] = null; ?>
  </div>
</div>
