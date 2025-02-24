<?php
$classeLogin = 'text-gray-700 hover:border-slate-200';
$styleLogin = '';

if (isset($inicio) and (int) Helper::ajuste('publico_inicio_topo_transparente') == ATIVO and (int) Helper::ajuste('publico_inicio_cor_fundo') == ATIVO) {
  $classeLogin = 'text-white';
}

if (isset($inicio) and (int) Helper::ajuste('publico_inicio_topo_transparente') == ATIVO and Helper::ajuste('publico_inicio_foto_desktop')) {
  $classeLogin = '';
  $styleLogin = 'style="color: ' . Helper::ajuste('publico_inicio_texto_cor_desktop') . ';"';
}
?>

<nav class="w-full">
  <ul class="flex justify-end h-full gap-2">

    <?php if ($urlSite) { ?>
      <li class="flex items-center justify-center hover:opacity-95">
        <a href="<?php echo $urlSite ?>" target="_blank" class="px-4 py-2 rounded-lg pers-publico-botao template-cor-<?php echo Helper::ajuste('publico_cor_primaria'); ?>">Website</a>
      </li>
    <?php } ?>

    <li class="flex items-center justify-center"><a href="/dashboard/login" target="_blank" class="border border-transparent <?php echo $classeLogin; ?> px-4 py-2 rounded-lg menu-login-desktop" <?php echo $styleLogin; ?>>Login</a></li>
  </ul>
</nav>