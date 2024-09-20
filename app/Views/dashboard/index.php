<!DOCTYPE html>
<html lang="pt-br">
<?php require_once 'template/cabecalho.php' ?>

<body class="font-normal h-screen max-w-screen" data-editor="ClassicEditor" data-subdominio="<?php echo $this->usuarioLogadoSubdominio ?>">
  <?php if (isset($pagLogin)) { ?>
    <main>
      <div class="w-full h-screen flex justify-center items-center">
        <div class="relative w-max min-w-96 h-max bg-white">
          <?php require_once $visao ?>
          <?php require_once 'login/notificacoes.php' ?>
        </div>
      </div>
      <?php require_once 'rodape-suporte.php' ?>
    </main>
  <?php } ?>

  <?php if (isset($pagCadastro)) { ?>
    <main>
      <div class="w-full h-screen flex justify-center items-center">
        <div class="relative w-max min-w-96 h-max bg-white">
          <?php require_once $visao ?>
          <?php require_once 'cadastro/notificacoes.php' ?>
        </div>
      </div>
    </main>
    <?php require_once 'rodape-suporte.php' ?>
  <?php } ?>

  <?php if (! isset($pagLogin) and ! isset($pagCadastro)) { ?>
    <?php require_once 'template/topo.php' ?>
    <div class="flex">
      <?php require_once 'template/menu_lateral.php' ?>
      <main class="md:ml-64 pt-16 flex w-screen h-screen flex-col">
        <?php require_once 'notificacoes.php' ?>
        <div class="w-full max-w-screen h-full h-max-full flex gap-6">
          <?php require_once $visao ?>
        </div>
      </main>
    </div>
  <?php } ?>

  <?php if ($this->usuarioLogadoId) { ?>
    <a href="/<?php echo $this->usuarioLogadoSubdominio ?>/dashboard/usuario/editar/<?php echo $this->usuarioLogadoId ?>" class="fixed bottom-0 right-0 py-1 px-2 md:px-6 flex items-center gap-2 text-xs font-extralight bg-green-800 text-white rounded-t-lg">
      <?php echo $this->usuarioLogadoEmail ?>
    </a>
  <?php } ?>
  <?php require_once 'scripts.php' ?>
</body>
</html>