<?php
$nivelAcesso = [
  // 0 => 'Suporte',
  2 => 'Acesso restrito',
  1 => 'Acesso total',
];

$tipoUsuario = [
  'suporte' => 0,
  'padrao' => 1,
  'comum' => 2,
];
?>

<form method="POST" action="/usuario" class="border border-slate-200 w-full min-w-96 flex flex-col gap-4 p-4 rounded-lg shadow">
  <div class="w-full flex flex-col gap-4">
    <div class="flex gap-10">
      <div class="w-full flex gap-4">
        <label class="flex flex-col items-start gap-1 cursor-pointer">
          <span class="block text-sm font-medium text-gray-700">Status</span>
          <input type="hidden" name="ativo" value="0">
          <input type="hidden" name="padrao" value="<?php echo $tipoUsuario['comum']; ?>">
          <input type="checkbox" value="1" class="sr-only peer" name="ativo" checked>
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        </label>

        <div class="w-full flex flex-col">
          <label for="usuario-editar-nivel" class="block text-sm font-medium text-gray-700">Nível de acesso</label>
          <select id="usuario-editar-nivel" name="nivel" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required>
            <?php foreach ($nivelAcesso as $chave => $linha) : ?>
              <option value="<?php echo $chave; ?>">
                <?php echo $linha; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <div class="w-full">
      <label for="usuario-editar-nome" class="block text-sm font-medium text-gray-700">Nome</label>
      <input type="text" id="usuario-editar-nome" name="nome" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="" autofocus>
    </div>
    <div class="w-full">
      <label for="usuario-editar-email" class="block text-sm font-medium text-gray-700">Email</label>
      <input type="text" id="usuario-editar-email" name="email" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="" required autocomplete="off">
    </div>
    <div class="w-full">
      <label for="usuario-editar-senha" class="block text-sm font-medium text-gray-700">Senha</label>
      <input type="password" id="usuario-editar-senha" name="senha" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required autocomplete="off">
    </div>
  </div>
  <div class="flex gap-4">
    <a href="/<?php echo $this->buscarUsuarioLogado('subdominio') ?>/dashboard/usuarios" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-xs text-gray-700 rounded-lg">Cancelar</a>
    <button type="submit" class="flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg">Gravar</button>
  </div>
</form>