<form class="w-full" action="/cadastro" method="POST" onsubmit="evitarDuploClique(event)">
  <div class="w-full flex flex-col gap-4">
    <div class="flex flex-col gap-2">
      <label for="email" class="text-sm font-medium text-gray-800">Email do responsável</label>
      <input id="email" name="email" type="email" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
    </div>

    <div class="flex flex-col gap-2">
      <label for="subdominio" class="text-sm font-medium text-gray-800">Identificação da sua empresa</label>
      <input id="subdominio" name="subdominio" type="text" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
      <div class="pt-1 text-xs text-gray-500">Exemplo: <span class="font-bold text-blue-800">nome-empresa</span>.360help.com.br</div>
    </div>

    <div class="flex flex-col gap-2">
      <label for="senha" class="text-sm font-medium text-gray-800">Senha</label>
      <input id="senha" name="senha" type="password" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
    </div>

    <div class="flex flex-col gap-2">
      <label for="confirmar_senha" class="text-sm font-medium text-gray-800">Confirmar senha</label>
      <input id="confirmar_senha" name="confirmar_senha" type="password" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
    </div>

    <div class="text-xs text-gray-500 my-0">
      <span>A senha precisa ter:</span>
      <ul class="list-disc ml-5 flex flex-col">
        <li>8 caracteres</li>
        <li>1 letra maiúscula e minúscula</li>
        <li>1 número e 1 caractere especial (!, @, #)</li>
      </ul>
    </div>
    <div class="flex justify-center">
      <button type="submit" class="<?php echo CLASSES_LOGIN_BUTTON; ?>">Criar conta</button>
    </div>
  </div>
  <div class="mt-2 text-center text-gray-500 flex flex-col gap-4">
    <span class="text-xs">Ao clicar em "Criar conta", você declara que leu e concorda com os <a class="text-blue-600 hover:underline" href="/termos" target="_blank">Termos de Uso</a> e <a class="text-blue-600 hover:underline" href="/privacidade" target="_blank"> Política de Privacidade</a> da plataforma.</span>
    <!-- <span class="text-sm">Já possui uma conta? <a href="<?php echo baseUrl('/login'); ?>" class="font-semibold text-blue-800 hover:text-blue-600">Entrar</a></span> -->
  </div>
</form>