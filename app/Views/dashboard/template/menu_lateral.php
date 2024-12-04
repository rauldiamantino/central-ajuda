<?php
$paginaSelecionada = $paginaMenuLateral ?? '';
$classeRestrito = $this->usuarioLogado['nivel'] == USUARIO_RESTRITO ? 'text-gray-500' : ''
?>

<asside class="pb-20 md:pb-0 fixed inset-y-0 left-0 z-20 transform -translate-x-full transition-transform duration-100 xl:translate-x-0 border-r border-slate-200 flex flex-col justify-start bg-gray-800 w-full md:w-96 lg:w-72 min-h-screen overflow-y-auto overflow-estilo dashboard-menu-lateral">
  <div class="px-4 py-10 flex flex-col gap-6 w-full w-full text-gray-200 text-sm group">
    <div class="w-full flex justify-between xl:justify-center gap-8 items-center text-gray-400">
      <a href="<?php echo baseUrl('/login'); ?>" class="w-max justify-start flex items-center">
        <img src="<?php echo baseUrl('/img/360help-preto.svg')?>" class="w-36">
      </a>

      <button class="h-max w-max xl:hidden btn-dashboard-menu-lateral-fechar">
        <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="currentColor"><path d="m287-446.67 240 240L480-160 160-480l320-320 47 46.67-240 240h513v66.66H287Z"/></svg>
      </button>
    </div>
    <div class="py-5 border-y border-slate-700/70 w-full flex justify-between items-center gap-3 rounded-lg">
      <div class="w-2/3 flex gap-3 items-center">
        <div class="w-max h-max p-2 bg-gray-50 rounded-full text-gray-500 outline-slate-700 outline outline-offset-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"viewBox="0 0 16 16">
            <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
          </svg>
        </div>
        <div class="w-full pr-4 flex flex-col gap-1">
          <div class="w-full truncate"><?php echo $this->usuarioLogado['nome'] ?></div>
          <div class="w-full text-xs truncate"><?php echo $this->usuarioLogado['email'] ?></div>
        </div>
      </div>

      <div class="flex relative">
        <button type="button" class="p-3 text-gray-500 bg-gray-700/20 hover:bg-gray-400/20 rounded-lg cursor-pointer" onclick="document.querySelector('.menu-lateral-usuario').classList.toggle('hidden')">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" stroke="currentColor" stroke-width="1" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
          </svg>
        </button>
        <ul class="border border-slate-300 lg:mx-10 absolute top-12 right-0 lg:-right-10 flex flex-col justify-center bg-white text-gray-600 rounded-md shadow hidden menu-lateral-usuario">
          <li class="px-4 py-3">
            <button type="button" onclick="window.location.href='<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuario/editar/' . $this->usuarioLogado['id']); ?>'" class="flex gap-2 items-center hover:text-gray-950">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
              </svg>
              <span class="whitespace-nowrap">Meu usuário</span>
            </button>
          </li>
          <li class="px-4 py-3">
            <button type="button" onclick="window.open('/<?php echo $this->usuarioLogado['subdominio']; ?>')" class="flex gap-3 items-center hover:text-gray-950">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16"><path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/></svg>
              <span class="whitespace-nowrap">Ver central</span>
            </button>
          </li>
          <li class="px-4 py-3">
            <button type="button" onclick="window.location.href='<?php echo baseUrl('/logout'); ?>'" class="w-max flex gap-3 items-center text-red-800 hover:text-red-950">
              <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" fill="currentColor"><path d="M186.67-120q-27 0-46.84-19.83Q120-159.67 120-186.67v-586.66q0-27 19.83-46.84Q159.67-840 186.67-840h292.66v66.67H186.67v586.66h292.66V-120H186.67Zm470.66-176.67-47-48 102-102H360v-66.66h351l-102-102 47-48 184 184-182.67 182.66Z"/></svg>
              <span class="whitespace-nowrap">Sair</span>
            </button type="button">
          </li>
        </ul>

        <ul class="border border-slate-300 lg:mx-10 absolute top-10 right-5 xl:-right-6 flex flex-col justify-center bg-white text-gray-600 rounded-md shadow hidden menu-lateral-usuario">
          <li class="px-4 py-3">
            <button type="button" onclick="window.location.href='<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuario/editar/' . $this->usuarioLogado['id']); ?>'" class="flex gap-2 items-center hover:text-gray-950">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
              </svg>
              Meu usuário
            </button>
          </li>
          <li class="px-4 py-3">
            <button type="button" onclick="window.open('/<?php echo $this->usuarioLogado['subdominio']; ?>')" class="flex gap-3 items-center hover:text-gray-950">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16"><path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/></svg>
              Ver central
            </button>
          </li>
          <li class="px-4 py-3">
            <button type="button" onclick="window.location.href='<?php echo baseUrl('/logout'); ?>'" class="w-max flex gap-3 items-center text-red-800 hover:text-red-950">
              <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" fill="currentColor"><path d="M186.67-120q-27 0-46.84-19.83Q120-159.67 120-186.67v-586.66q0-27 19.83-46.84Q159.67-840 186.67-840h292.66v66.67H186.67v586.66h292.66V-120H186.67Zm470.66-176.67-47-48 102-102H360v-66.66h351l-102-102 47-48 184 184-182.67 182.66Z"/></svg>
              Sair
            </button type="button">
          </li>
        </ul>
      </div>
    </div>
  </div>
  <ul class="flex flex-col gap-2 text-gray-200 px-4 pb-4">
    <h3 class="px-6 pb-2 text-xs font-extralight text-slate-300">MENU</h3>
    <li class="px-4 hover:bg-gray-700 <?php echo $paginaSelecionada == 'dashboard' ? 'bg-gray-700' : ''; ?> rounded-lg cursor-pointer flex justify-between group">
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard'); ?>" class="w-full p-2">
        <div class="flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
          </svg>
          <span>Início</span>
        </div>
      </a>
    </li>
    <li class="px-4 hover:bg-gray-700 <?php echo $paginaSelecionada == 'categorias' ? 'bg-gray-700' : ''; ?> rounded-lg cursor-pointer flex justify-between group">
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categorias'); ?>" class="w-full p-2">
        <div class="flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="" viewBox="0 0 16 16">
            <path d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z"/>
            <path d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z"/>
          </svg>
          <span>Categorias</span>
        </div>
      </a>
    </li>
    <li class="px-4 hover:bg-gray-700 <?php echo $paginaSelecionada == 'artigos' ? 'bg-gray-700' : ''; ?> rounded-lg cursor-pointer flex justify-between group">
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos'); ?>" class="w-full p-2">
        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16"><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z"/></svg>
          <span>Artigos</span>
        </div>
      </a>
    </li>
    <li class="px-4 hover:bg-gray-700 <?php echo $paginaSelecionada == 'ajustes' ? 'bg-gray-700' : ''; ?> rounded-lg cursor-pointer flex justify-between group">
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/ajustes'); ?>" class="w-full p-2">
        <div class="flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="" viewBox="0 0 16 16">
            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
          </svg>
          <span>Ajustes</span>
        </div>
      </a>
    </li>
    <li class="px-4 hover:bg-gray-700 <?php echo $paginaSelecionada == 'empresa' ? 'bg-gray-700' : ''; ?> rounded-lg cursor-pointer flex justify-between group">
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar'); ?>" class="w-full p-2">
        <div class="w-full flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="" viewBox="0 0 16 16">
            <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1z"/>
            <path d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.386 1.46c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
          </svg>
          <div class="w-full flex gap-2 justify-between items-center">
            <span class="w-full flex justify-between items-center gap-2">
              Empresa
            </span>
          </div>
        </div>
      </a>
    </li>
    <li class="<?php echo $classeRestrito; ?> px-4 hover:bg-gray-700 <?php echo $paginaSelecionada == 'usuarios' ? 'bg-gray-700' : ''; ?> rounded-lg cursor-pointer flex justify-between group">
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuarios'); ?>" class="w-full p-2">
        <div class="flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="" viewBox="0 0 16 16">
            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
          </svg>
          <span class="w-full flex justify-between items-center gap-2">
            Usuários
            <?php if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO) { ?>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
              </svg>
            <?php } ?>
          </span>
        </div>
      </a>
    </li>
    <li class="<?php echo $classeRestrito; ?> px-4 hover:bg-gray-700 <?php echo $paginaSelecionada == 'assinatura' ? 'bg-gray-700' : ''; ?> rounded-lg cursor-pointer flex justify-between group">
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/assinatura/editar'); ?>" class="w-full p-2">
        <div class="w-full flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
            <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
          </svg>
          <div class="w-full flex gap-2 justify-between items-center">
            <span class="w-full flex justify-between items-center gap-2">
              Assinatura
              <?php if ($this->usuarioLogado['nivel'] == USUARIO_RESTRITO) { ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                  <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
                </svg>
              <?php } ?>
            </span>
          </div>
        </div>
      </a>
    </li>
    <?php

    if (! isset($this->usuarioLogado['assinaturaStatus']) or (int) $this->usuarioLogado['assinaturaStatus'] == INATIVO) {
      require_once 'teste-gratis.php';
    }
    ?>
    <?php if ($this->usuarioLogado['id'] > 0 and $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
      <h3 class="mt-4 px-6 py-2 text-xs font-extralight text-slate-300">SUPORTE</h3>
      <li class="px-4 hover:bg-gray-700 rounded-lg cursor-pointer flex justify-between group">
        <button type="button" onclick="window.location.href='<?php echo baseUrl('/cache/limpar'); ?>'" class="w-full p-2">
          <div class="flex justify-start items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
              <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9"/>
              <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z"/>
            </svg>
            <span>Reset Cache</span>
          </div>
        </button>
      </li>
      <li class="px-4 hover:bg-gray-700 rounded-lg cursor-pointer flex justify-between group">
        <?php $debugAtivo = $this->sessaoUsuario->buscar('debugAtivo'); ?>

        <button type="button" onclick="window.location.href='<?php echo strtok($_SERVER['REQUEST_URI'], '?') . ($debugAtivo ? '?debug=false' : '?debug=true'); ?>'" class="w-full p-2">
          <div class="flex justify-start items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-eyeglasses" viewBox="0 0 16 16">
              <path d="M4 6a2 2 0 1 1 0 4 2 2 0 0 1 0-4m2.625.547a3 3 0 0 0-5.584.953H.5a.5.5 0 0 0 0 1h.541A3 3 0 0 0 7 8a1 1 0 0 1 2 0 3 3 0 0 0 5.959.5h.541a.5.5 0 0 0 0-1h-.541a3 3 0 0 0-5.584-.953A2 2 0 0 0 8 6c-.532 0-1.016.208-1.375.547M14 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0"/>
            </svg>
            <div class="flex gap-2 items-center justify-between w-full">
              <span>Modo debug</span>
              <?php if ($debugAtivo) { ?>
                <span class="text-green-900">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="8"/>
                  </svg>
                </span>
              <?php } ?>
            </div>
          </div>
        </button>
      </li>
      <li class="px-4 hover:bg-gray-700 <?php echo $paginaSelecionada == 'login' ? 'bg-gray-700' : ''; ?> rounded-lg cursor-pointer flex justify-between group">
        <button type="button" onclick="window.location.href='<?php echo baseUrl('/login/suporte'); ?>'" class="w-full p-2">
          <div class="flex justify-start items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5m14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5"/>
            </svg>
            <span>Trocar empresa</span>
          </div>
        </button>
      </li>
    <?php } ?>
    <?php $diasGratis = 10 ?>
  </ul>
</asside>