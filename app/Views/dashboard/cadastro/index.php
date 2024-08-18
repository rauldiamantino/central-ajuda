<div class="w-full h-full shadow rounded-lg">
  <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img class="mx-auto h-10 w-auto" src="/img/luminaOn.png" alt="">
      <!-- <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your account</h2> -->
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" action="/cadastro" method="POST">
        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
          <input id="email" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-900 sm:text-sm sm:leading-6">
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
      </form>

      <p class="mt-10 text-center text-sm text-gray-500">
        Já possui cadastro?
        <a href="/login" class="font-semibold leading-6 text-blue-900 hover:text-blue-500">Clique aqui</a>
      </p>
    </div>
  </div>
</div>