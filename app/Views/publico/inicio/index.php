<?php
// Padrão
$classes_colunas = 'w-full h-max grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 justify-start gap-5';
$classes_colunas_efeito = '';
$classes_colunas_icones_efeito = 'pers-publico-icones template-cor-' . Helper::ajuste('publico_cor_primaria');
$classes_colunas_descricao_efeito = '';

if ((int) Helper::ajuste('publico_inicio_template') == 1) {
  $classes_colunas = 'w-full h-max grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 justify-start gap-5';
}
elseif ((int) Helper::ajuste('publico_inicio_template') == 2) {
  $classes_colunas = 'w-full h-max grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 justify-start gap-5';
}
elseif ((int) Helper::ajuste('publico_inicio_template') == 3) {
  $classes_colunas = 'w-full h-max grid grid-cols-1 sm:grid-cols-2 justify-start gap-5';
}
elseif ((int) Helper::ajuste('publico_inicio_template') == 4) {
  $classes_colunas = 'w-full h-max grid grid-cols-1 justify-start gap-5';
}

if ((int) Helper::ajuste('publico_inicio_colunas_efeito') == 1) {
  $classes_colunas_efeito = 'hover:scale-105';
}
elseif ((int) Helper::ajuste('publico_inicio_colunas_efeito') == 2) {
  $classes_colunas_efeito = 'hover:border-slate-500/60';
}
elseif ((int) Helper::ajuste('publico_inicio_colunas_efeito') == 3) {
  $classes_colunas_efeito = 'hover:bg-slate-100/50';
}
elseif ((int) Helper::ajuste('publico_inicio_colunas_efeito') == 4) {
  $classes_colunas_efeito = 'pers-publico-colunas template-cor-' . Helper::ajuste('publico_cor_primaria');
  $classes_colunas_icones_efeito = 'pers-publico-colunas-icones template-cor-' . Helper::ajuste('publico_cor_primaria');
  $classes_colunas_descricao_efeito = 'pers-publico-colunas-descricao template-cor-' . Helper::ajuste('publico_cor_primaria');
}

// Padrão
$classes_alinhamento_1 = 'items-center text-center';
$classes_alinhamento_2 = 'flex flex-col gap-5 items-center';

if ((int) Helper::ajuste('publico_inicio_template_alinhamento') == 1) {
  $classes_alinhamento_1 = 'items-center text-center';
  $classes_alinhamento_2 = 'w-full flex flex-col gap-5 items-center';
}
elseif ((int) Helper::ajuste('publico_inicio_template_alinhamento') == 2) {
  $classes_alinhamento_1 = 'items-start text-start';
  $classes_alinhamento_2 = 'flex flex-col gap-5 items-start';

}
elseif ((int) Helper::ajuste('publico_inicio_template_alinhamento') == 3 and ((int) Helper::ajuste('publico_largura_geral') == 1 or (int) Helper::ajuste('publico_inicio_template') > 2)) {
  // Somente largura 100%
  $classes_alinhamento_1 = 'items-start text-start';
  $classes_alinhamento_2 = 'flex gap-5 items-center';
}
?>

<div class="mx-auto w-full flex flex-col items-center gap-16 py-12">
  <div class="w-full flex flex-col gap-2">
    <h3 class="font-extralight text-xs text-gray-800">CATEGORIAS</h3>
    <div class="<?php echo $classes_colunas; ?>">
      <?php foreach ($categorias as $chave => $linha): ?>
        <a href="<?php echo '/categoria/' . $linha['Categoria']['id'] . '/' . $this->gerarSlug($linha['Categoria']['nome']); ?>" class="border border-slate-200 shadow p-5 h-full min-h-[200px] bg-white flex flex-col justify-start gap-5 rounded-lg <?php echo $classes_colunas_efeito . ' ' . $classes_alinhamento_1; ?> duration-100">

          <div class="w-full <?php echo $classes_alinhamento_2; ?>">

            <?php if ($linha['Categoria']['icone'] and $this->iconeExiste($linha['Categoria']['icone'])) { ?>
              <div class="w-max">
                <div class="w-12 h-12 <?php echo $classes_colunas_icones_efeito; ?>">
                  <?php echo $this->renderIcone($linha['Categoria']['icone']); ?>
                </div>
              </div>
            <?php } ?>

            <h3 class="text-xl bloco-categoria-nome break-words"><?php echo $linha['Categoria']['nome'] ?></h3>
          </div>

          <div class="font-extralight text-gray-400 <?php echo $classes_colunas_descricao_efeito; ?> bloco-categoria-descricao"><?php echo $linha['Categoria']['descricao'] ?></div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
