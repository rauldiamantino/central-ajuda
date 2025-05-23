<?php if ($paginasTotal > 1) { ?>
  <?php // Paginação ?>
  <div class="mt-10 sticky inset-x-0 w-full h-max py-2 flex justify-start items-center gap-1 rounded-md">
    <?php if ($pagina > 1) { ?>
      <a href="<?php echo '/buscar?texto_busca=' . $textoBusca . '&pagina=' . $pagina - 1; ?>" class="border border-gray-200 bg-gray-50 hover:bg-gray-200 text-gray-500 hover:text-black p-3 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" stroke="currentColor" stroke-width="2" />
        </svg>
      </a>
    <?php } ?>

    <?php if ($pagina <= 1) { ?>
      <div class="border border-gray-200 text-gray-500 p-3 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" stroke="currentColor" stroke-width="2" />
        </svg>
      </div>
    <?php } ?>

    <form action="<?php echo '/buscar'; ?>" method="get">
      <input type="hidden" name="texto_busca" value="<?php echo $textoBusca ?>">
      <input type="number" name="pagina" value="<?php echo $pagina ?>" class="p-2 w-16 border-2 border-gray-200 text-center [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none rounded-lg artigo-numero-pagina">
    </form>

    <?php if ($pagina < $paginasTotal) { ?>
      <a href="<?php echo '/buscar?texto_busca=' . $textoBusca . '&pagina=' . $pagina + 1; ?>" class="border border-gray-200 bg-gray-50 hover:bg-gray-200 text-gray-500 hover:text-black p-3 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" stroke="currentColor" stroke-width="2" />
        </svg>
      </a>
    <?php } ?>

    <?php if ($pagina == $paginasTotal) { ?>
      <div class="border border-gray-200 text-gray-500 p-3 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" stroke="currentColor" stroke-width="2" />
        </svg>
      </div>
    <?php } ?>

    <div class="pl-2 text-xs">Exibindo <?php echo $intervaloInicio; ?>-<?php echo $intervaloFim; ?> de <?php echo $artigosTotal; ?></div>
  </div>
<?php } ?>