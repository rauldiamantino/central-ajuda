<div class="relative w-full min-h-full flex flex-col bg-white">
  <table class="table-fixed text-sm text-left text-gray-500">
    <div class="p-4 w-full">
    <h2 class="text-2xl font-semibold">Todos os artigos</h2>
  </div>
    <thead class="p-6 text-xs font-light text-gray-500 uppercase bg-slate-100">
      <tr class="border-b">
        <th class="p-6 w-22">ID</th>
        <th class="p-6 min-w-64 max-w-64">Título</th>
        <th class="p-6 min-w-56 max-w-56">Categoria</th>
        <th class="p-6 w-36 min-w-[120px]">Autor</th>
        <th class="p-6 w-36 min-w-[120px]">Criado</th>
        <th class="p-6 w-20">Status</th>
        <th class="p-6 w-32 min-w-max">Ações</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php foreach ($artigos as $chave => $linha) : ?>
        <tr class="hover:bg-slate-100">
          <?php foreach ($linha as $subChave => $subLinha) : ?>
            <?php if ($subChave == 'Artigo.id') { ?>
              <td class="whitespace-nowrap p-6 w-22"><?php echo $subLinha ?></td>
            <?php } ?>
            <?php if ($subChave == 'Artigo.titulo') { ?>
              <td class="whitespace-nowrap p-6 min-w-64 max-w-64 font-semibold text-gray-700"><?php echo $subLinha ?></td>
            <?php } ?>
            <?php if ($subChave == 'Categoria.nome') { ?>
              <td class="whitespace-nowrap p-6 min-w-56 max-w-56"><?php echo $subLinha ?></td>
            <?php } ?>
            <?php if ($subChave == 'Usuario.nome') { ?>
              <td class="whitespace-nowrap p-6 w-36 min-w-[120px]"><?php echo $subLinha ?></td>
            <?php } ?>
            <?php if ($subChave == 'Artigo.criado') { ?>
              <td class="whitespace-nowrap p-6 w-36 min-w-[120px] truncate"><?php echo $subLinha ?></td>
            <?php } ?>
            <?php if ($subChave == 'Artigo.ativo') { ?>
              <td class="whitespace-nowrap p-6 w-20">
                <?php if ($subLinha == 1) { ?>
                  <div class="flex items-center gap-2">
                    <span class="text-green-800">
                      <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                        <circle cx="8" cy="8" r="8"/>
                      </svg>
                    </span>
                    <span>Ativo</span>
                  </div>
                <?php } ?>
                <?php if ($subLinha == 0) { ?>
                  <div class="flex items-center gap-2">
                    <span class="text-red-800">
                      <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                        <circle cx="8" cy="8" r="8"/>
                      </svg>
                    </span>
                    <span>Inativo</span>
                  </div>
                <?php } ?>
              </td>
            <?php } ?>
          <?php endforeach; ?>
          <td class="whitespace-normal p-6 w-32 min-w-max flex gap-2 items-center">
            <a href="/dashboard/artigo/editar/<?php echo $linha['Artigo.id'] ?>" class="flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg js-dashboard-artigos-editar" data-artigo-id="<?php echo $linha['Artigo.id'] ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
              </svg>
              Editar
            </a>
            <button type="button" class="flex gap-2 items-center justify-center py-2 px-4 bg-red-800 hover:bg-red-600 text-white text-xs rounded-lg js-dashboard-artigos-remover" data-artigo-id="<?php echo $linha['Artigo.id'] ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
              </svg>
              Remover
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php // Paginação ?>
  <div class="sticky inset-x-0 bottom-0 border-t border-slate-200 w-full h-max py-2 flex justify-center items-center gap-1 bg-white">
    <?php if ($pagina > 1) { ?>
      <a href="/dashboard/artigos?pagina=<?php echo $pagina - 1 ?>" class="border border-slate-100 bg-slate-100 hover:bg-slate-200 text-gray-500 hover:text-black p-3 rounded-lg">
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
      <input type="number" name="pagina" value="<?php echo $pagina ?>" class="p-2 w-16 border-2 border-slate-100 text-center [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none rounded-lg artigo-numero-pagina">
    </form>

    <?php if ($pagina < $paginasTotal) { ?>
      <a href="/dashboard/artigos?pagina=<?php echo $pagina + 1 ?>" class="border border-slate-100 bg-slate-100 hover:bg-slate-200 text-gray-500 hover:text-black p-3 rounded-lg">
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

    <div class="pl-2 text-xs">Exibindo <?php echo $intervaloInicio; ?>-<?php echo $intervaloFim; ?> de <?php echo $artigosTotal; ?></div>
  </div>
</div>

<dialog class="p-4 sm:w-[440px] rounded-lg shadow modal-artigo-remover">
  <div class="w-full flex gap-4">
    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
      <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
    </div>
    <div class="text-left">
      <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-artigo-remover-titulo">Remover artigo</h3>
      <div class="mt-2">
        <p class="text-sm text-gray-500">
          Tem certeza que deseja remover este artigo? Todos os dados serão permanentemente apagados. Esta ação não poderá ser revertida.
        </p>
      </div>
    </div>
  </div>
  <div class="mt-4 w-full flex flex-col sm:flex-row gap-3 font-semibold text-xs sm:justify-end justify-center">
    <button type="button" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-gray-700 rounded-lg modal-artigo-btn-cancelar w-full">Cancelar</button>
    <button type="button" class="flex gap-2 items-center justify-center py-2 px-3 bg-red-800 hover:bg-red-600 text-white rounded-lg w-full modal-artigo-btn-remover">Remover</button>
  </div>
</dialog>