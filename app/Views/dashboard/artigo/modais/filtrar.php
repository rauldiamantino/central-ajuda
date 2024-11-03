<?php
$status = [
  'Ativo' => 1,
  'Inativo' => 0,
  'Todos' => 'null',
];

$statusFiltro = $filtroAtual['status'] ?? 'null';
?>

<dialog class="border border-slate-200 p-4 w-full sm:w-[440px] rounded-lg shadow modal-artigos-filtrar-cate">
  <div class="w-full flex flex-col gap-4 mb-4 modal-artigos-filtrar-cate-blocos">
    <div class="flex gap-6">
      <div class="w-full flex flex-col">
        <label for="filtrar-id">ID</label>
        <input type="number" id="filtrar-id" class="<?php echo CLASSES_DASH_INPUT_BUSCA; ?> input-number-none" value=<?php echo $filtroAtual['id'] ?? ''; ?>>
      </div>
    <div class="w-full flex flex-col">
      <label for="filtrar-status">Status</label>
      <select id="filtrar-status" class="<? echo CLASSES_DASH_INPUT_BUSCA; ?> modal-categorias-filtrar-select">
        <?php foreach ($status as $chave => $linha): ?>
          <option value="<?php echo $linha; ?>" <?php echo $linha == $statusFiltro ? 'selected' : ''; ?>><?php echo $chave; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    </div>
    <div>
      <label for="filtrar-titulo">Título</label>
      <input type="text" id="filtrar-titulo" class="<?php echo CLASSES_DASH_INPUT_BUSCA; ?>" value="<?php echo $filtroAtual['titulo'] ?? ''; ?>">
    </div>
    <div>
      <label for="filtrar-categoria">Categoria</label>
      <select id="filtrar-categoria" class="<?php echo CLASSES_DASH_INPUT_BUSCA; ?> modal-categorias-filtrar-select"></select>
    </div>
  </div>
  <div class="w-full flex justify-between items-center">
    <div class="mt-4 w-full flex gap-2 justify-start">
      <button type="button" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?> modal-artigos-filtrar-cate-btn-cancelar">Voltar</button>
      <button type="button" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> modal-artigos-filtrar-cate-btn-confirmar">Confirmar</button>
    </div>

    <div class="mt-4 w-full flex gap-2 justify-end">
      <button type="button" class="<?php echo CLASSES_DASH_BUTTON_LIMPAR; ?> modal-artigos-filtrar-cate-btn-limpar">Limpar</button>
    </div>
  </div>
</dialog>