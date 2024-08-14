<div class="flex flex-col gap-4 w-full min-w-96 form-conteudo">
  <input type="hidden" name="artigo.id" value="<?php echo $artigo['Artigo.id'] ?>">
  <div class="flex gap-2 conteudo-botoes-adicionar">
    <button type="button" class="flex gap-2 items-center justify-center py-2 px-4 border border-gray-800 hover:border-gray-500 text-gray-800 text-xs rounded-lg conteudo-btn-texto-adicionar">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-textarea-t" viewBox="0 0 16 16">
        <path d="M1.5 2.5A1.5 1.5 0 0 1 3 1h10a1.5 1.5 0 0 1 1.5 1.5v3.563a2 2 0 0 1 0 3.874V13.5A1.5 1.5 0 0 1 13 15H3a1.5 1.5 0 0 1-1.5-1.5V9.937a2 2 0 0 1 0-3.874zm1 3.563a2 2 0 0 1 0 3.874V13.5a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V9.937a2 2 0 0 1 0-3.874V2.5A.5.5 0 0 0 13 2H3a.5.5 0 0 0-.5.5zM2 7a1 1 0 1 0 0 2 1 1 0 0 0 0-2m12 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
        <path d="M11.434 4H4.566L4.5 5.994h.386c.21-1.252.612-1.446 2.173-1.495l.343-.011v6.343c0 .537-.116.665-1.049.748V12h3.294v-.421c-.938-.083-1.054-.21-1.054-.748V4.488l.348.01c1.56.05 1.963.244 2.173 1.496h.386z" />
      </svg>
      Texto
    </button>
    <button type="button" class="flex gap-2 items-center justify-center py-2 px-4 border border-yellow-800 hover:border-yellow-600 text-yellow-800 text-xs rounded-lg conteudo-btn-imagem-adicionar">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z" />
      </svg>
      Imagem
    </button>
    <button type="button" class="flex gap-2 items-center justify-center py-2 px-4 border border-red-800 hover:border-red-600 text-red-800 text-xs rounded-lg conteudo-btn-video-adicionar">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-btn" viewBox="0 0 16 16">
        <path d="M6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814z" />
        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
      </svg>
      Vídeo
    </button>
    <?php if (is_array($conteudos) and count($conteudos) > 1) { ?>
      <button type="button" class="w-max flex gap-2 items-center justify-center py-2 px-4 border border-yellow-800 hover:border-yellow-600 text-yellow-800 text-sm text-xs rounded-lg" onclick="buscarConteudos()">Reorganizar</button>
    <?php } ?>
  </div>
</div>