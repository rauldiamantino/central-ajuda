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

    <div class="w-full h-full flex">
      <div class="py-2 w-full flex gap-2 items-center justify-end">
        <a href="<?php echo baseUrl($urlVoltar); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
        <button type="button" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?>" onclick="document.querySelector('.btn-gravar-usuario').click()">Gravar</button>
      </div>
    </div>
  </div>
  <?php require_once 'formulario.php' ?>
</div>