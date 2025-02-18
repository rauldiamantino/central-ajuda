<?php
$classesTopoFixo = '';
$classesTopoCor = 'bg-white text-black';
$classesTopoBorda = 'border-b-slate-200/60';
$classesTopoLogo = '';

if ((int) Helper::ajuste('publico_topo_fixo') == ATIVO) {
  $classesTopoFixo = 'sticky';
}

if (isset($inicio)) {
  $classesTopoFixo = 'absolute';
}

if ((int) Helper::ajuste('publico_inicio_topo_cor') == ATIVO and (isset($inicio))) {
  $classesTopoCor = 'bg-transparent text-white';
}

if ((int) Helper::ajuste('publico_inicio_logo_cor_inverter') == ATIVO and (isset($inicio))) {
  $classesTopoLogo = 'filter invert grayscale brightness-0';
}

if ((int) Helper::ajuste('publico_topo_borda_inicio') == INATIVO and isset($inicio)) {
  $classesTopoBorda = 'border-transparent';
}

if ((int) Helper::ajuste('publico_topo_borda_demais') == INATIVO and ! isset($inicio)) {
  $classesTopoBorda = 'border-transparent';
}
?>

<?php // Preloads ?>
<?php $logo = $this->renderImagem($logo); ?>
<link rel="preload" href="<?php echo $logo; ?>" as="image" type="image/png">

<header class="border-b <?php echo $classesTopoBorda . ' ' . $classesTopoFixo. ' ' . $classesTopoCor ?> z-20 top-0 w-full h-24 lg:mx-auto flex justify-center items-center <?php echo isset($inicio) ? 'py-12' : 'py-20'; ?> md:py-4 px-4 xl:px-0 md:gap-10 transition duration-300 topo-publico">

  <div class="w-full <?php echo $classesLarguraGeral ?> flex items-center gap-4 justify-center">

      <div class="w-full flex flex-col md:flex-row gap-5 justify-between md:items-center">
        <div class="w-full md:w-[220px] flex flex-shrink-0 <?php echo (int) Helper::ajuste('publico_menu_lateral') == ATIVO ? 'justify-between' : 'justify-center'; ?> md:justify-start items-center gap-4 inverter transition-invert duration-300">
          <a href="/"><img src="<?php echo $logo; ?>" alt="<?php echo $empresaNome; ?>" class="w-full max-h-14 <?php echo $classesTopoLogo; ?>"></a>

          <?php if ($menuLateral and (int) Helper::ajuste('publico_menu_lateral') == ATIVO) { ?>
            <button class="w-max md:hidden btn-publico-menu-lateral <?php echo $classesTopoLogo; ?>">
              <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="current">
                <path d="M120-240v-60h720v60H120Zm0-210v-60h720v60H120Zm0-210v-60h720v60H120Z"/>
              </svg>
            </button>
          <?php } ?>
        </div>

        <div class="<?php echo isset($inicio) ? 'hidden' : 'block' ?> w-full">
          <?php require_once 'formulario-busca.php' ?>
        </div>

        <div class="<?php echo ! isset($inicio) ? 'hidden ' : '' ?> hidden md:block w-max">
          <?php require 'menu_login.php' ?>
        </div>
      </div>
  </div>
</header>