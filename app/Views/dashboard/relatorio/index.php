<div class="w-full">
  <div class="border-b border-slate-300 mb-10 relative w-full h-max flex flex-col">
    <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
      <div class="mb-5 w-full h-full flex flex-col justify-end">
        <h2 class="text-3xl font-semibold flex gap-2">Relatórios</h2>
        <p class="text-gray-600"><?php echo $subtitulo; ?></p>
      </div>
      <?php if (! isset($relInicio)) { ?>
        <div class="py-2 w-full h-full flex gap-2 items-start justify-end">
          <a href="<?php echo $botaoVoltar ? $botaoVoltar : '/dashboard/relatorios'; ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
        </div>
      <?php } ?>
    </div>
  </div>

  <?php if (isset($relInicio)) { ?>
    <ul class="pt-6 w-full">
      <li>1 . <a href="/dashboard/relatorios/feedbacks" class="underline text-blue-600">Feedbacks de artigos publicados</a></li>
      <li>2 . <a href="/dashboard/relatorios/visualizacoes" class="underline text-blue-600">Visualizações de artigos publicados</a></li>
    </ul>
  <?php } ?>

  <?php if (isset($relFeedbacks)) { ?>
    <?php require_once 'feedbacks-1/tabela-feedbacks.php'; ?>
  <?php } ?>

  <?php if (isset($relVisualizacoes)) { ?>
    <?php require_once 'visualizacoes-2/tabela-visualizacoes.php'; ?>
  <?php } ?>
</div>