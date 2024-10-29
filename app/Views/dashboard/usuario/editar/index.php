<div class="relative w-screen min-h-full flex flex-col p-4">
  <h2 class="flex gap-1 text-2xl font-semibold mb-4">
    Editar
    <?php require_once 'usuario-padrao.php' ?>
  </h2>
  <?php require_once 'datas.php' ?>
  <div class="w-full flex flex-col md:flex-row gap-4">
    <div class="w-full flex flex-col gap-2">
      <?php require_once 'formulario.php' ?>
    </div>
    <div class="mb-10 md:mb-0 md:block w-full">
      <?php // Somente suporte ?>
      <?php if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
        <div class="border border-slate-300 w-full md:min-w-96 flex flex-col p-4 rounded-lg shadow bg-white">
          <div class="px-2 w-full flex gap-4 justify-between items-center">
            <h2 class="font-bold pb-2">Último acesso</h2>
            <?php if ($usuario['Usuario']['tentativas_login'] >= 10) { ?>
              <div class="flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-unlock" viewBox="0 0 16 16">
                  <path d="M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2M3 8a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1z"/>
                </svg>
                <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/usuario/desbloquear/' . $usuario['Usuario']['id']); ?>" class="text-sm hover:underline">Desbloquear</a>
              </div>
            <?php } ?>
          </div>

          <?php
          $ultimoAcesso = $usuario['Usuario']['ultimo_acesso'] ?? '';
          $ultimoAcesso = json_decode($ultimoAcesso, true);
          ?>

          <?php if ($ultimoAcesso) {?>
            <?php foreach ($ultimoAcesso as $chave => $linha): ?>
              <?php // Sempre ocultar ?>
              <?php if ($chave == 'idSessao') { continue; } ?>

              <div class="w-full p-2 flex flex-col sm:flex-row items-center sm:gap-6">
                <span class="w-full sm:w-2/12 text-xs rounded"><?php echo strtoupper($chave) ?></span>
                <div class="<?php echo CLASSES_DASH_INPUT_BLOCK; ?>">
                  <span class="text-sm"><?php echo $linha ?? '' ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php } ?>

          <div class="w-full p-2 flex flex-col sm:flex-row items-center sm:gap-6">
            <span class="w-full sm:w-2/12 text-xs rounded"><?php echo strtoupper('tentativas') ?></span>
              <div class="<?php echo CLASSES_DASH_INPUT_BLOCK; ?>">
            <span class="text-sm"><?php echo $usuario['Usuario']['tentativas_login'] ?></span>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</div>