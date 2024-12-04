<div class="flex xl:hidden">
  <button type="button" class="outline-none xl:mx-5 w-max flex justify-end items-center gap-4 btn-menu-topo-usuario">
    <div class="border border-slate-200 w-12 h-12 bg-gray-50 rounded-full text-gray-500">
      <img src="<?php echo $this->renderImagem($this->usuarioLogado['foto']); ?>" class="p-1 rounded-full" alt="foto-perfil-<?php echo $this->usuarioLogado['id']; ?>" onerror="this.onerror=null; this.src='/img/sem-imagem-perfil.svg';">
    </div>

    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="perfil-usuario-baixo">
      <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
    </svg>

    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="hidden perfil-usuario-cima">
      <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708z"/>
    </svg>
  </button>
  <ul class="border border-slate-300 lg:mx-10 absolute top-20 right-0 flex flex-col justify-center bg-white text-gray-600 rounded-md shadow hidden menu-topo-usuario">
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