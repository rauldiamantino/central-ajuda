<div class="relative w-full min-h-full flex flex-col bg-white p-4">
  <h2 class="flex gap-1 text-2xl font-semibold mb-4">
    Editar
    <?php require_once 'usuario-padrao.php' ?>
  </h2>
  <?php require_once 'datas.php' ?>
  <div class="w-full flex flex-col md:flex-row gap-4">
    <div class="w-full flex flex-col gap-2">
      <?php require_once 'formulario.php' ?>
    </div>
    <div class="md:block w-full">
      <?php // Somente suporte ?>
      <?php if ($this->usuarioLogado['padrao'] == 0) { ?>
        <div class="border border-slate-200 w-full min-w-96 flex flex-col p-4 rounded-lg shadow">
          <h2 class="font-bold pb-2">Último acesso</h2>
          <?php
          $ultimoAcesso = $usuario['Usuario.ultimo_acesso'] ?? '';
          $ultimoAcesso = json_decode($ultimoAcesso, true);
          ?>

          <?php if ($ultimoAcesso) {?>
            <?php foreach ($ultimoAcesso as $chave => $linha): ?>
              <?php // Sempre ocultar ?>
              <?php if ($chave == 'idSessao') { continue; } ?>

              <div class="w-full p-2 flex items-center gap-4">
                <span class="w-2/12 text-xs rounded"><?php echo strtoupper($chave) ?></span>
                <div class="w-10/12 px-4 py-1 bg-slate-50">
                  <span class="text-sm"><?php echo $linha ?? '' ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php } ?>

          <div class="w-full p-2 flex items-center gap-4">
            <span class="w-2/12 text-xs rounded"><?php echo strtoupper('tentativas') ?></span>
            <div class="w-10/12 px-4 py-2 bg-slate-50">
              <span class="text-sm"><?php echo $usuario['Usuario.tentativas_login'] ?></span>
            </div>
          </div>
        </div>

        <?php if ($usuario['Usuario.tentativas_login'] >= 10) { ?>
          <a href="/<?php echo $this->usuarioLogado['subdominio'] ?>/d/usuario/desbloquear/<?php echo $usuario['Usuario.id']; ?>" class="mt-2 border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-xs text-gray-700 rounded-lg">Desbloquear</a>
        <?php } ?>
      <?php } ?>
    </div>
  </div>
</div>