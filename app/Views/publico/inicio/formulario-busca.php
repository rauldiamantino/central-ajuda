<?php
$titulo = Helper::ajuste('publico_inicio_titulo');
$subtitulo = Helper::ajuste('publico_inicio_subtitulo');
?>
<div class="w-full <?php echo $classesLarguraGeral ?> flex <?php echo $classesBuscaAlinhamento . ' ' . $classesBuscaAlinhamentoTexto; ?>">
  <div class="w-full <?php echo $classesBuscaTamanho; ?> flex flex-col items-start gap-6">

    <?php if ($titulo or $subtitulo) { ?>
      <div class="w-full flex flex-col gap-3" <?php echo $styleFundoBuscaInicio; ?>>
        <?php if ($titulo) { ?>
          <h2 class="w-full font-bold text-3xl"><?php echo $titulo ?></h2>
        <?php } ?>

        <?php if ($subtitulo) { ?>
          <div class="w-full block font-light"><?php echo $subtitulo ?></div>
        <?php } ?>
      </div>
    <?php  } ?>

    <div class="w-full flex flex-col justify-center">

      <?php if ((int) Helper::ajuste('publico_inicio_busca') == 2) { ?>
        <form action="/buscar" method="GET" class="w-full flex justify-start form-busca-inicio">
          <div class="w-full relative">
            <input type="text" name="texto_busca" id="texto_busca" autocomplete="off" placeholder="Descreva sua dúvida..." class="<?php echo CLASSES_DASH_INPUT_BUSCA_GRANDE1; ?>">
            <button type="submit" class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-500 w-5 h-5">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns:xlink="http://www.w3.org/1999/xlink"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M18.5 10.5A7.5 7.5 0 1 1 10 3a7.5 7.5 0 0 1 8.5 7.5z"></path></svg>
            </button>
          </div>
        </form>
      <?php } elseif ((int) Helper::ajuste('publico_inicio_busca') == 3) { ?>
        <form action="/buscar" method="GET" class="w-full flex justify-start form-busca-inicio">
          <div class="w-full relative">
            <input type="text" name="texto_busca" id="texto_busca" autocomplete="off" placeholder="Descreva sua dúvida..." class="<?php echo CLASSES_DASH_INPUT_BUSCA_GRANDE2; ?>">
            <button type="submit" class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-500 w-5 h-5">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns:xlink="http://www.w3.org/1999/xlink"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M18.5 10.5A7.5 7.5 0 1 1 10 3a7.5 7.5 0 0 1 8.5 7.5z"></path></svg>
            </button>
          </div>
        </form>
      <?php } else { ?>
      <?php // Padrão: Helper::ajuste('publico_inicio_busca') == 1 ?>
        <form action="/buscar" method="GET" class="w-full flex justify-start form-busca-inicio">
          <div class="relative w-full">
            <input type="text" name="texto_busca" id="texto_busca" autocomplete="off" placeholder="Descreva sua dúvida..." class="<?php echo CLASSES_DASH_INPUT_BUSCA_GRANDE_TRANSPARENTE; ?> input_busca" />
            <button type="submit" class="absolute right-5 top-1/2 transform -translate-y-1/2 text-white w-5 h-5 transition-all focus:text-gray-600 botao_busca">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns:xlink="http://www.w3.org/1999/xlink"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M18.5 10.5A7.5 7.5 0 1 1 10 3a7.5 7.5 0 0 1 8.5 7.5z"></path></svg>
            </button>
          </div>
        </form>
      <?php } ?>
    </div>
  </div>
</div>