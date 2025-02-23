<div class="w-full max-w-[990px] flex flex-col gap-1 menu-adicionar">
  <div class="w-full flex gap-5">
    <div class="flex flex-col gap-6 w-full form-conteudo">
      <input type="hidden" name="artigo.id" value="<?php echo $artigo['Artigo']['id'] ?>">
      <div class="w-full flex gap-5 flex-col md:flex-row conteudo-botoes-adicionar">
        <div class="w-full flex flex-wrap sm:flex-nowrap justify-start gap-2 md:gap-5">
          <button type="button" class="border-2 border-dashed border-gray-300 rounded-lg px-10 py-1 sm:py-4 md:py-6 w-full text-left hover:bg-gray-200/60 duration-100 flex flex-row gap-1 sm:gap-4 items-center justify-center text-gray-700 text-sm conteudo-btn-texto-adicionar" onclick="abrirModalAdicionar('texto')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-slate-400/50">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            texto
          </button>
          <button type="button" class="border-2 border-dashed border-gray-300 rounded-lg px-10 py-1 sm:py-4 md:py-6 w-full text-left hover:bg-gray-200/60 duration-100 flex flex-row gap-1 sm:gap-4 items-center justify-center text-gray-700 text-sm botao-abrir-menu-adicionar-imagem" onclick="abrirModalAdicionar('imagem')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-slate-400/50">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            imagem
          </button>
          <button type="button" class="border-2 border-dashed border-gray-300 rounded-lg px-10 py-1 sm:py-4 md:py-6 w-full text-left hover:bg-gray-200/60 duration-100 flex flex-row gap-1 sm:gap-4 items-center justify-center text-gray-700 text-sm botao-abrir-menu-adicionar-video" onclick="abrirModalAdicionar('video')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-slate-400/50">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            vídeo
          </button>
        </div>
      </div>
    </div>
  </div>
</div>