<dialog class="relative px-4 pt-4 w-full sm:w-[440px] rounded-lg shadow modal-conteudos-organizar" data-artigo-id="<?php echo $artigo['Artigo']['id']?>">
  <h3 class="flex p-2 pb-4 font-semibold">Reorganizar</h3>
  <div class="w-full flex flex-col px-10 sm:px-2 gap-1 text-sm handle modal-conteudos-organizar-blocos">
  </div>
  <div class="mt-2 sticky bottom-0 py-4 w-full flex gap-2 justify-end bg-white">
    <button type="button" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?> modal-conteudos-organizar-btn-cancelar">Voltar</button>
    <button type="button" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-conteudos-organizar-btn-confirmar">Confirmar</button>
  </div>
</dialog>