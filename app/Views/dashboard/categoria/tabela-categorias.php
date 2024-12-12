<div class="w-full min-h-max border border-gray-300 rounded-t-2xl rounded-b-lg shadow bg-white overflow-x-auto overflow-y-hidden overflow-estilo-tabela">
  <div class="inline-block min-w-full align-middle">
    <table class="table-fixed min-w-full text-sm text-left text-gray-500">
      <thead class="text-xs font-light text-gray-500 uppercase">
        <colgroup>
          <col class="w-[50px]">
          <col class="w-[350px]">
          <col class="w-[500px]">
          <col class="w-[100px]">
        </colgroup>
        <tr class="bg-gray-100 w-full border-b text-xs">
          <th class="py-4 px-6"></th>
          <th class="py-4 px-4">Título</th>
          <th class="py-4 px-4">Descrição</th>
          <th class="py-4 px-4">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <?php foreach ($categorias as $chave => $linha) : ?>
          <?php if (isset($linha['Categoria']['id'])) { ?>
            <tr class="hover:bg-gray-100 cursor-pointer select-none lg:select-auto" onclick="window.location='<?php echo '/dashboard/categoria/editar/' . $linha['Categoria']['id'] . '?referer=' . urlencode('/dashboard/categorias?pagina=' . $pagina); ?>';">
              <td class="py-6 px-6 font-semibold text-gray-900">
                <?php if ($linha['Categoria']['icone'] and $this->iconeExiste($linha['Categoria']['icone'])) { ?>
                  <div class="w-6">
                    <?php echo $this->renderIcone($linha['Categoria']['icone']); ?>
                  </div>
                <?php } else { ?>
                  <div class="w-6 text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                  </div>
                <?php } ?>
              </td>
              <td class="py-6 px-4 font-semibold text-gray-900"><?php echo $linha['Categoria']['nome'] ?></td>
              <td class="py-6 px-4 md:whitespace-nowrap md:truncate"><?php echo $linha['Categoria']['descricao'] ?></td>
              <td class="py-6 px-4">
                <?php if ($linha['Categoria']['ativo'] == 1) { ?>
                  <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-green-50 text-green-600 text-xs rounded-full">Ativo</span>
                  </div>
                <?php } else { ?>
                  <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-red-50 text-red-600 text-xs rounded-full">Inativo</span>
                  </div>
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>