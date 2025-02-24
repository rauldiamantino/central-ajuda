<?php
if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
  return;
}
?>

<div class="menu-adicionar">
  <span class="text-xs font-extralight italic">Ação</span>
  <div class="w-max flex flex-row gap-2 justify-start items-center form-conteudo conteudo-botoes-adicionar">
    <input type="hidden" name="artigo.id" value="<?php echo $artigo['Artigo']['id'] ?>">
    <button
      type="button"
      class="flex w-full sm:w-max border text-purple-900 gap-1 items-center justify-center text-xs hover:bg-purple-100/25 duration-150 py-1 px-2 rounded conteudo-btn-texto-adicionar"
      onclick="abrirModalAdicionar('texto')"
      >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
      </svg>
    </button>
    <button
      type="button"
      class="flex w-full sm:w-max border text-red-900 gap-1 items-center justify-center text-xs hover:bg-red-100/25 duration-150 py-1 px-2 rounded botao-abrir-menu-adicionar-video"
      onclick="abrirModalAdicionar('video')"
      >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
      </svg>
    </button>
    <button
      type="button"
      class="flex w-full sm:w-max border text-blue-900 gap-1 items-center justify-center text-xs hover:bg-blue-100/25 duration-150 py-1 px-2 rounded botao-abrir-menu-adicionar-imagem"
      onclick="abrirModalAdicionar('imagem')"
      >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
      </svg>
    </button>

    <?php
    // Modo leitura
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
      class="<?php echo $classeBloqueado; ?> flex w-full sm:w-max border border-transparent gap-1 items-center justify-center text-xs hover:bg-gray-300/25 duration-150 py-1 px-2 rounded pre-visualizacao-bloqueado"
      onclick="definirDesbloqueio(<?php echo $artigo['Artigo']['id']; ?>, <?php echo $this->usuarioLogado['nivel']; ?>)"
      <?php echo $acaoBotaoBloqueio; ?>
    >
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
      </svg>
    </button>
    <button
      type="button"
      class="<?php echo $classeDesbloqueado; ?> flex w-full sm:w-max border text-green-900 gap-1 items-center justify-center text-xs hover:bg-green-100/25 duration-150 py-1 px-2 rounded pre-visualizacao-bloquear"
      onclick="definirBloqueio(<?php echo $artigo['Artigo']['id']; ?>)"
      <?php echo $acaoBotaoBloqueio; ?>
      >
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
        <path d="M18 1.5c2.9 0 5.25 2.35 5.25 5.25v3.75a.75.75 0 0 1-1.5 0V6.75a3.75 3.75 0 1 0-7.5 0v3a3 3 0 0 1 3 3v6.75a3 3 0 0 1-3 3H3.75a3 3 0 0 1-3-3v-6.75a3 3 0 0 1 3-3h9v-3c0-2.9 2.35-5.25 5.25-5.25Z" />
      </svg>
    </button>
  </div>
</div>