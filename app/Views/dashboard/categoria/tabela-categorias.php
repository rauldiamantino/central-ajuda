<div class="w-full min-h-max overflow-x-auto overflow-y-hidden border border-slate-300 rounded-md shadow bg-white">
  <div class="inline-block min-w-full align-middle">
    <table class="table-fixed min-w-full text-sm text-left text-gray-500">
      <thead class="text-xs font-light text-gray-500 uppercase">
        <colgroup>
          <col class="w-[350px]">
          <col class="w-[500px]">
          <col class="w-[100px]">
        </colgroup>
        <tr class="bg-slate-100 w-full border-b">
          <th class="py-4 px-4 md:pl-8">Título</th>
          <th class="py-4 px-4">Descrição</th>
          <th class="py-4 px-4">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <?php foreach ($categorias as $chave => $linha) : ?>
          <?php if (isset($linha['Categoria']['id'])) { ?>
            <tr class="hover:bg-slate-100 cursor-pointer" onclick="window.location='<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categoria/editar/' . $linha['Categoria']['id']); ?>';">
              <td class="py-4 px-4 md:pl-8 font-semibold text-gray-900"><?php echo $linha['Categoria']['nome'] ?></td>
              <td class="py-4 px-4 md:whitespace-nowrap md:truncate"><?php echo $linha['Categoria']['descricao'] ?></td>
              <td class="py-4 px-4">
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