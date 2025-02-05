<!DOCTYPE html>
<html lang="pt-br">

<?php require_once 'template/cabecalho.php' ?>

<?php // echo isset($inicio) ? 'bg-gray-100' : 'bg-gray-100' ?>
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
      $classes_busca_tamanho = 'max-w-[800px]';
      $classes_busca_alinhamento = 'justify-center';

      if ((int) $this->buscarAjuste('publico_inicio_busca_tamanho') == 1) {
        $classes_busca_tamanho = 'max-w-[600px]';
      }
      elseif ((int) $this->buscarAjuste('publico_inicio_busca_tamanho') == 2) {
        $classes_busca_tamanho = 'max-w-[800px]';
      }
      elseif ((int) $this->buscarAjuste('publico_inicio_busca_tamanho') == 3) {
        $classes_busca_tamanho = 'max-w-[1244px]';
      }

      if ((int) $this->buscarAjuste('publico_inicio_busca_alinhamento') == 1) {
        $classes_busca_alinhamento = 'justify-start';
      }
      elseif ((int) $this->buscarAjuste('publico_inicio_busca_alinhamento') == 2) {
        $classes_busca_alinhamento = 'justify-center';

      }
      elseif ((int) $this->buscarAjuste('publico_inicio_busca_alinhamento') == 3) {
        $classes_busca_alinhamento = 'justify-end';
      }

      // Bloco busca
      $foto_inicio = '';
      $buscar_foto_inicio = $this->buscarAjuste('publico_inicio_foto');
      $classes_foto_fundo = 'pers-publico-inicio-busca template-cor-' . $corPrimaria;
      $classes_formulario_busca_input = 'pers-publico-input template-cor-' . $corPrimaria;
      $classes_formulario_busca_lupa = 'pers-publico-lupa template-cor-' . $corPrimaria;;

      $style_foto_fundo = '';
      $style_formulario_busca_input = '';
      $style_formulario_busca_lupa = '';

      if ($buscar_foto_inicio) {
        $foto_inicio = $this->renderImagem($buscar_foto_inicio);
        $classes_foto_fundo = '';
        $classes_formulario_busca_input = '';
        $classes_formulario_busca_lupa = '';

        $style_formulario_busca_input = 'style="color: ' . $this->buscarAjuste('publico_inicio_busca_cor') . ';border: 1px solid ' . $this->buscarAjuste('publico_inicio_busca_borda') . ';"';
        $style_formulario_busca_lupa = 'style="color: ' . $this->buscarAjuste('publico_inicio_busca_cor') . ';"';
        $style_foto_fundo = 'style="background-image: url(' . $foto_inicio . '); background-size: cover; background-position: center; color: ' . $this->buscarAjuste('publico_inicio_texto_cor') . ';"';
      }
      ?>

      <div class="px-4 md:px-8 py-16 md:py-32 w-full flex items-center justify-center <?php echo $classes_foto_fundo; ?>" <?php echo $style_foto_fundo; ?>>
        <div class="w-full max-w-[1244px] flex <?php echo $classes_busca_alinhamento; ?>">
          <div class="w-full <?php echo $classes_busca_tamanho; ?> flex flex-col items-start gap-6">
            <div class="flex flex-col gap-3">
              <h2 class="font-bold text-3xl"><?php echo $this->buscarAjuste('publico_inicio_titulo'); ?></h2>
              <div class="font-light"><?php echo $this->buscarAjuste('publico_inicio_subtitulo'); ?></div>
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