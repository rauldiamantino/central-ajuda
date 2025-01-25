<div class="w-full py-4">
  <h3 class="py-3 text-lg font-semibold">O que gostaria de fazer?</h3>
  <div class="flex flex-col sm:flex-row gap-6 sm:gap-4 lg:gap-6 inicio-rapido-blocos">
    <button type="button" onclick="window.location.href='/dashboard/categorias?acao=adicionar';" class="w-full lg:w-max border border-gray-200 flex flex-col gap-4 text-left p-6 bg-white hover:bg-gray-100 rounded-xl shadow cursor-pointer">
      <span class="text-blue-900">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="" viewBox="0 0 16 16">
          <path d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z" />
          <path d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z" />
        </svg>
      </span>
      <span>Criar categoria</span>
    </button>
    <button type="button" onclick="window.location.href='/dashboard/artigos?acao=adicionar';" class="w-full lg:w-max border border-gray-200 flex flex-col gap-4 text-left p-6 bg-white hover:bg-gray-100 rounded-xl shadow cursor-pointer">
      <span class="text-blue-900">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">
          <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
        </svg>
      </span>
      <span>Criar um artigo</span>
    </button>
    <button type="button" onclick="window.location.href='/dashboard/usuarios?acao=adicionar';" class="w-full lg:w-max border border-gray-200 flex flex-col gap-4 text-left p-6 bg-white hover:bg-gray-100 rounded-xl shadow cursor-pointer">
      <span class="text-blue-900">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="" viewBox="0 0 16 16">
          <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
        </svg>
      </span>
      <span>Criar um usuário</span>
    </button>
    <button type="button" onclick="window.open('<?php echo $dominio; ?>')" class="w-full lg:w-max border border-gray-200 flex flex-col gap-4 text-left p-6 bg-white hover:bg-gray-100 rounded-xl shadow cursor-pointer">
      <span class="text-blue-900">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
          <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783" />
        </svg>
      </span>
      <span>Ver central de ajuda</span>
    </button>
  </div>
</div>