<form class="w-full flex flex-col md:flex-row md:gap-12 space-y-6" action="<?php echo baseUrl('/cadastro'); ?>" method="POST">
  <div class="w-full flex flex-col gap-6">
    <div class="flex flex-col gap-2">
      <label for="email" class="text-sm font-medium text-gray-800">Email</label>
      <input id="email" name="email" type="email" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
    </div>

    <div class="flex flex-col gap-2">
      <label for="subdominio" class="text-sm font-medium text-gray-800">Escolha seu subdomínio</label>
      <input id="subdominio" name="subdominio" type="text" required class="<?php echo CLASSES_LOGIN_INPUT; ?>" placeholder="nome-empresa">
      <div class="pt-1 text-xs text-gray-500">Exemplo: 360help.com.br/<span class="font-bold text-blue-800">nome-empresa</span></div>
    </div>

    <div class="flex flex-col gap-2">
      <label for="senha" class="text-sm font-medium text-gray-800">Senha</label>
      <input id="senha" name="senha" type="password" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
    </div>

    <div class="flex flex-col gap-2">
      <label for="confirmar_senha" class="text-sm font-medium text-gray-800">Confirmar senha</label>
      <input id="confirmar_senha" name="confirmar_senha" type="password" required class="<?php echo CLASSES_LOGIN_INPUT; ?>">
    </div>

    <div class="text-xs text-gray-500 mt-0">
      <span>A senha precisa ter:</span>
      <ul class="list-disc ml-5 space-y-1">
        <li>8 caracteres</li>
        <li>1 letra maiúscula e minúscula</li>
        <li>1 número e 1 caractere especial (!, @, #)</li>
      </ul>
    </div>
  </div>
  <div class="w-full flex flex-col gap-6">
    <div class="border border-gray-200 rounded-lg p-6 space-y-4">
      <h3 class="text-lg font-semibold text-center text-gray-800">Benefícios da nossa Central de Ajuda</h3>
      <ul class="list-disc ml-5 space-y-2 text-sm text-gray-700">
        <li>Passo a passo em vídeos, imagens e textos para enriquecer o suporte</li>
        <li>Encontre conteúdos facilmente na dashboard</li>
        <li>Interface simples e ágil para sua equipe e clientes</li>
        <li>Gestão ilimitada de artigos com controle de acesso para sua equipe</li>
        <li>Organização de artigos em categorias para navegação rápida</li>
      </ul>
    </div>
    <div class="w-full">
      <h3 class="text-lg font-semibold text-center text-gray-800">Escolha o plano ideal para sua empresa</h3>
    </div>
    <div class="w-full flex gap-4">
      <div class="flex-1">
        <input type="radio" id="plano-mensal" name="plano_nome" class="hidden peer" checked value="Mensal">
        <label for="plano-mensal" class="block p-5 rounded-lg text-center cursor-pointer border border-gray-200 transition duration-200 ease-in-out peer-checked:ring-2 peer-checked:ring-blue-900 peer-checked:ring-offset-2 peer-checked:ring-offset-gray-200">
          <span class="text-lg font-semibold text-gray-800">Mensal</span>
          <p class="text-2xl md:text-3xl mt-1 text-blue-800">R$ 99</p>
          <p class="text-xs mt-1 text-gray-600">por mês</p>
        </label>
      </div>
      <div class="flex-1">
        <input type="radio" id="plano-anual" name="plano_nome" class="hidden peer" value="Anual">
        <label for="plano-anual" class="block p-5 rounded-lg text-center cursor-pointer border border-gray-200 transition duration-200 ease-in-out peer-checked:ring-2 peer-checked:ring-blue-900 peer-checked:ring-offset-2 peer-checked:ring-offset-gray-200">
          <span class="text-lg font-semibold text-gray-800">Anual</span>
          <p class="text-2xl md:text-3xl mt-1 text-blue-800">R$ 768</p>
          <p class="text-xs mt-1 text-gray-600">por ano</p>
        </label>
      </div>
    </div>
    <div class="flex justify-center">
      <button type="submit" class="<?php echo CLASSES_LOGIN_BUTTON; ?>">Assinar</button>
    </div>

    <p class="mt-6 text-center text-sm text-gray-500">
      Já possui uma conta? <a href="<?php echo baseUrl('/login'); ?>" class="font-semibold text-blue-800 hover:text-blue-600">Entrar</a>
    </p>
  </div>
</form>