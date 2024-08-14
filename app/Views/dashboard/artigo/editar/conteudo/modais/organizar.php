<dialog class="p-4 sm:w-[440px] rounded-lg shadow modal-conteudos-organizar" data-artigo-id="<?php echo $artigo['Artigo.id']?>">
  <h3 class="flex p-2 pb-4 font-semibold">Reorganizar</h3>
  <div class="w-full flex flex-col gap-1 text-sm handle modal-conteudos-organizar-blocos">
  </div>
  <div class="mt-4 w-full flex flex-col sm:flex-row gap-3 font-semibold text-xs sm:justify-end justify-center">
    <button type="button" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-gray-700 rounded-lg modal-conteudos-organizar-btn-cancelar w-full">Cancelar</button>
    <button type="button" class="border border-blue-800 flex gap-2 items-center justify-center py-2 px-3 hover:bg-blue-600 bg-blue-800 text-white rounded-lg modal-conteudos-organizar-btn-confirmar w-full">Confirmar</button>
  </div>
</dialog>