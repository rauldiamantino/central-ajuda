<header class="px-4 xl:px-0 sticky z-10 top-0 border-b border-gray-100 w-full bg-white">
  <nav class="mx-auto py-4 w-full max-w-[1140px] flex justify-between items-center bg-white">
    <a href="<?php echo baseUrl('/'); ?>" class="min-w-28 block justify-center flex items-center">
      <img src="<?php echo baseUrl('/img/360help-branco.svg')?>" class="w-28 h-auto">
    </a>

    <div class="w-max flex items-center gap-5">
      <button type="button" class="text-gray-800 w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-gray-100 rounded-lg duration-100 font-semibold" onclick="window.open('/login')">Login</button>
      <button type="button" class="w-max whitespace-nowrap hidden md:flex items-center justify-center py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
    </div>
  </nav>
</header>