<!DOCTYPE html>
<html lang="pt-br">
<?php require_once 'template/cabecalho.php' ?>

<body class="font-normal h-screen max-w-screen" data-editor="ClassicEditor" data-empresa-id="<?php echo $this->usuarioLogado['empresaId'] ?>">
  <div id="efeito-loader" class="loader <?php echo isset($loader) ? '' : 'hidden'; ?>"></div>

  <?php if (isset($pagLogin)) { ?>
    <main>
      <div class="w-full h-screen flex justify-center items-center">
        <div class="relative p-4 w-full sm:w-[600px] h-max bg-white">
          <?php require_once $visao ?>
          <?php require_once 'login/notificacoes.php' ?>
        </div>
      </div>
      <?php require_once 'rodape-suporte.php' ?>
    </main>
  <?php } ?>

  <?php if (isset($pagLoginSuporte)) { ?>
    <main>
      <div class="w-full h-screen flex justify-center items-center">
        <div class="relative p-4 w-full sm:w-[600px] h-max bg-white">
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
        <div class="relative p-4 w-full sm:w-[600px] h-max bg-white">
          <?php require_once $visao ?>
          <?php require_once 'cadastro/notificacoes.php' ?>
        </div>
      </div>
    </main>

    <?php if (! isset($pagCadastroSucesso)) { ?>
      <?php require_once 'rodape-suporte.php' ?>
    <?php } ?>
  <?php } ?>

  <?php if (! isset($pagLogin) and ! isset($pagLoginSuporte) and ! isset($pagCadastro)) { ?>
    <?php require_once 'template/topo.php' ?>
    <div class="flex">
      <?php require_once 'template/menu_lateral.php' ?>
      <main class="lg:ml-64 pt-16 flex w-screen h-screen flex-col">
        <?php require_once 'notificacoes.php' ?>
        <div class="w-full max-w-screen h-full h-max-full flex gap-6">
          <?php require_once $visao ?>
        </div>
      </main>
    </div>
  <?php } ?>

  <?php
  if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE and $this->sessaoUsuario->buscar('empresaId') > 1) {
    $usuarioSuporte = true;
  }
  else {
    $usuarioSuporte = false;
  }
  ?>

  <?php // Email com link para edição de usuário ?>
  <?php if ($this->usuarioLogado['id'] and ! isset($pagLoginSuporte) and $usuarioSuporte == false) { ?>
    <a href="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/usuario/editar/<?php echo $this->usuarioLogado['id'] ?>" class="fixed bottom-0 right-0 py-1 px-2 md:px-6 flex items-center gap-2 text-xs font-extralight bg-green-800 text-white rounded-t-lg">
      <?php echo $this->usuarioLogado['email'] ?>
    </a>
  <?php } ?>

  <?php // Usuário de suporte edita somente na loja padrão ?>
  <?php if ($this->usuarioLogado['id'] and ! isset($pagLoginSuporte) and $usuarioSuporte == true) { ?>
    <div class="fixed bottom-0 right-0 py-1 px-2 md:px-6 flex items-center gap-2 text-xs font-extralight bg-red-800 text-white rounded-t-lg">
      <?php echo $this->usuarioLogado['email'] ?>
    </div>
  <?php } ?>

  <?php require_once 'scripts.php' ?>
</body>
</html>