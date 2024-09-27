<header class="border-b border-slate-200 px-4 fixed top-0 w-screen flex items-center justify-between bg-white z-20">
  <nav class="bg-white flex justify-between">
    <button class="lg:hidden btn-dashboard-menu-lateral">
      <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="current">
        <path d="M120-240v-60h720v60H120Zm0-210v-60h720v60H120Zm0-210v-60h720v60H120Z"/>
      </svg>
    </button>
    <div class="p-4 flex items-center justify-between">
      <a href="/<?php echo $this->usuarioLogado['subdominio'] ?>" target="_blank"><img src="/public/img/luminaOn.png" alt="luminaOn" class="w-40"></a>
    </div>
  </nav>
</header>