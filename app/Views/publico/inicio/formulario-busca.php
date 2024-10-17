<form action="<?php echo baseUrl('/buscar'); ?>" method="GET" class="w-full flex justify-start">
  <div class="w-full relative">
    <input type="text" name="texto_busca" id="texto_busca" placeholder="Descreva sua dúvida..." class="<?php echo CLASSES_DASH_INPUT_BUSCA_GRANDE; ?>" autocomplete="off">
    <button type="submit" class="p-1 flex items-center justify-center absolute top-0 bottom-0 right-3 text-gray-600">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
      </svg>
    </button>
  </div>
</form>