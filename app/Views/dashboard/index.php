<!DOCTYPE html>
<html lang="pt-br">
<?php require_once 'template/cabecalho.php' ?>

<body class="relative w-full min-h-screen max-w-screen flex flex-col justify-start items-center font-normal bg-gray-100" data-editor="ClassicEditor" data-empresa-id="<?php echo $this->usuarioLogado['empresaId'] ?>" data-empresa="<?php echo $this->usuarioLogado['subdominio'] ?>" data-base-url="<?php echo RAIZ; ?>">
  <div class="efeito-loader" <?php echo isset($loader) ? '' : 'hidden'; ?>></div>
  <?php if (isset($pagLogin)) { ?>
    <main class="w-full my-auto p-2">
      <div class="w-full h-full flex justify-center items-center">
        <div class="relative w-full sm:w-[445px] h-max">
          <?php require_once $visao ?>
          <?php require_once 'login/notificacoes.php' ?>
        </div>
      </div>
      <?php require_once 'rodape-suporte.php' ?>
    </main>
  <?php } ?>

  <?php if (isset($pagLoginSuporte)) { ?>
    <main class="w-full my-auto p-2">
      <div class="w-full h-full flex justify-center items-center">
        <div class="relative w-full sm:w-[445px] h-max">
          <?php require_once $visao ?>
          <?php require_once 'login/notificacoes.php' ?>
        </div>
      </div>
      <?php require_once 'rodape-suporte.php' ?>
    </main>
  <?php } ?>

  <?php if (isset($pagCadastro)) { ?>
    <main class="w-full my-auto p-2">
      <div class="w-full h-full flex justify-center items-center">
        <div class="relative w-full sm:w-[445px] h-max">
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
    <div class="p-5 w-full max-w-[1660px] flex h-max">
      <?php require_once 'template/menu_lateral.php' ?>
      <main class="xl:pl-72 flex flex-col w-full">
        <?php require_once 'notificacoes.php' ?>
        <div class="w-full h-full flex gap-6">
          <?php require_once $visao ?>
        </div>
      </main>
    </div>
  <?php } ?>

  <?php require_once 'debug.php' ?>
  <?php require_once 'scripts.php' ?>
</body>
</html>