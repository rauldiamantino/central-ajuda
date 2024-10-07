<!DOCTYPE html>
<html lang="pt-br">
<?php require_once 'template/cabecalho.php' ?>

<body class="font-normal h-screen max-w-screen bg-slate-100" data-editor="ClassicEditor" data-empresa-id="<?php echo $this->usuarioLogado['empresaId'] ?>">
  <div id="efeito-loader" class="loader <?php echo isset($loader) ? '' : 'hidden'; ?>"></div>

  <?php if (isset($pagLogin)) { ?>
    <main>
      <div class="w-full h-screen flex justify-center items-center">
        <div class="relative p-4 w-full sm:w-[600px] h-max">
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
        <div class="relative p-4 w-full sm:w-[600px] h-max">
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
        <div class="relative p-4 w-full sm:w-[600px] h-max">
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
      <main class="xl:ml-72 pt-5 lg:px-10 flex w-screen h-screen flex-col">
        <?php require_once 'notificacoes.php' ?>
        <div class="w-full max-w-screen h-full h-max-full flex gap-6">
          <?php require_once $visao ?>
        </div>
      </main>
    </div>
  <?php } ?>

  <?php require_once 'scripts.php' ?>
</body>
</html>