<?php
$filtrar = [
  'feedbacks-1' => 'filtrarFeedbacks()',
  'visualizacoes-2' => 'filtrarVisualizacoes()',
];
?>

<div class="w-full">
  <div class="border-b border-slate-300 relative w-full h-max flex flex-col">
    <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
      <div class="mb-5 w-full h-full flex flex-col justify-end">
        <h2 class="text-3xl font-semibold flex gap-2">Relatórios</h2>
        <p class="text-gray-600"><?php echo $subtitulo; ?></p>
      </div>
      <?php if (! isset($relInicio)) { ?>
        <div class="w-full flex gap-2 items-center">
          <div class="py-2 w-full h-full flex gap-2 items-start justify-end">
            <a href="<?php echo $botaoVoltar ? $botaoVoltar : '/dashboard/relatorios'; ?>" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
          </div>
          <?php // Menu auxiliar ?>
          <div class="relative text-sm">
            <button type="button" class="p-3 bg-gray-400/10 hover:bg-gray-400/20 rounded-lg cursor-pointer" onclick="document.querySelector('.menu-auxiliar-categorias').classList.toggle('hidden')">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
              </svg>
            </button>
            <ul class="absolute top-12 right-0 lg:-right-10 border border-slate-300 lg:mx-10 flex flex-col justify-center bg-white text-gray-600 rounded-md shadow hidden menu-auxiliar menu-auxiliar-categorias">
              <li class="px-4 py-3">
                <button type="button" onclick="<?php echo $filtrar[ $tipo ] ?>" class="flex gap-2 items-center hover:text-gray-950">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
                    <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/>
                  </svg>
                  <span class="whitespace-nowrap">Filtrar</span>
                </button>
              </li>
            </ul>
          </div>
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
    <?php require_once 'feedbacks-1/filtrar.php'; ?>
  <?php } ?>

  <?php if (isset($relVisualizacoes)) { ?>
    <?php require_once 'visualizacoes-2/tabela-visualizacoes.php'; ?>
    <?php require_once 'visualizacoes-2/filtrar.php'; ?>
  <?php } ?>
</div>