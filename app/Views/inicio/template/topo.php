<header id="header" class="px-4 xl:px-0 sticky z-10 top-0 border-b border-gray-100 w-full bg-white transition-colors duration-350">
  <nav class="mx-auto py-4 w-full max-w-[1140px] flex justify-between items-center">
    <a href="<?php echo baseUrl('/'); ?>" class="min-w-28 block justify-center flex items-center" id="logo">
      <img src="<?php echo baseUrl('/img/360help-branco.svg')?>" class="w-28 h-auto" id="logo-img">
    </a>
    <div class="w-max flex items-center gap-5">
      <button id="login-btn" type="button" class="text-gray-800 w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-gray-100 rounded-lg duration-100 font-semibold" onclick="window.open('/login')">Login</button>
      <button type="button" class="w-max whitespace-nowrap hidden md:flex items-center justify-center py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
    </div>
  </nav>
</header>

<script>
  window.addEventListener('scroll', function() {
    const header = document.querySelector('#header');
    const logo = document.querySelector('#logo-img');
    const botaoLogin = document.querySelector('#login-btn');

    if (window.scrollY > 0) {
      header.classList.add('bg-gray-800', 'text-white');
      header.classList.remove('border-gray-100');
      header.classList.add('border-gray-800');
      botaoLogin.classList.remove('text-gray-800', 'bg-gray-100');
      botaoLogin.classList.add('text-white', 'bg-gray-700/50');
      logo.classList.add('invert');
    }
    else {
      header.classList.remove('bg-gray-800', 'text-white');
      header.classList.remove('border-gray-800');
      header.classList.add('border-gray-100');
      botaoLogin.classList.add('text-gray-800', 'bg-gray-100');
      botaoLogin.classList.remove('text-white', 'bg-gray-700/50');
      logo.classList.remove('invert'); // Remove o filtro invertido
    }
  });
</script>