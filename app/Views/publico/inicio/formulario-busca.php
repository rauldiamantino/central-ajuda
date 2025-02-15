<?php if ((int) Helper::ajuste('publico_inicio_foto') == ATIVO) { ?>
  <form action="/buscar" method="GET" class="w-full flex justify-start">
    <div class="w-full relative">
      <input type="text" name="texto_busca" id="texto_busca" placeholder="Descreva sua dúvida..." class="<?php echo CLASSES_DASH_INPUT_BUSCA_GRANDE; ?> <?php echo $classesFormularioBuscaInput; ?>" autocomplete="off" <?php echo $styleFormularioBuscaInput; ?>>
      <button type="submit" class="p-1 flex items-center justify-center absolute top-0 bottom-0 right-5 text-gray-600">
        <span class="<?php echo $classesFormularioBuscaLupa; ?>" <?php echo $styleFormularioBuscaLupa; ?>>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
          </svg>
        </span>
      </button>
    </div>
  </form>
<?php } else { ?>
<form class="w-full flex justify-start mt-10">
  <div class="relative w-full">
    <input id="inputBusca" type="text" autocomplete="off" placeholder="Descreva sua dúvida..." class="<?php echo CLASSES_DASH_INPUT_BUSCA_GRANDE_EFEITO; ?>" />
    <button id="botaoBusca" type="submit" class="absolute right-5 top-1/2 transform -translate-y-1/2 text-white w-5 h-5 transition-all focus:text-gray-600">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns:xlink="http://www.w3.org/1999/xlink"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M18.5 10.5A7.5 7.5 0 1 1 10 3a7.5 7.5 0 0 1 8.5 7.5z"></path></svg>
    </button>
  </div>
</form>

<script>
  const inputBusca = document.querySelector('#inputBusca');
  const botaoBusca = document.querySelector('#botaoBusca');

  inputBusca.addEventListener('focus', () => {
    botaoBusca.classList.add('text-gray-600');
    botaoBusca.classList.remove('text-white');
  });

  inputBusca.addEventListener('blur', () => {
    botaoBusca.classList.add('text-white');
    botaoBusca.classList.remove('text-gray-600');
  });
</script>
<?php } ?>
