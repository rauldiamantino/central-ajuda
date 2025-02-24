<div>
  <span class="text-xs font-extralight italic">Status</span>
  <div class="w-max flex flex-row gap-2 justify-start items-center">
    <div class="w-full sm:w-max flex gap-2 justify-start items-center">
      <?php if (isset($artigo['Categoria']['ativo']) and $artigo['Categoria']['ativo'] == ATIVO) { ?>
        <div class="w-max border text-green-900 flex gap-2 items-center justify-center px-4 py-1 text-xs leading-none rounded">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          Categoria
        </div>
      <?php } else { ?>
        <div class="w-max border text-red-900 flex gap-2 items-center justify-center px-4 py-1 text-xs leading-none rounded">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          Categoria
        </div>
      <?php } ?>
    </div>
    <div class="w-full flex gap-2 justify-start items-center">
      <?php if (isset($artigo['Artigo']['ativo']) and $artigo['Artigo']['ativo'] == ATIVO) { ?>
        <div class="w-max border text-green-900 flex gap-2 items-center justify-center px-4 py-1 text-xs leading-none rounded">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          Artigo
        </div>
      <?php } else { ?>
        <div class="w-max border text-red-900 flex gap-2 items-center justify-center px-4 py-1 text-xs leading-none rounded">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          Artigo
        </div>
      <?php } ?>
    </div>
  </div>
</div>