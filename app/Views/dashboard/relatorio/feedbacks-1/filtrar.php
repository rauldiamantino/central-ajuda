<?php
$codigoFiltro = $filtroAtual['codigo'] ?? '';
$dataInicioFiltro = $filtroAtual['data_inicio'] ?? '';
$dataFimFiltro = $filtroAtual['data_fim'] ?? '';
?>

<dialog class="border border-slate-200 p-4 w-full sm:w-[440px] rounded-lg shadow modal-filtrar-feedbacks-1">
  <div class="w-full flex flex-col gap-4 mb-4 modal-filtrar-feedbacks-1-blocos">
    <div class="flex gap-4">
      <div class="w-full flex flex-col">
        <label for="filtrar-feedbacks-1-codigo">Código</label>
        <input type="number" id="filtrar-feedbacks-1-codigo" class="<?php echo CLASSES_DASH_INPUT_BUSCA; ?> input-number-none" value=<?php echo $codigoFiltro; ?>>
      </div>
    </div>
    <div class="flex gap-4">
      <div class="w-full flex flex-col">
        <label for="filtrar-feedbacks-1-data-inicio">Data início</label>
        <input type="text" placeholder="dd/mm/aaaa" id="filtrar-feedbacks-1-data-inicio" class="<?php echo CLASSES_DASH_INPUT_BUSCA; ?> data-feedback" value=<?php echo $dataInicioFiltro; ?>>
      </div>
      <div class="w-full flex flex-col">
        <label for="filtrar-feedbacks-1-data-fim">Data fim</label>
        <input type="text" placeholder="dd/mm/aaaa" id="filtrar-feedbacks-1-data-fim" class="<?php echo CLASSES_DASH_INPUT_BUSCA; ?> data-feedback" value=<?php echo $dataFimFiltro; ?>>
      </div>
    </div>
    <div>
      <label for="filtrar-feedbacks-1-categoria">Categoria</label>
      <select id="filtrar-feedbacks-1-categoria" class="<?php echo CLASSES_DASH_INPUT_BUSCA; ?> modal-filtrar-feedbacks-1-select" autofocus></select>
    </div>
  </div>
  <div class="mt-4 w-full flex flex-wrap sm:flex-nowrap gap-2 justify-center sm:justify-end">
    <button type="button" class="<?php echo CLASSES_DASH_BUTTON_LIMPAR; ?> modal-filtrar-feedbacks-1-btn-limpar">Limpar</button>
    <button type="button" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?> modal-filtrar-feedbacks-1-btn-cancelar">Voltar</button>
    <button type="button" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-filtrar-feedbacks-1-btn-confirmar">Confirmar</button>
  </div>
</dialog>