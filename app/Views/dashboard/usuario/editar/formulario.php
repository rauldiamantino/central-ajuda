<?php
$nivelAcesso = [
  USUARIO_TOTAL => 'Acesso total',
  USUARIO_RESTRITO => 'Acesso restrito',
];
?>

<form method="POST" action="<?php echo baseUrl('/d/' . $this->usuarioLogado['empresaId'] . '/usuario/' . $usuario['Usuario']['id']); ?>" class="border border-slate-300 w-full min-w-96 flex flex-col gap-4 p-4 rounded-lg shadow bg-white">
  <input type="hidden" name="_method" value="PUT">
  <input type="hidden" name="empresa_id" value="<?php echo $usuario['Usuario']['empresa_id']; ?>">
  <div class="w-full flex flex-col gap-4">
    <div class="flex gap-10">
      <div class="w-full flex gap-4">
        <label class="flex flex-col items-start gap-1 cursor-pointer">
          <span class="block text-sm font-medium text-gray-700">Status</span>
          <input type="hidden" name="ativo" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $usuario['Usuario']['ativo'] ? 'checked' : '' ?> name="ativo">
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        </label>

        <div class="w-full flex flex-col">
          <label for="usuario-editar-nivel" class="block text-sm font-medium text-gray-700">Nível de acesso</label>
          <select id="usuario-editar-nivel" name="nivel" class="<?php echo CLASSES_DASH_INPUT; ?>" required>
            <?php foreach ($nivelAcesso as $chave => $linha) : ?>
              <option value="<?php echo $chave; ?>" <?php echo $chave == $usuario['Usuario']['nivel'] ? 'selected' : ''; ?>>
                <?php echo $linha; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <?php if ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE) { ?>
          <input type="hidden" name="padrao" value="<?php echo $usuario['Usuario']['padrao']; ?>">
        <?php } ?>
      </div>
    </div>
    <div class="w-full">
      <label for="usuario-editar-nome" class="block text-sm font-medium text-gray-700">Nome</label>
      <input type="text" id="usuario-editar-nome" name="nome" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $usuario['Usuario']['nome']; ?>" autofocus>
    </div>
    <div class="w-full">
      <label for="usuario-editar-email" class="block text-sm font-medium text-gray-700">Email</label>
      <input type="text" id="usuario-editar-email" name="email" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $usuario['Usuario']['email']; ?>" required>
    </div>
    <div>
      <div class="mt-4 text-red-800 text-xs">*Preencha os campos abaixo apenas para alterar a senha</div>
      <div class="px-4 p-6 flex flex-col gap-4 border border-slate-200 bg-slate-50 rounded-md">
        <div class="flex flex-col md:flex-row gap-4">
          <div class="w-full">
            <label for="usuario-editar-senha-atual" class="block text-sm font-medium text-gray-700">Senha atual</label>
            <input type="password" id="usuario-editar-senha-atual" name="senha_atual" class="<?php echo CLASSES_DASH_INPUT; ?>" value="" autocomplete="off">
          </div>
          <div class="w-full">
            <label for="usuario-editar-senha" class="block text-sm font-medium text-gray-700">Nova senha</label>
            <input type="password" id="usuario-editar-senha" name="senha" class="<?php echo CLASSES_DASH_INPUT; ?>" value="" autocomplete="off">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="w-full flex gap-4 justify-between">
    <div class="flex gap-2">
      <?php
      $urlVoltar = '/dashboard/' . $this->usuarioLogado['empresaId'] . '/usuarios';

      if ($this->usuarioLogado['nivel'] == USUARIO_COMUM) {
        $urlVoltar = '/dashboard/' . $this->usuarioLogado['empresaId'] . '/artigos';
      }
      ?>
      <a href="<?php echo baseUrl($urlVoltar); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
      <button type="submit" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?>">Gravar</button>
    </div>
  </div>
</form>