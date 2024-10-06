<div class="w-full flex flex-col gap-1">
  <h2>Adicionar conteúdo</h2>
  <div class="border border-slate-300 p-4 w-full flex gap-5 rounded-md shadow bg-white">
    <div class="flex flex-col gap-6 w-full form-conteudo">
      <input type="hidden" name="artigo.id" value="<?php echo $artigo['Artigo.id'] ?>">
      <div class="w-full px-2 flex gap-5 flex-col md:flex-row conteudo-botoes-adicionar">
        <div class="w-full flex justify-between md:justify-start gap-5 md:gap-8">
          <a href="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/conteudo/adicionar?artigo_id=<?php echo $artigo['Artigo.id'] ?>&tipo=1" class="w-max flex gap-2 items-center justify-center text-black text-sm hover:underline conteudo-btn-texto-adicionar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M1.5 2.5A1.5 1.5 0 0 1 3 1h10a1.5 1.5 0 0 1 1.5 1.5v3.563a2 2 0 0 1 0 3.874V13.5A1.5 1.5 0 0 1 13 15H3a1.5 1.5 0 0 1-1.5-1.5V9.937a2 2 0 0 1 0-3.874zm1 3.563a2 2 0 0 1 0 3.874V13.5a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V9.937a2 2 0 0 1 0-3.874V2.5A.5.5 0 0 0 13 2H3a.5.5 0 0 0-.5.5zM2 7a1 1 0 1 0 0 2 1 1 0 0 0 0-2m12 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
              <path d="M11.434 4H4.566L4.5 5.994h.386c.21-1.252.612-1.446 2.173-1.495l.343-.011v6.343c0 .537-.116.665-1.049.748V12h3.294v-.421c-.938-.083-1.054-.21-1.054-.748V4.488l.348.01c1.56.05 1.963.244 2.173 1.496h.386z" />
            </svg>
            Texto
          </a>
          <a href="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/conteudo/adicionar?artigo_id=<?php echo $artigo['Artigo.id'] ?>&tipo=2" class="w-max flex gap-2 items-center justify-center hover:underline text-black text-sm conteudo-btn-imagem-adicionar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
              <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z" />
            </svg>
            Imagem
          </a>
          <a href="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/conteudo/adicionar?artigo_id=<?php echo $artigo['Artigo.id'] ?>&tipo=3" class="w-max flex gap-2 items-center justify-center hover:underline text-black text-sm conteudo-btn-video-adicionar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814z" />
              <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
            </svg>
            Vídeo
          </a>
        </div>
        <button type="button" class="w-max flex gap-2 items-center hover:underline justify-center text-black text-sm rounded-lg" <?php echo count($conteudos) > 1 ? 'onclick="buscarConteudos()"' : '' ?>>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
          </svg>
          Reorganizar
        </button>
      </div>
    </div>
  </div>
</div>