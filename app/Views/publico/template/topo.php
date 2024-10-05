<header class="border-b border-slate-300 <?php echo (int) $this->buscarAjuste('publico_topo_fixo') == 1 ? 'sticky' : '' ?> z-20 top-0 w-full flex justify-center pb-2 pt-6 md:py-8 px-4 bg-white text-black transition duration-300 topo-publico">
  <div class="w-full md:w-8/12 flex flex-col md:flex-row gap-2 justify-between">
    <div class="w-full flex justify-between md:justify-start items-center gap-4 inverter transition-invert duration-300">
      <a href="/"><img src="<?php echo $logo ?>" alt="" class="w-52"></a>
      <h2 class="text-xl font-light tracking-tighter hidden md:flex">central de ajuda</h2>

      <button class="w-max md:hidden btn-publico-menu-lateral">
        <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="current">
          <path d="M120-240v-60h720v60H120Zm0-210v-60h720v60H120Zm0-210v-60h720v60H120Z"/>
        </svg>
      </button>
    </div>
    <div class="w-full md:w-6/12 flex gap-2">
      <div class="w-full h-20 flex flex-col justify-center">
        <?php require_once 'formulario-busca.php' ?>
      </div>
    </div>
  </div>
</header>