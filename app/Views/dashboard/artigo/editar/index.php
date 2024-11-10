<div class="mb-10 relative w-full min-h-screen flex flex-col">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex gap-2 items-end">Editar artigo <a href="/<?php echo $this->usuarioLogado['subdominio']; ?>/artigo/<?php echo $artigo['Artigo']['id'] ?>" target="_blank" class="py-1 text-sm text-gray-400 font-light italic hover:underline">(Artigo #<?php echo $artigo['Artigo']['id']; ?>)</a></h2>
      <p class="text-gray-600">Vamos dar aquele toque nos seus tutoriais de ajuda e deixá-los ainda melhores!</p>
    </div>
    <div class="py-2 h-full flex gap-2 items-start">
      <a href="<?php echo baseUrl($botaoVoltar ? $botaoVoltar : '/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos?referer=' . $botaoVoltar); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
      <button class="w-max <?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> botao-abrir-menu-adicionar-conteudos">Editar</button>
    </div>
  </div>

  <?php require_once 'conteudo/menu-adicionar.php' ?>

  <div class="mt-4 w-full flex flex-col lg:flex-row lg:justify-between gap-5">
    <div class="relative pb-10 w-full border border-slate-300 bg-white duration-350 shadow rounded-md">
      <?php if((int) $artigo['Artigo']['ativo'] == INATIVO) { ?>
        <div class="md:absolute w-full p-4 flex justify-end">
          <div class="w-full md:w-max py-1 px-4 bg-red-900 text-center text-white text-xs font-light rounded">
            Não publicado
          </div>
        </div>
      <?php } ?>
      <?php require_once 'conteudo/pre-visualizacao.php' ?>
    </div>
  </div>
</div>

<?php require_once 'modais/formulario.php' ?>
<?php require_once 'conteudo/modais/organizar.php' ?>
<?php require_once 'conteudo/modais/remover.php' ?>
<?php require_once 'conteudo/modais/adicionar-video.php' ?>
<?php require_once 'conteudo/modais/adicionar-imagem.php' ?>
<?php require_once 'conteudo/modais/editar-imagem.php' ?>
<?php require_once 'conteudo/modais/editar-video.php' ?>