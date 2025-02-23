<?php
$nivelAcesso = [
  USUARIO_TOTAL => 'Acesso total',
  USUARIO_LEITURA => 'Modo leitura',
];

$tipoUsuario = [
  USUARIO_SUPORTE => 'Suporte',
  // USUARIO_PADRAO => 'Padrão',
  USUARIO_COMUM => 'Comum',
];

// Somente usuário de suporte pode criar usuário de suporte e apenas na loja padrão
if ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE or $this->sessaoUsuario->buscar('empresaPadraoId') > 1) {
  unset($tipoUsuario[ USUARIO_SUPORTE ]);
}
?>

<dialog class="border border-slate-300 w-full md:w-[500px] rounded-md shadow menu-adicionar-usuario">
  <form method="POST" class="w-full flex flex-col gap-4 p-4 menu-adicionar-usuario-form" data-action="/d/usuario">
    <div class="w-full flex flex-col gap-4">
      <div class="flex gap-10">
        <div class="w-full flex gap-4">
          <label class="flex flex-col items-start gap-1 cursor-pointer">
            <span class="block text-sm font-medium text-gray-700">Status</span>
            <input type="hidden" name="ativo" value="0">
            <input type="checkbox" value="1" class="sr-only peer" name="ativo" checked>
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          </label>

          <div class="w-full flex flex-col">
            <label for="usuario-editar-nivel" class="block text-sm font-medium text-gray-700">Nível de acesso</label>
            <select id="usuario-editar-nivel" name="nivel" class="<?php echo CLASSES_DASH_INPUT; ?>">
              <?php foreach ($nivelAcesso as $chave => $linha) : ?>
                <option value="<?php echo $chave; ?>">
                  <?php echo $linha; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
            <div class="w-full flex flex-col">
              <label for="usuario-editar-padrao" class="block text-sm font-medium text-gray-700">Tipo de usuário</label>
              <select id="usuario-editar-padrao" name="padrao" class="<?php echo CLASSES_DASH_INPUT; ?>">
                <?php foreach ($tipoUsuario as $chave => $linha) : ?>
                  <option value="<?php echo $chave; ?>">
                    <?php echo $linha; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php } ?>
          <?php if ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE) { ?>
            <input type="hidden" name="padrao" value="2">
          <?php } ?>
        </div>
      </div>
      <div class="w-full">
        <label for="usuario-editar-nome" class="block text-sm font-medium text-gray-700">Nome</label>
        <input type="text" id="usuario-editar-nome" name="nome" class="<?php echo CLASSES_DASH_INPUT; ?>" value="" autofocus>
      </div>
      <div class="w-full">
        <label for="usuario-editar-email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="text" id="usuario-editar-email" name="email" class="<?php echo CLASSES_DASH_INPUT; ?>" value="" autocomplete="off">
      </div>
      <div class="w-full">
        <label for="usuario-editar-senha" class="block text-sm font-medium text-gray-700">Senha</label>
        <input type="password" id="usuario-editar-senha" name="senha" class="<?php echo CLASSES_DASH_INPUT; ?>" autocomplete="off">
      </div>
    </div>
    <div class="flex gap-2">
      <button type="button" onclick="document.querySelector('.menu-adicionar-usuario').close()" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Fechar</button>
      <button type="submit" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?>">Gravar</button>
    </div>
  </form>
</dialog>