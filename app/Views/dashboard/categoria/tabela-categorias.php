<div class="w-full min-h-max overflow-x-auto overflow-y-hidden border border-slate-300 rounded-md shadow bg-white">
  <div class="inline-block min-w-full align-middle">
    <table class="table-fixed min-w-full text-sm text-left text-gray-500">
      <thead class="text-xs font-light text-gray-500 uppercase">
        <colgroup>
          <col class="w-[60px]">
          <col class="w-[350px]">
          <col class="w-[500px]">
          <col class="w-[200px]">
          <col class="w-[100px]">
          <col class="w-[100px]">
        </colgroup>
        <tr class="bg-slate-100 w-full border-b">
          <th class="py-5 px-4">ID</th>
          <th class="py-5 px-4">Título</th>
          <th class="py-5 px-4">Descrição</th>
          <th class="py-5 px-4">Criado</th>
          <th class="py-5 px-4">Status</th>
          <th class="py-5 px-4">Ação</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <?php foreach ($categorias as $chave => $linha) : ?>
          <tr class="hover:bg-slate-100">
            <?php if (isset($linha['Categoria']['id'])) { ?>
                <td class="py-5 px-4"><?php echo $linha['Categoria']['id'] ?></td>

                <td class="py-5 px-4">
                  <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categoria/editar/' . $linha['Categoria']['id']); ?>" class="font-semibold text-gray-700 hover:underline js-dashboard-categorias-editar" data-categoria-id="<?php echo $linha['Categoria']['id'] ?>">
                    <?php echo $linha['Categoria']['nome'] ?>
                  </a>
                </td>
                <td class="py-5 px-4"><?php echo $linha['Categoria']['descricao'] ?></td>
                <td class="py-5 px-4"><?php echo $linha['Categoria']['criado'] ?></td>
                <td class="py-5 px-4">
                  <?php if ($linha['Categoria']['ativo'] == 1) { ?>
                    <div class="flex items-center gap-2">
                      <span class="px-3 py-1 bg-green-50 text-green-600 text-xs rounded-full">Ativo</span>
                    </div>
                  <?php } ?>
                  <?php if ($linha['Categoria']['ativo'] == 0) { ?>
                    <div class="flex items-center gap-2">
                      <span class="px-3 py-1 bg-red-50 text-red-600 text-xs rounded-full">Inativo</span>
                    </div>
                  <?php } ?>
                </td>
                <td class="py-5 px-4">
                  <div class="min-h-full flex gap-3 justify-start">
                    <button type="button" class="text-red-800 js-dashboard-categorias-remover" data-categoria-id="<?php echo $linha['Categoria']['id'] ?>">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="min-h-full">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                      </svg>
                    </button>
                    <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigos'); ?>?categoria_id=<?php echo $linha['Categoria']['id'] ?>&categoria_nome=<?php echo urlencode($linha['Categoria']['nome']) ?>" target="_blank" class="text-slate-800" title="Filtrar artigos">
                      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
                        <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/>
                      </svg>
                    </a>
                  </div>
                </td>
            <?php } ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>