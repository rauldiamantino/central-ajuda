<?php
if (! isset($inicio)) {
  return;
}
?>

<?php if ((int) Helper::ajuste('publico_inicio_arredondamento') == 1) { ?>
  <svg class="absolute -top-[60px] md:-top-[99px] left-0 w-full h-[70px] md:h-[100px]" viewBox="0 0 1440 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
    <path fill="white" d="M0,0 Q720,100 1440,0 V100 H0 Z"></path>
  </svg>
<?php } ?>

<?php if ((int) Helper::ajuste('publico_inicio_arredondamento') == 2) { ?>
  <svg class="absolute -top-[60px] md:-top-[120px] left-0 w-full h-[60px] md:h-[120px]" viewBox="0 0 1440 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
    <path fill="white" d="M0,100 Q720,0 1440,100 V100 H0 Z"></path>
  </svg>
<?php } ?>