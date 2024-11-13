<div class="mb-10 relative w-full min-h-screen flex flex-col">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex gap-2 items-end">Editar artigo <a href="/<?php echo $this->usuarioLogado['subdominio']; ?>/artigo/<?php echo $artigo['Artigo']['id'] ?>" target="_blank" class="py-1 text-sm text-gray-400 font-light italic hover:underline">(Artigo #<?php echo $artigo['Artigo']['id']; ?>)</a></h2>
      <p class="text-gray-600">Vamos dar aquele toque nos seus tutoriais de ajuda e deixá-los ainda melhores!</p>
    </div>
    <div class="py-2 w-full h-full flex gap-2 items-start justify-end">
      <a href="<?php echo baseUrl($botaoVoltar ? $botaoVoltar : '/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos?referer=' . $botaoVoltar); ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>

      <?php // Menu auxiliar ?>
      <div class="relative">
        <button type="button" class="p-3 bg-gray-400/10 hover:bg-gray-400/20 rounded-lg cursor-pointer" onclick="document.querySelector('.menu-auxiliar-artigo').classList.toggle('hidden')">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
          </svg>
        </button>
        <ul class="z-10 absolute top-12 right-0 md:-right-10 border border-slate-300 lg:mx-10 flex flex-col justify-center bg-white text-gray-600 rounded-md shadow hidden menu-auxiliar menu-auxiliar-artigo">
          <li class="px-8 py-5">
            <button type="button" <?php echo count($conteudos) > 1 ? 'onclick="buscarConteudos()"' : '' ?> class="flex gap-3 items-center hover:text-gray-950">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
              </svg>
              Reorganizar
            </button>
          </li>
          <li class="px-8 py-5">
            <button type="button" class="flex gap-3 items-center hover:text-gray-950 botao-abrir-menu-adicionar-conteudos">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
              </svg>
              <span class="whitespace-nowrap">Editar título</span>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <?php require_once 'conteudo/menu-adicionar.php' ?>

  <div class="mt-4 w-full flex flex-col lg:flex-row lg:justify-between gap-5">
    <div class="relative pb-10 w-full border border-slate-300 bg-white duration-350 shadow rounded-md">
      <?php if(isset($artigo['Artigo']['ativo']) and (int) $artigo['Artigo']['ativo'] == INATIVO) { ?>
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