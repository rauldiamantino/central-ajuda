<div>
  <span class="text-xs font-extralight italic">Status</span>
  <div class="w-max flex flex-row gap-2 justify-start items-center">
    <div class="w-full sm:w-max flex gap-2 justify-start items-center">
      <?php if (isset($artigo['Categoria']['ativo']) and $artigo['Categoria']['ativo'] == ATIVO) { ?>
        <div class="w-max border text-green-900 flex gap-2 items-center justify-center px-4 py-2 leading-none rounded">
          <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
          </svg> -->
          C
        </div>
      <?php } else { ?>
        <div class="w-max border text-red-900 flex gap-2 items-center justify-center px-4 py-2 leading-none rounded">
          <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
          </svg> -->
          C
        </div>
      <?php } ?>
    </div>
    <div class="w-full flex gap-2 justify-start items-center">
      <?php if (isset($artigo['Artigo']['ativo']) and $artigo['Artigo']['ativo'] == ATIVO) { ?>
        <div class="w-max border text-green-900 flex gap-2 items-center justify-center px-4 py-2 leading-none rounded">
          <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
          </svg> -->
          A
        </div>
      <?php } else { ?>
        <div class="w-max border text-red-900 flex gap-2 items-center justify-center px-4 py-2 leading-none rounded">
          <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
          </svg> -->
          A
        </div>
      <?php } ?>
    </div>
  </div>
</div>