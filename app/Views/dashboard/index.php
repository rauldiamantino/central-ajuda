<!DOCTYPE html>
<html lang="pt-br">
<?php require_once 'template/cabecalho.php' ?>

<body class="relative min-h-screen max-w-screen flex flex-col justify-start items-center font-normal bg-slate-100" data-editor="ClassicEditor" data-empresa-id="<?php echo $this->usuarioLogado['empresaId'] ?>" data-empresa="<?php echo $this->usuarioLogado['subdominio'] ?>" data-base-url="<?php echo RAIZ; ?>">
  <div id="efeito-loader" class="loader <?php echo isset($loader) ? '' : 'hidden'; ?>"></div>

  <?php if (isset($pagLogin)) { ?>
    <main>
      <div class="w-full min-h-screen flex justify-center items-center">
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
      <div class="w-full min-h-screen flex justify-center items-center">
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
      <div class="w-full min-h-screen flex justify-center items-center">
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
    <div class="mt-5 w-11/12 md:w-10/12 lg:w-9/12 flex h-full">
      <?php require_once 'template/menu_lateral.php' ?>
      <main class="xl:pl-72 flex flex-col w-full">
        <?php require_once 'notificacoes.php' ?>
        <div class="w-full h-full flex gap-6">
          <?php require_once $visao ?>
        </div>
      </main>
    </div>
  <?php } ?>
  <?php require_once 'scripts.php' ?>

  <?php if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE and $this->sessaoUsuario->buscar('debugAtivo')) { ?>

    <?php
    $classeMargem = '';

    if (! isset($pagLogin) and ! isset($pagLoginSuporte) and ! isset($pagCadastro)) {
      $classeMargem = 'xl:ml-72 ';
    }
    ?>
    <div class="<?php echo $classeMargem ?>my-10 px-4 lg:px-16 flex w-full lg:w-8/12 max-w-screen">
      <div class="w-full">
        <h2 class="mb-5 text-2xl font-semibold">Debug</h2>
        <div class="border border-slate-300 w-full p-4 lg:p-10 bg-gray-200 text-gray-900 text-xs shadow rounded-md">
          <div class="py-4 overflow-x-auto">
            <?php pr($this->sessaoUsuario->buscar('debug')); ?>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
</body>
</html>