<?php
if ($this->usuarioLogado['nivel'] == USUARIO_COMUM) {
  $urlVoltar = '/' . $this->usuarioLogado['subdominio'] . '/dashboard';
}
else {
$urlVoltar = '/' . $this->usuarioLogado['subdominio'] . '/dashboard/usuarios';
}
?>

<div class="mb-10 relative w-full h-max flex flex-col">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex gap-2">Editar <?php require_once 'usuario-padrao.php' ?>
      </h2>
      <p class="text-gray-600">Gerencie seu plano, extrato e configurações para otimizar sua experiência.</p>
    </div>

    <div class="w-full h-full flex gap-2 items-center">
      <div class="py-2 w-full flex gap-2 items-center justify-end">
        <a href="<?php echo baseUrl($urlVoltar); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
        <button type="button" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?>" onclick="document.querySelector('.btn-gravar-usuario').click()">Gravar</button>
      </div>

      <?php // Menu auxiliar ?>
      <div class="relative">
        <button type="button" class="p-3 bg-gray-400/10 hover:bg-gray-400/20 rounded-lg cursor-pointer" onclick="document.querySelector('.menu-auxiliar-usuario').classList.toggle('hidden')">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
          </svg>
        </button>
        <ul class="z-10 absolute top-12 right-0 lg:-right-10 border border-slate-300 lg:mx-10 flex flex-col justify-center bg-white text-gray-600 rounded-md shadow hidden menu-auxiliar menu-auxiliar-usuario">
          <li class="px-4 py-3">
            <button type="button" class="flex gap-3 items-center text-red-800 js-dashboard-usuarios-remover" data-usuario-id="<?php echo $usuario['Usuario']['id'] ?>" data-empresa-id="<?php echo $usuario['Usuario']['empresa_id'] ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="min-h-full">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
              </svg>
              <span class="whitespace-nowrap">Excluir usuário</span>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <?php require_once 'modais/remover-usuario.php' ?>
  <?php require_once 'formulario.php' ?>
</div>