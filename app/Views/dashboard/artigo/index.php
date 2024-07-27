<div class="flex gap-6">
  <div class="w-full h-max flex flex-col gap-4 bg-white p-4 border border-slate-200 rounded-lg">
    <table class="table-fixed text-sm text-left text-gray-500 w-full">
      <thead class="text-xs text-gray-700 uppercase bg-slate-50">
        <tr class="bg-white border-b">
          <th scope="col" class="px-6 w-10">
            <input type="checkbox" name="artigo" id="artigoTodos" class="w-10">
          </th>
          <th scope="col" class="px-6 py-3 w-16">ID</th>
          <th scope="col" class="px-6 py-3 w-24">Status</th>
          <th scope="col" class="px-6 py-3 w-56">Título</th>
          <th scope="col" class="px-6 py-3 w-32">Categoria</th>
          <th scope="col" class="px-6 py-3 w-32 min-w-[120px]">Criado</th>
          <th scope="col" class="px-6 py-3 w-32 min-w-[120px]">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($artigos as $chave => $linha) : ?>
          <tr class="bg-white border-b hover:bg-slate-50">
            <td class="whitespace-nowrap p-6 w-10">
              <input type="checkbox" name="artigo" id="artigoUnico" class="w-10">
            </td>
            <?php foreach ($linha as $subChave => $subLinha) : ?>
              <?php if ($subChave == 'Artigo.id') { ?>
                <td class="whitespace-nowrap p-6 w-16"><?php echo $subLinha ?></td>
              <?php } ?>
              <?php if ($subChave == 'Artigo.ativo') { ?>
                <td class="whitespace-nowrap p-6 uppercase text-xs w-24"><?php echo $subLinha == 1 ? 'Ativo' : 'Inativo' ?></td>
              <?php } ?>
              <?php if ($subChave == 'Artigo.titulo') { ?>
                <td class="whitespace-normal p-6 w-56"><?php echo $subLinha ?></td>
              <?php } ?>
              <?php if ($subChave == 'Categoria.nome') { ?>
                <td class="whitespace-normal p-6 w-32"><?php echo $subLinha ?></td>
              <?php } ?>
              <?php if ($subChave == 'Artigo.criado') { ?>
                <td class="whitespace-normal p-6 w-32 min-w-[120px]"><?php echo $subLinha ?></td>
              <?php } ?>
            <?php endforeach; ?>
            <td class="whitespace-normal p-6 w-32 min-w-[120px] flex gap-2">
              <button type="button" class="flex gap-2 items-center justify-center py-2 px-3 bg-blue-800 text-white rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg>
                Editar
              </button>
              <button type="button" class="flex gap-2 items-center justify-center py-2 px-3 bg-red-800 text-white rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                  <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                </svg>
                Apagar
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="w-full flex gap-1 justify-start">
      <?php if ($pagina > 1) { ?>
        <a href="/dashboard/artigos?pag=<?php echo $pagina - 1 ?>" class="border border-transparent bg-slate-50 hover:bg-slate-100 text-gray-500 hover:text-black p-3 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" stroke="currentColor" stroke-width="2" />
          </svg>
        </a>
      <?php } ?>

      <?php if ($pagina <= 1) { ?>
        <div class="border border-slate-100 text-gray-500 p-3 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" stroke="currentColor" stroke-width="2" />
          </svg>
        </div>
      <?php } ?>

      <form action="/dashboard/artigos" method="get">
        <input type="number" name="pag" value="<?php echo $pagina ?>" class="p-2 w-16 border-2 border-slate-100 text-center [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none rounded-lg">
      </form>

      <?php if ($pagina < $paginasTotal) { ?>
        <a href="/dashboard/artigos?pag=<?php echo $pagina + 1 ?>" class="border border-transparent bg-slate-50 hover:bg-slate-100 text-gray-500 hover:text-black p-3 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" stroke="currentColor" stroke-width="2" />
          </svg>
        </a>
      <?php } ?>

      <?php if ($pagina == $paginasTotal) { ?>
        <div class="border border-slate-100 text-gray-500 p-3 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" stroke="currentColor" stroke-width="2" />
          </svg>
        </div>
      <?php } ?>
    </div>
  </div>
</div>