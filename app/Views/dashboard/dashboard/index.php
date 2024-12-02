<?php
date_default_timezone_set('America/Sao_Paulo');
$horaAtual = date('H');

if ($horaAtual >= 5 and $horaAtual < 12) {
  $saudacao = 'Bom dia';
} elseif ($horaAtual >= 12 and $horaAtual < 18) {
  $saudacao = 'Boa tarde';
} else {
  $saudacao = 'Boa noite';
}
?>

<div class="relative w-full h-max flex flex-col">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex gap-2"><?php echo $saudacao; ?>! Vamos lá?</h2>
      <p class="text-gray-600">Organize ideias, inspire seu público e transforme conhecimento em ação!</p>
    </div>
  </div>

  <!-- Conteúdo da dashboard -->
  <div class="border-t border-gray-300 w-full flex flex-col py-6">

    <?php // Teste grátis expirado
    ?>
    <?php if ($this->sessaoUsuario->buscar('teste-expirado-' . $this->empresaPadraoId) == true) { ?>
      <div class="p-10 w-full lg:w-[700px] flex flex-col bg-red-600/5 rounded-xl">
        <div class="w-full text-red-800 font-semibold flex gap-2">
          <div class="w-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
          </div>
          <span class="w-full">Ops! Seu acesso foi bloqueado</span>
        </div>
        <div class="mt-2 text-sm text-gray-700 font-light">
          Para desbloquear, assine um dos planos disponíveis no menu <a href="<?php echo '/' . $this->usuarioLogado['subdominio'] . '/dashboard/empresa/editar?acao=assinar'; ?>" class="hover:underline text-blue-600">Empresa</a>.<br>
          <div class="mt-2 text-xs">
            Caso tenha alguma dúvida, entre em contato com o nosso suporte:<br>
            Email: suporte@360help.com.br<br>
            WhatsApp: (11) 93433-2319
          </div>
        </div>
      </div>
    <?php } ?>

    <?php // Início rápido
    ?>
    <div class="w-full py-4">
      <h3 class="py-3 text-lg font-semibold">O que gostaria de fazer?</h3>
      <div class="flex flex-col sm:flex-row gap-6 sm:gap-4 lg:gap-6 inicio-rapido-blocos">
        <button type="button" onclick="window.location.href='/<?php echo $this->usuarioLogado['subdominio'] . '/dashboard/categorias?acao=adicionar'; ?>';" class="w-full lg:w-max border border-gray-200 flex flex-col gap-4 text-left p-6 bg-white hover:bg-gray-100 rounded-xl shadow cursor-pointer">
          <span class="text-blue-900">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="" viewBox="0 0 16 16">
              <path d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z" />
              <path d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z" />
            </svg>
          </span>
          <span>Criar categoria</span>
        </button>
        <button type="button" onclick="window.location.href='/<?php echo $this->usuarioLogado['subdominio'] . '/dashboard/artigos?acao=adicionar'; ?>';" class="w-full lg:w-max border border-gray-200 flex flex-col gap-4 text-left p-6 bg-white hover:bg-gray-100 rounded-xl shadow cursor-pointer">
          <span class="text-blue-900">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">
              <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
            </svg>
          </span>
          <span>Criar um artigo</span>
        </button>
        <button type="button" onclick="window.location.href='/<?php echo $this->usuarioLogado['subdominio'] . '/dashboard/usuarios?acao=adicionar'; ?>';" class="w-full lg:w-max border border-gray-200 flex flex-col gap-4 text-left p-6 bg-white hover:bg-gray-100 rounded-xl shadow cursor-pointer">
          <span class="text-blue-900">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="" viewBox="0 0 16 16">
              <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
            </svg>
          </span>
          <span>Criar um usuário</span>
        </button>
        <button type="button" onclick="window.open('/<?php echo $this->usuarioLogado['subdominio']; ?>')" class="w-full lg:w-max border border-gray-200 flex flex-col gap-4 text-left p-6 bg-white hover:bg-gray-100 rounded-xl shadow cursor-pointer">
          <span class="text-blue-900">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
              <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783" />
            </svg>
          </span>
          <span>Ver central de ajuda</span>
        </button>
      </div>
    </div>

    <?php // Visão geral ?>
    <h3 class="mt-3 py-3 text-lg font-semibold">Visão geral</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-4 lg:gap-6 inicio-rapido-visao-geral">
      <div class="border border-gray-200 flex gap-4 items-center p-6 bg-white rounded-xl shadow">
        <span class="flex items-center p-3 text-red-800 bg-red-100/75 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="" viewBox="0 0 16 16">
            <path d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z" />
            <path d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z" />
          </svg>
        </span>
        <div class="flex flex-col justify-center items-start">
          <span>Categorias</span>
          <span class="text-2xl font-bold"><?php echo $totalCategorias; ?></span>
        </div>
      </div>
      <div class="border border-gray-200 flex gap-4 items-center p-6 bg-white rounded-xl shadow">
        <span class="flex items-center p-3 text-orange-800 bg-orange-100/75 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">
            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
          </svg>
        </span>
        <div class="flex flex-col justify-center items-start">
          <span>Artigos</span>
          <span class="text-2xl font-bold"><?php echo $totalArtigos; ?></span>
        </div>
      </div>
      <div class="border border-gray-200 flex gap-4 items-center p-6 bg-white rounded-xl shadow">
        <span class="flex items-center p-3 text-green-800 bg-green-100/75 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="" viewBox="0 0 16 16">
            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
          </svg>
        </span>
        <div class="flex flex-col justify-center items-start">
          <span>Usuários</span>
          <span class="text-2xl font-bold"><?php echo $totalUsuarios; ?></span>
        </div>
      </div>
      <div class="w-full sm:col-span-2 lg:col-span-1 border border-gray-200 flex gap-4 items-center p-6 bg-white rounded-xl shadow armazenamento-geral">
        <span class="flex items-center p-3 text-blue-800 bg-blue-100/75 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
          </svg>
        </span>
        <div class="w-full flex flex-col justify-center items-start">
          <span class="font-semibold text-gray-800">Espaço utilizado</span>
          <div class="w-full h-full flex flex-col justify-start">
            <div class="h-full flex mb-2 w-full">
              <div class="w-full bg-gray-200 rounded-full">
                <div class="max-w-full p-1 rounded-md barra-progresso transition-all duration-300"></div>
              </div>
            </div>
            <p class="h-full block text-xs text-gray-600 espaco-utilizado opacity-50 transition-all duration-300">Calculando...</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>