<table class="table-fixed text-sm text-left text-gray-500">
  <thead class="p-6 text-xs font-light text-gray-500 uppercase bg-slate-100">
    <tr class="w-full border-b divide-x">
      <th class="p-6 w-20">ID</th>
      <th class="p-6 w-96">Título</th>
      <th class="p-6 max-w-96">Descrição</th>
      <th class="p-6 w-[200px] max-w-[200px]">Criado</th>
      <th class="p-6 w-28">Status</th>
      <th class="p-6 w-32 min-w-max">Ações</th>
    </tr>
  </thead>
  <tbody class="divide-y">
    <?php foreach ($categorias as $chave => $linha) : ?>
      <tr class="hover:bg-slate-100 divide-x">
        <?php if (isset($linha['Categoria.id'])) { ?>
          <?php foreach ($linha as $subChave => $subLinha) : ?>
            <?php if ($subChave == 'Categoria.id') { ?>
              <td class="whitespace-nowrap p-6 w-20"><?php echo $subLinha ?></td>
            <?php } ?>
            <?php if ($subChave == 'Categoria.nome') { ?>
              <td class="whitespace-nowrap p-6 w-96 font-semibold text-gray-700"><?php echo $subLinha ?></td>
            <?php } ?>
            <?php if ($subChave == 'Categoria.descricao') { ?>
              <td class="whitespace-nowrap p-6 max-w-96 truncate"><?php echo $subLinha ?></td>
            <?php } ?>
            <?php if ($subChave == 'Categoria.criado') { ?>
              <td class="whitespace-nowrap p-6 w-[200px] max-w-[200px] truncate"><?php echo $subLinha ?></td>
            <?php } ?>
            <?php if ($subChave == 'Categoria.ativo') { ?>
              <td class="whitespace-nowrap p-6 w-28">
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
        <?php } ?>
        <?php if (isset($linha['Categoria.id'])) { ?>
          <td class="whitespace-normal p-6 w-32 min-w-max flex gap-2 items-center">
            <a href="/dashboard/categoria/editar/<?php echo $linha['Categoria.id'] ?>" class="flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg js-dashboard-categorias-editar" data-categoria-id="<?php echo $linha['Categoria.id'] ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
              </svg>
              Editar
            </a>
            <button type="button" class="flex gap-2 items-center justify-center py-2 px-4 bg-red-800 hover:bg-red-600 text-white text-xs rounded-lg js-dashboard-categorias-remover" data-categoria-id="<?php echo $linha['Categoria.id'] ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
              </svg>
              Remover
            </button>
          </td>
        <?php } ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>