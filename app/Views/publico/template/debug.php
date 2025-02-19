<?php if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE and $this->sessaoUsuario->buscar('debugAtivo')) { ?>
  <div class="py-10 px-4 lg:px-14 flex max-w-screen bg-white">
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