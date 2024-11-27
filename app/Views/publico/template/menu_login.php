<nav class="w-full">
  <ul class="flex justify-end h-full gap-2">
    <?php if ($urlSite) { ?>
      <li class="flex items-center justify-center hover:opacity-95"><a href="<?php echo $urlSite ?>" target="_blank" class="px-4 py-2 rounded-lg pers-publico-botao template-cor-<?php echo $corPrimaria; ?>">Website</a></li>
    <?php } ?>
    <li class="flex items-center justify-center"><a href="<?php echo HOST_LOCAL ? 'http://localhost/login' : 'https://www.360help.com.br'; ?>" target="_blank" class="px-4 py-2 hover:bg-slate-100 rounded-lg">Login</a></li>
  </ul>
</nav>