<form class="space-y-6" action="/cadastro" method="POST">
  <div>
    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
    <input id="email" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-900 sm:text-sm sm:leading-6">
  </div>

  <div>
    <label for="subdominio" class="block text-sm font-medium leading-6 text-gray-900">Como as pessoas vão te encontrar</label>
    <input id="subdominio" name="subdominio" type="subdominio" autocomplete="subdominio" required class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-900 sm:text-sm sm:leading-6" placeholder="subdominio">
    <div class="pt-1 text-xs">Exemplo: <span class="text-sm font-bold text-red-800 underline">subdominio</span>.luminaon.com.br</div>
  </div>

  <div>
    <label for="senha" class="block text-sm font-medium leading-6 text-gray-900">Senha</label>
    <input id="senha" name="senha" type="password" autocomplete="current-senha" required class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-900 sm:text-sm sm:leading-6">
  </div>

  <div>
    <label for="confirmar_senha" class="block text-sm font-medium leading-6 text-gray-900">Confirmar senha</label>
    <input id="confirmar_senha" name="confirmar_senha" type="password" autocomplete="current-senha" required class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-900 sm:text-sm sm:leading-6">
  </div>

  <div>
    <button type="submit" class="flex w-full justify-center rounded-md bg-blue-900 px-3 p-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cadastrar</button>
  </div>

  <div class="border border-slate-200 p-5 rounded-md w-full flex flex-col items-center justify-center">
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