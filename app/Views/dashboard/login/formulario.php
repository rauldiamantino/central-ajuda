<form class="space-y-4" action="<?php echo baseUrl('/login'); ?>" method="POST">
  <div class="flex flex-col gap-2">
    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
    <input id="email" name="email" type="email" autocomplete="off" class="<?php echo CLASSES_LOGIN_INPUT; ?>" placeholder="Digite seu e-mail">
  </div>

  <div class="flex flex-col gap-2">
    <label for="senha" class="block text-sm font-medium leading-6 text-gray-900">Senha</label>
    <input id="senha" name="senha" type="password" autocomplete="off" class="<?php echo CLASSES_LOGIN_INPUT; ?>" placeholder="Digite sua senha">
  </div>

  <div>
    <button type="submit" class="<?php echo CLASSES_LOGIN_BUTTON; ?>">Entrar</button>
  </div>
</form>