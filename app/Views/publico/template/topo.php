<header class="border-b border-slate-200 <?php echo (int) $this->buscarAjuste('publico_topo_fixo') == ATIVO ? 'sticky' : '' ?> z-20 top-0 w-full h-20 flex justify-center items-center <?php echo isset($inicio) ? 'py-12' : 'py-20'; ?> md:py-4 px-4 md:px-6 md:gap-10 bg-white text-black transition duration-300 topo-publico">
  <div class="w-full flex flex-col md:flex-row gap-6 justify-between">
    <div class="md:w-max flex justify-between md:justify-start items-center gap-4 inverter transition-invert duration-300">
      <a href="<?php echo baseUrl('/' . $subdominio); ?>"><img src="<?php echo $logo ?>" alt="" class="w-full max-h-14"></a>

      <?php if ($menuLateral) { ?>
        <button class="w-max md:hidden btn-publico-menu-lateral">
          <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="current">
            <path d="M120-240v-60h720v60H120Zm0-210v-60h720v60H120Zm0-210v-60h720v60H120Z"/>
          </svg>
        </button>
      <?php } ?>
    </div>

    <div class="<?php echo isset($inicio) ? 'hidden ' : '' ?>w-full lg:w-[500px] flex gap-2">
      <div class="w-full flex flex-col justify-center">
        <?php require_once 'formulario-busca.php' ?>
      </div>
    </div>
  </div>
  <div class="<?php echo ! isset($inicio) ? 'hidden ' : '' ?> hidden md:block w-max">
    <?php require 'menu_login.php' ?>
  </div>
</header>