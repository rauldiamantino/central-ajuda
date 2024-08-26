<header class="border-b border-slate-200 px-4 fixed top-0 w-screen bg-white z-20">
  <nav class="bg-white flex justify-between">
    <button class="md:hidden btn-dashboard-menu-lateral">
      <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="current">
        <path d="M120-240v-60h720v60H120Zm0-210v-60h720v60H120Zm0-210v-60h720v60H120Z"/>
      </svg>
    </button>
    <div class="p-4 flex items-center justify-between">
      <a href="/dashboard/artigos"><img src="/public/img/luminaOn.png" alt="luminaOn" class="w-40"></a>
    </div>

  <div class="fixed bottom-0 right-0 py-1 px-2 md:px-6 flex items-center gap-2 text-xs font-extralight bg-green-800 text-white rounded-t-lg">
    <?php if (isset($_SESSION['usuario']['email'])) { ?>
      <?php echo $_SESSION['usuario']['email'] . ' - #' .  $_SESSION['usuario']['id']?>
    <?php } ?>
  </div>
  </nav>
</header>