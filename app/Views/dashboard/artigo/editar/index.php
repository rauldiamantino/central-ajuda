<?php
$referer = '';

if ($botaoVoltar) {
  $referer = '?referer=' . urlencode($botaoVoltar);
}

$dominio = '';

if ($dominio) {
  $dominio = $this->usuarioLogado['subdominio_2'];
}
?>

<div class="mb-10 relative w-full h-max flex flex-col">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex flex-wrap sm:gap-2 items-end">
        Editar artigo

        <?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
          <span class="text-gray-400 font-extralight">#<?php echo $artigo['Artigo']['id'] ?></span>
        <?php } ?>
      </h2>
      <p class="text-gray-600">Vamos deixar seus tutoriais ainda melhores?</p>
    </div>
    <div class="py-2 w-full h-full flex gap-2 items-start justify-end">
      <a href="<?php echo $botaoVoltar ? $botaoVoltar : '/dashboard/artigos' . $referer; ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>

      <?php // Menu auxiliar ?>
      <div class="relative text-sm">
        <button type="button" class="p-3 bg-gray-400/10 hover:bg-gray-400/20 rounded-lg cursor-pointer" onclick="document.querySelector('.menu-auxiliar-artigo').classList.toggle('hidden')">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
          </svg>
        </button>
        <ul class="z-10 absolute top-12 right-0 lg:-right-10 border border-slate-300 lg:mx-10 flex flex-col justify-center bg-white text-gray-600 rounded-md shadow hidden menu-auxiliar menu-auxiliar-artigo">
          <li class="px-4 py-3">
            <button type="button" onclick="window.open('<?php echo $dominio . '/artigo/' . $artigo['Artigo']['codigo'] . '/' . $this->gerarSlug($artigo['Artigo']['titulo']); ?>')" class="flex gap-3 items-center hover:text-gray-950">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16"><path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/></svg>
              <span class="whitespace-nowrap">Ver na central</span>
            </button>
          </li>
          <li class="px-4 py-3">
            <button type="button" class="flex gap-3 items-center hover:text-gray-950 botao-abrir-menu-adicionar-conteudos">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
              </svg>
              <span class="whitespace-nowrap">Editar artigo</span>
            </button>
          </li>
          <li class="px-4 py-3">
            <button type="button" <?php echo count($conteudos) > 1 ? 'onclick="buscarConteudos()"' : '' ?> class="flex gap-3 items-center hover:text-gray-950">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
              </svg>
              <span class="whitespace-nowrap">Reorganizar</span>
            </button>
          </li>
          <li class="px-4 py-3">
            <button type="button" class="flex gap-3 items-center text-red-800 js-dashboard-artigos-remover" data-botao-voltar="<?php echo urlencode($botaoVoltar); ?>" data-artigo-id="<?php echo $artigo['Artigo']['id'] ?>" data-empresa-id="<?php echo $artigo['Artigo']['empresa_id'] ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="min-h-full">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
              </svg>
              <span class="whitespace-nowrap">Excluir artigo</span>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="w-full border-t border-slate-300 pt-4 flex flex-col lg:justify-between gap-5">

    <?php
    if ($this->usuarioLogado['nivel'] != USUARIO_LEITURA) {
      require_once 'conteudo/menu-adicionar.php';
    }
    ?>

    <div class="w-full max-w-[990px] border border-slate-300 bg-white duration-350 shadow rounded-md">
      <div class="relative pb-10 w-full">
        <div class="w-full sm:w-max flex flex-col sm:flex-row gap-2 sm:justify-start sm:items-center p-6">
          <div class="w-full sm:w-max flex gap-2 justify-start items-center">
            <?php if (isset($artigo['Categoria']['ativo']) and $artigo['Categoria']['ativo'] == ATIVO) { ?>
              <div class="w-full sm:w-max border text-green-900 flex gap-2 items-center justify-center text-xs p-1.5 rounded">
                Categoria pública
              </div>
            <?php } else { ?>
              <div class="w-full sm:w-max border text-red-900 flex gap-2 items-center justify-center text-xs p-1.5 rounded">
                Categoria privada
              </div>
            <?php } ?>
          </div>
          <div class="w-full flex gap-2 justify-start items-center">
            <?php if (isset($artigo['Artigo']['ativo']) and $artigo['Artigo']['ativo'] == ATIVO) { ?>
              <div class="w-full sm:w-max border text-green-900 flex gap-2 items-center justify-center text-xs p-1.5 rounded">
                Artigo público
              </div>
            <?php } else { ?>
              <div class="w-full sm:w-max border text-red-900 flex gap-2 items-center justify-center text-xs p-1.5 rounded">
                Artigo privado
              </div>
            <?php } ?>
          </div>

          <?php
          $classeDesbloqueado = 'hidden';
          $classeBloqueado = '';
          $acaoBotaoBloqueio = '';

          if (isset($artigo['Artigo']['editar']) and $artigo['Artigo']['editar'] == ATIVO) {
            $classeDesbloqueado = '';
            $classeBloqueado = 'hidden';
            $acaoBotaoBloqueio = '';
          }

          if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
            $classeDesbloqueado = 'hidden';
            $classeBloqueado = '';
            $acaoBotaoBloqueio = 'disabled';
          }

          ?>
          <button
            type="button"
            class="<?php echo $classeBloqueado; ?> flex w-full sm:w-max gap-1 items-center justify-center text-xs hover:bg-gray-300/25 duration-150 py-1 px-2 rounded pre-visualizacao-bloqueado"
            onclick="definirDesbloqueio(<?php echo $artigo['Artigo']['id']; ?>, <?php echo $this->usuarioLogado['nivel']; ?>)"
            <?php echo $acaoBotaoBloqueio; ?>
          >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5">
              <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
            </svg>
            Bloqueado
          </button>

          <button
            type="button"
            class="<?php echo $classeDesbloqueado; ?> flex w-full sm:w-max border border-blue-900/75 text-blue-900 gap-1 items-center justify-center text-xs hover:bg-blue-100/25 duration-150 py-1 px-2 rounded pre-visualizacao-bloquear"
            onclick="definirBloqueio(<?php echo $artigo['Artigo']['id']; ?>)"
            <?php echo $acaoBotaoBloqueio; ?>
            >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5">
              <path d="M18 1.5c2.9 0 5.25 2.35 5.25 5.25v3.75a.75.75 0 0 1-1.5 0V6.75a3.75 3.75 0 1 0-7.5 0v3a3 3 0 0 1 3 3v6.75a3 3 0 0 1-3 3H3.75a3 3 0 0 1-3-3v-6.75a3 3 0 0 1 3-3h9v-3c0-2.9 2.35-5.25 5.25-5.25Z" />
            </svg>
            Desbloqueado
          </button>
        </div>

        <?php require_once 'conteudo/pre-visualizacao.php' ?>
      </div>
    </div>
  </div>
</div>

<?php require_once 'modais/formulario.php' ?>
<?php require_once 'modais/remover-artigo.php' ?>
<?php require_once 'conteudo/modais/organizar.php' ?>
<?php require_once 'conteudo/modais/remover.php' ?>
<?php require_once 'conteudo/modais/fechar.php' ?>