<form class="space-y-6" action="/cadastro" method="POST">
  <div class="flex flex-col gap-2">
    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
    <input id="email" name="email" type="email" autocomplete="off" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
  </div>

  <div class="flex flex-col gap-2">
    <label for="subdominio" class="block text-sm font-medium leading-6 text-gray-900">Como as pessoas vão te encontrar</label>
    <input id="subdominio" name="subdominio" type="subdominio" autocomplete="off" required class="<?php echo CLASSES_LOGIN_INPUT; ?>" placeholder="subdominio">
    <div class="pt-1 text-xs">Exemplo: <span class="text-sm font-bold text-red-800 underline">subdominio</span>.360help.com.br</div>
  </div>

  <div class="flex flex-col gap-2">
    <label for="senha" class="block text-sm font-medium leading-6 text-gray-900">Senha</label>
    <input id="senha" name="senha" type="password" autocomplete="off" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
  </div>

  <div class="flex flex-col gap-2">
    <label for="confirmar_senha" class="block text-sm font-medium leading-6 text-gray-900">Confirmar senha</label>
    <input id="confirmar_senha" name="confirmar_senha" type="password" autocomplete="off" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
  </div>

  <div class="flex flex-col gap-2">
    <button type="submit" class="<?php echo CLASSES_LOGIN_BUTTON; ?>">Cadastrar</button>
  </div>

  <div class="border border-slate-300 p-5 rounded-md w-full flex flex-col items-center justify-center">
    <h3 class="font-semibold">Acesso ilimitado</h3>
    <div class="flex gap-2 items-center">
      <h2 class="text-3xl">R$ 99</h2>
      <span class="leading-4 text-xs">
        por<br>
        mês
      </span>
    </div>
  </div>
</form>