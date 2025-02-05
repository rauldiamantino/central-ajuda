<?php
// Padrão
$classes_colunas = 'w-full h-max grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 justify-start gap-4';

if ((int) $this->buscarAjuste('publico_inicio_template') == 1) {
  $classes_colunas = 'w-full h-max grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 justify-start gap-4';
}
elseif ((int) $this->buscarAjuste('publico_inicio_template') == 2) {
  $classes_colunas = 'w-full h-max grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 justify-start gap-4';
}
elseif ((int) $this->buscarAjuste('publico_inicio_template') == 3) {
  $classes_colunas = 'w-full h-max grid grid-cols-1 sm:grid-cols-2 justify-start gap-4';
}
elseif ((int) $this->buscarAjuste('publico_inicio_template') == 4) {
  $classes_colunas = 'w-full h-max grid grid-cols-1 justify-start gap-4';
}

// Padrão
$classes_alinhamento_1 = 'items-center text-center';
$classes_alinhamento_2 = 'flex flex-col gap-4 items-center';

if ((int) $this->buscarAjuste('publico_inicio_template_alinhamento') == 1) {
  $classes_alinhamento_1 = 'items-center text-center';
  $classes_alinhamento_2 = 'w-full flex flex-col gap-4 items-center';
}
elseif ((int) $this->buscarAjuste('publico_inicio_template_alinhamento') == 2) {
  $classes_alinhamento_1 = 'items-start text-start';
  $classes_alinhamento_2 = 'flex flex-col gap-4 items-start';

}
elseif ((int) $this->buscarAjuste('publico_inicio_template_alinhamento') == 3) {
  $classes_alinhamento_1 = 'items-start text-start';
  $classes_alinhamento_2 = 'flex gap-4 items-center';
}
elseif ((int) $this->buscarAjuste('publico_inicio_template_alinhamento') == 4) {
  $classes_alinhamento_1 = 'items-center text-center';
  $classes_alinhamento_2 = 'flex gap-4 items-center justify-center';
}
?>

<div class="mx-auto w-full flex flex-col items-center gap-16 py-12">
  <div class="w-full flex flex-col gap-2">
    <h3 class="font-extralight text-xs text-gray-800">CATEGORIAS</h3>
    <div class="<?php echo $classes_colunas; ?>">
      <?php foreach ($categorias as $chave => $linha): ?>
        <a href="<?php echo '/categoria/' . $linha['Categoria']['id'] . '/' . $this->gerarSlug($linha['Categoria']['nome']); ?>" class="border border-slate-200 shadow p-5 h-full min-h-[200px] bg-white flex flex-col justify-start gap-4 rounded-lg hover:scale-105 duration-100 <?php echo $classes_alinhamento_1; ?>">

          <div class="w-full <?php echo $classes_alinhamento_2; ?>">

            <?php if ($linha['Categoria']['icone'] and $this->iconeExiste($linha['Categoria']['icone'])) { ?>
              <div class="w-max">
                <div class="w-12 h-12 pers-publico-icones template-cor-<?php echo $corPrimaria; ?>">
                  <?php echo $this->renderIcone($linha['Categoria']['icone']); ?>
                </div>
              </div>
            <?php } ?>

            <h3 class="text-xl bloco-categoria-nome break-words w-max"><?php echo $linha['Categoria']['nome'] ?></h3>
          </div>

          <div class="font-extralight text-gray-400 bloco-categoria-descricao"><?php echo $linha['Categoria']['descricao'] ?></div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
