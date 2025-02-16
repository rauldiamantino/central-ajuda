<?php
$classesTopoFixo = '';
$classesTopoBorda = 'border-transparent';

if ((int) Helper::ajuste('publico_topo_fixo') == ATIVO) {
  $classesTopoFixo = 'sticky';
}

if ((int) Helper::ajuste('publico_topo_borda') == ATIVO) {
  $classesTopoBorda = 'border-slate-200';
}
?>

<?php // Preloads ?>
<link rel="preload" href="<?php echo $this->renderImagem($logo); ?>" as="image">

<header class="border-b <?php echo $classesTopoBorda . ' ' . $classesTopoFixo; ?> z-20 top-0 w-full lg:mx-auto h-24 flex justify-center items-center <?php echo isset($inicio) ? 'py-12' : 'py-20'; ?> md:py-4 px-4 xl:px-0 md:gap-10 bg-white text-black transition duration-300 topo-publico">

  <div class="w-full <?php echo $classesLarguraGeral ?> flex items-center gap-2 justify-center">

      <div class="w-full flex flex-col md:flex-row gap-6 justify-between md:items-center">
        <div class="md:w-max flex <?php echo (int) Helper::ajuste('publico_menu_lateral') == ATIVO ? 'justify-between' : 'justify-center'; ?> md:justify-start items-center gap-4 inverter transition-invert duration-300">
          <a href="/"><img src="<?php echo $this->renderImagem($logo); ?>" alt="<?php echo $empresaNome; ?>" class="w-full max-h-14" onerror="this.onerror=null; this.src='/img/sem-imagem.svg';"></a>

          <?php if ($menuLateral and (int) Helper::ajuste('publico_menu_lateral') == ATIVO) { ?>
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

        <div class="<?php echo ! isset($inicio) ? 'hidden ' : '' ?> hidden md:block w-max">
          <?php require 'menu_login.php' ?>
        </div>
      </div>
  </div>
</header>