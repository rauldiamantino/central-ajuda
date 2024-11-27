<header class="border-b border-gray-200 w-full">
  <nav class="mx-auto py-4 w-full max-w-[1200px] flex justify-between items-center bg-gray-100">
    <div class="w-max justify-center flex items-center">
      <img src="<?php echo baseUrl('/img/360help-branco.svg')?>" class="w-28">
    </div>

    <div class="w-max hidden md:flex items-center gap-5">
      <button type="button" class="text-gray-800 w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-gray-200 rounded-lg duration-100 font-semibold" onclick="window.open('/login')">Login</button>
      <button type="button" class="w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-800 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
    </div>
  </nav>
</header>