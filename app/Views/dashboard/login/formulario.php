<form class="space-y-6" action="/login" method="POST">
  <div>
    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
    <input id="email" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-900 sm:text-sm sm:leading-6">
  </div>

  <div>
    <div class="flex items-center justify-between">
      <label for="senha" class="block text-sm font-medium leading-6 text-gray-900">Senha</label>
      <div class="text-sm">
        <!-- <a href="#" class="font-semibold text-blue-900 hover:text-blue-500">Esqueceu sua senha?</a> -->
      </div>
    </div>
    <input id="senha" name="senha" type="password" autocomplete="current-senha" required class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-900 sm:text-sm sm:leading-6">
  </div>

  <div>
    <button type="submit" class="flex w-full justify-center rounded-md bg-blue-900 px-3 p-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Entrar</button>
  </div>
</form>