<!DOCTYPE html>
<html lang="pt-br">

<?php require_once 'template/cabecalho.php' ?>

<div class="absolute w-full h-full flex items-center justify-center bg-gray-200/25 efeito-loader-publico-div">
  <div class="efeito-loader-publico">
  </div>
</div>

<body class="min-h-screen max-w-screen flex flex-col font-normal bg-white" data-base-url="<?php echo RAIZ; ?>">
  <div class="hidden" id="conteudo-publico">
    <?php require_once 'template/topo.php' ?>

    <?php if (isset($inicio)) { ?>
      <?php
      // Padrão
      $classesBuscaTamanho = 'max-w-[800px]';
      $classesBuscaAlinhamento = 'justify-center';

      $ajusteBuscaTamanho = Helper::ajuste('publico_inicio_busca_tamanho');
      $ajusteBuscaAlinhamento = Helper::ajuste('publico_inicio_busca_alinhamento');

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

      // Bloco busca
      $fotoInicio = '';
      $classesFotoFundo = 'pers-publico-inicio-busca template-cor-' . Helper::ajuste('publico_cor_primaria');
      $styleFotoFundo = '';

      $buscarFotoInicio = Helper::ajuste('publico_inicio_foto');

      if ($buscarFotoInicio) {
        $fotoInicio = $this->renderImagem($buscarFotoInicio);

        $classesFotoFundo = '';
        $ajusteBuscaTextoCor = Helper::ajuste('publico_inicio_texto_cor');
        $styleFotoFundo = 'style="background-image: url(' . $fotoInicio . '); background-size: cover; background-position: center; color: ' . $ajusteBuscaTextoCor . ';"';
      }
      ?>

      <div class="px-4 md:px-8 py-16 md:py-32 w-full flex items-center justify-center <?php echo $classesFotoFundo; ?>" <?php echo $styleFotoFundo; ?>>
        <div class="w-full max-w-[1244px] flex <?php echo $classesBuscaAlinhamento; ?>">
          <div class="w-full <?php echo $classesBuscaTamanho; ?> flex flex-col items-start gap-6">
            <div class="flex flex-col gap-3">
              <h2 class="font-bold text-3xl"><?php echo Helper::ajuste('publico_inicio_titulo'); ?></h2>
              <div class="font-light"><?php echo Helper::ajuste('publico_inicio_subtitulo'); ?></div>
            </div>
            <div class="w-full flex flex-col justify-center">
              <?php require_once 'inicio/formulario-busca.php' ?>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>

    <main class="p-4 w-full min-h-screen flex flex-col gap-4 items-center">
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

      <div class="w-full max-w-[1244px] min-h-screen flex gap-4"> <?php // rounded-md ?>

        <?php if ($menuLateral) { ?>
          <?php require_once 'template/menu_lateral.php' ?>
        <?php } ?>

        <?php require_once $visao ?>
      </div>
    </main>

    <?php require_once 'template/rodape.php' ?>

    <?php if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE and $this->sessaoUsuario->buscar('debugAtivo')) { ?>
      <div class="my-10 px-4 lg:px-14 flex max-w-screen">
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