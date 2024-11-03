<?php
// Prepara filtros
$filtrarId = isset($filtroAtual['id']) ? '&id=' . $filtroAtual['id'] : '';
$filtrarStatus = isset($filtroAtual['status']) ? '&status=' . $filtroAtual['status'] : '';
$filtrarTitulo = isset($filtroAtual['titulo']) ? '&titulo=' . $filtroAtual['titulo'] : '';
$filtrarCategoria = isset($filtroAtual['categoria_id']) && $filtroAtual['categoria_id'] >= 0 ? '&categoria_id=' . $filtroAtual['categoria_id'] : '';
?>

<?php // Paginação ?>
<div class="mt-2 py-2 h-max flex flex-col gap-2 md:gap-1 md:flex-row justify-start items-center">
  <div class="flex justify-center items-center gap-1">
    <?php if ($pagina > 1) { ?>
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos'); ?>?pagina=<?php echo $pagina - 1 . $filtrarId . $filtrarStatus . $filtrarTitulo . $filtrarCategoria; ?>" class="border-2 border-slate-200 hover:border-slate-500 text-gray-500 p-3 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" stroke="currentColor" stroke-width="2" />
        </svg>
      </a>
    <?php } ?>

    <?php if ($pagina <= 1) { ?>
      <div class="border-2 border-slate-200 text-gray-500 p-3 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" stroke="currentColor" stroke-width="2" />
        </svg>
      </div>
    <?php } ?>

    <form action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos'); ?>" method="get">
      <input type="number" name="pagina" value="<?php echo $pagina ?>" class="p-2 w-16 bg-slate-100 border-2 hover:border-slate-500 text-center [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none rounded-lg artigo-numero-pagina">

      <?php if ($filtrarId) { ?>
        <input type="hidden" name="id" value="<?php echo $filtroAtual['id'] ?>">
      <?php } ?>

      <?php if ($filtrarStatus) { ?>
        <input type="hidden" name="status" value="<?php echo $filtroAtual['status'] ?>">
      <?php } ?>

      <?php if ($filtrarTitulo) { ?>
        <input type="hidden" name="titulo" value="<?php echo $filtroAtual['titulo'] ?>">
      <?php } ?>

      <?php if ($filtrarCategoria) { ?>
        <input type="hidden" name="categoria_id" value="<?php echo $filtroAtual['categoria_id'] ?>">
      <?php } ?>
    </form>

    <?php if ($pagina < $paginasTotal) { ?>
      <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos'); ?>?pagina=<?php echo $pagina + 1 . $filtrarId . $filtrarStatus . $filtrarTitulo . $filtrarCategoria ?>" class="border-2 border-slate-200 hover:border-slate-500 text-gray-500 p-3 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" stroke="currentColor" stroke-width="2" />
        </svg>
      </a>
    <?php } ?>

    <?php if ($pagina == $paginasTotal) { ?>
      <div class="border-2 border-slate-200 text-gray-500 p-3 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" stroke="currentColor" stroke-width="2" />
        </svg>
      </div>
    <?php } ?>
  </div>

  <div class="pl-2 text-xs">Exibindo <?php echo $intervaloInicio; ?>-<?php echo $intervaloFim; ?> de <?php echo $artigosTotal; ?></div>
</div>