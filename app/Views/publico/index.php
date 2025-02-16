<!DOCTYPE html>
<html lang="pt-br">

<?php require_once 'template/cabecalho.php' ?>

<!-- <div class="absolute w-full h-full flex items-center justify-center bg-gray-200/25 efeito-loader-publico-div">
  <div class="efeito-loader-publico">
  </div>
</div> -->

<?php
$styleFotoFundoDesktop = '';
$classesFundoDesktop = 'pers-publico-inicio-busca template-cor-' . Helper::ajuste('publico_cor_primaria');

if (isset($inicio)) {
  // Padrão
  $classesBuscaTamanho = 'max-w-[800px]';
  $classesBuscaAlinhamento = 'justify-center';

  $ajusteBuscaTamanho = Helper::ajuste('publico_inicio_busca_tamanho');
  $ajusteBuscaAlinhamento = Helper::ajuste('publico_inicio_busca_alinhamento');
  $ajusteBuscaAlinhamentoTexto = Helper::ajuste('publico_inicio_busca_alinhamento_texto');

  if ((int) $ajusteBuscaTamanho == 1) {
    $classesBuscaTamanho = 'max-w-[600px]';
  }
  elseif ((int) $ajusteBuscaTamanho == 2) {
    $classesBuscaTamanho = 'max-w-[800px]';
  }
  elseif ((int) $ajusteBuscaTamanho == 3) {
    $classesBuscaTamanho = 'max-w-[1244px]';
  }

  if ((int) $ajusteBuscaAlinhamento == 1) {
    $classesBuscaAlinhamento = 'justify-start';
  }
  elseif ((int) $ajusteBuscaAlinhamento == 2) {
    $classesBuscaAlinhamento = 'justify-center';

  }
  elseif ((int) $ajusteBuscaAlinhamento == 3) {
    $classesBuscaAlinhamento = 'justify-end';
  }

  if ((int) $ajusteBuscaAlinhamentoTexto == 1) {
    $classesBuscaAlinhamentoTexto = 'text-start';
  }
  elseif ((int) $ajusteBuscaAlinhamentoTexto == 2) {
    $classesBuscaAlinhamentoTexto = 'text-center';

  }
  elseif ((int) $ajusteBuscaAlinhamentoTexto == 3) {
    $classesBuscaAlinhamentoTexto = 'text-end';
  }

  // Bloco busca
  $buscarFotoInicioDesktop = Helper::ajuste('publico_inicio_foto_desktop');

  if ($buscarFotoInicioDesktop and isset($inicio)) {
    $fotoInicioDesktop = $this->renderImagem($buscarFotoInicioDesktop);
    $ajusteBuscaTextoCor = Helper::ajuste('publico_inicio_texto_cor_desktop');
    $styleFotoFundoDesktop = 'style="color: ' . $ajusteBuscaTextoCor . ';"';
    $classesFundoDesktop = '';
  }

  if ((int) Helper::ajuste('publico_inicio_cor_fundo') == INATIVO) {
    $classesFundoDesktop = '';
  }
}
?>

<body class="min-h-screen max-w-screen flex flex-col font-normal bg-white text-black <?php echo $classesFundoDesktop; ?>" <?php echo $styleFotoFundoDesktop; ?> data-base-url="<?php echo RAIZ; ?>">
  <?php
  $classesLarguraGeral = 'max-w-[1244px]';

  if ((int) Helper::ajuste('publico_largura_geral') == 2) {
    $classesLarguraGeral = 'max-w-[1024px]';
  }
  elseif ((int) Helper::ajuste('publico_largura_geral') == 3) {
    $classesLarguraGeral = 'max-w-[900px]';
  }
  elseif ((int) Helper::ajuste('publico_largura_geral') == 4) {
    $classesLarguraGeral = 'max-w-[800px]';
  }
  ?>
  <div id="conteudo-publico">
    <?php require_once 'template/topo.php' ?>

    <?php if (isset($inicio)) { ?>
      <?php // Fundo resoluções Desktop ?>
      <div class="relative px-4 md:px-8 mb-10 w-full h-[360px] flex items-center justify-center">

        <?php if ($buscarFotoInicioDesktop) { ?>
          <img src="<?php echo $fotoInicioDesktop; ?>" alt="Imagem de fundo desktop" class="absolute top-0 -z-10 min-w-[1000px] w-full h-max object-cover" />
        <?php } ?>

        <?php require 'inicio/formulario-busca.php' ?>
      </div>
    <?php } ?>

    <main class="w-full min-h-screen flex flex-col gap-4 items-center">
      <?php $notificacaoSucesso = $this->sessaoUsuario->buscar('ok'); ?>
      <?php $notificacaoErro = $this->sessaoUsuario->buscar('erro'); ?>

      <?php // Notificação de Sucesso ?>
      <?php if (isset($notificacaoSucesso) and $notificacaoSucesso) { ?>
        <div class="fixed bottom-0 w-full z-30 flex justify-center items-center text-lg js-notificacao-sucesso-publico js-dashboard-notificacao-sucesso-btn-fechar">
          <div class="w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <?php echo $notificacaoSucesso ?>
          </div>
        </div>
      <?php } ?>

      <?php // Notificação de erro ?>
      <?php if (isset($notificacaoErro) and $notificacaoErro) { ?>
        <div class="fixed bottom-0 w-full z-30 flex justify-center items-center text-lg js-notificacao-erro-publico js-dashboard-notificacao-erro-btn-fechar">
          <div class="w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <?php echo $notificacaoErro ?>
          </div>
        </div>
      <?php } ?>

      <?php // Limpar notificações ?>
      <?php $this->sessaoUsuario->apagar('ok'); ?>
      <?php $this->sessaoUsuario->apagar('erro'); ?>

      <div class="relative min-w-full h-full">

        <?php if ((int) Helper::ajuste('publico_inicio_arredondamento') == 1 and isset($inicio)) { ?>
          <svg class="absolute -top-[50px] left-0 w-full h-[100px]" viewBox="0 0 1440 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path fill="white" d="M0,0 Q720,100 1440,0 V100 H0 Z"></path>
          </svg>
        <?php } ?>

        <?php if ((int) Helper::ajuste('publico_inicio_arredondamento') == 2 and isset($inicio)) { ?>
          <svg class="absolute -top-[100px] left-0 w-full h-[100px]" viewBox="0 0 1440 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path fill="white" d="M0,100 Q720,0 1440,100 V100 H0 Z"></path>
          </svg>
        <?php } ?>

        <div class="px-4 <?php echo isset($inicio) ? 'pt-16' : 'pt-4' ?> w-full h-full bg-white text-black">
          <div class="mx-auto w-full <?php echo $classesLarguraGeral ?> min-h-screen flex gap-4">

            <?php if ($menuLateral and (int) Helper::ajuste('publico_menu_lateral') == ATIVO) { ?>
              <?php require_once 'template/menu_lateral.php' ?>
            <?php } ?>

            <?php require_once $visao ?>
        </div>
        </div>
      </div>
    </main>

    <?php require_once 'template/rodape.php' ?>

    <?php if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE and $this->sessaoUsuario->buscar('debugAtivo')) { ?>
      <div class="my-10 px-4 lg:px-14 flex max-w-screen ">
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
  </div>

  <?php require_once 'scripts.php' ?>
</body>
</html>