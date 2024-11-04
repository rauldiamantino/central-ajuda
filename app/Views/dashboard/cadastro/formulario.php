<form class="space-y-6" action="<?php echo baseUrl('/cadastro'); ?>" method="POST">
  <div class="flex flex-col gap-2">
    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
    <input id="email" name="email" type="email" autocomplete="off" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
  </div>

  <div class="flex flex-col gap-2">
    <label for="subdominio" class="block text-sm font-medium leading-6 text-gray-900">Como as pessoas vão te encontrar</label>
    <input id="subdominio" name="subdominio" type="subdominio" autocomplete="off" required class="<?php echo CLASSES_LOGIN_INPUT; ?>" placeholder="nome-empresa">
    <div class="pt-1 text-xs">Exemplo: 360help.com.br/<span class="text-sm font-bold text-red-800 underline">nome-empresa</span></div>
  </div>

  <div class="flex flex-col gap-2">
    <label for="senha" class="block text-sm font-medium leading-6 text-gray-900">Senha</label>
    <input id="senha" name="senha" type="password" autocomplete="off" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
  </div>

  <div class="flex flex-col gap-2">
    <label for="confirmar_senha" class="block text-sm font-medium leading-6 text-gray-900">Confirmar senha</label>
    <input id="confirmar_senha" name="confirmar_senha" type="password" autocomplete="off" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
  </div>

  <div class="w-full flex flex-col items-center justify-center">
    <div class="w-full flex gap-2 sm:gap-4 justify-between">
      <div class="w-full">
        <input type="radio" id="cadastro-plano-mensal" name="plano_nome" class="hidden peer" checked value="mensal">
        <label for="cadastro-plano-mensal" class="w-full flex flex-col items-center text-sm font-medium leading-6 text-gray-900 p-5 rounded-md border border-gray-200 transition duration-200 ease-in-out peer-checked:ring-2 peer-checked:ring-blue-900 peer-checked:ring-offset-2 peer-checked:ring-offset-gray-200">
          Mensal
          <div class="w-full flex flex-col sm:flex-row gap-2 justify-center items-center">
            <h2 class="text-3xl whitespace-nowrap">R$ 99</h2>
            <span class="leading-4 text-xs">
              por<br class="hidden sm:block">
              mês
            </span>
          </div>
        </label>
      </div>
      <div class="w-full">
        <input type="radio" id="cadastro-plano-anual" name="plano_nome" class="hidden peer" value="anual">
        <label for="cadastro-plano-anual" class="w-full flex flex-col items-center text-sm font-medium leading-6 text-gray-900 p-5 rounded-md border border-gray-200 transition duration-200 ease-in-out peer-checked:ring-2 peer-checked:ring-blue-900 peer-checked:ring-offset-2 peer-checked:ring-offset-gray-200">
          Anual
          <div class="w-full flex flex-col sm:flex-row gap-2 justify-center items-center">
            <h3 class="text-3xl whitespace-nowrap">R$ 64</h3>
            <span class="leading-4 text-xs">
              por<br class="hidden sm:block">
              mês
            </span>
          </div>
        </label>
      </div>
    </div>
  </div>

  <div class="flex flex-col gap-2">
    <button type="submit" class="<?php echo CLASSES_LOGIN_BUTTON; ?>">Cadastrar</button>
  </div>
</form>