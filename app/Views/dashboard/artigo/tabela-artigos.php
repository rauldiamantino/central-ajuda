<div class="w-full min-h-max border border-slate-300 rounded-md shadow bg-white overflow-x-auto overflow-y-hidden">
  <div class="inline-block min-w-full align-middle">
    <table class="table-fixed min-w-full text-sm text-left text-gray-500">
      <thead class="text-xs font-light text-gray-500 uppercase">
        <colgroup>
          <col class="w-[60px]">
          <col class="w-[400px]">
          <col class="w-[450px]">
          <col class="w-[200px]">
          <col class="w-[100px]">
          <col class="w-[100px]">
        </colgroup>
        <tr class="bg-slate-100 w-full border-b">
          <th class="py-5 px-4">ID</th>
          <th class="py-5 px-4">Título</th>
          <th class="py-5 px-4">Categoria</th>
          <th class="py-5 px-4">Criado</th>
          <th class="py-5 px-4">Status</th>
          <th class="py-5 px-4">Ação</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <?php foreach ($artigos as $chave => $linha) : ?>
          <tr class="hover:bg-slate-100">
            <?php if (isset($linha['Artigo']['id'])) { ?>
              <td class="py-5 px-4"><?php echo $linha['Artigo']['id'] ?></td>
              <td class="py-5 px-4">
                <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $linha['Artigo']['id']); ?>" class="font-semibold text-gray-700 hover:underline js-dashboard-artigos-editar" data-artigo-id="<?php echo $linha['Artigo']['id'] ?>">
                  <?php echo $linha['Artigo']['titulo'] ?>
                </a>
              </td>
              <td class="py-5 px-4">
                <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/categoria/editar/' . $linha['Artigo']['categoria_id']); ?>" target="_blank" class="hover:underline">
                  <?php echo $linha['Categoria']['nome'] ?>
                </a>
              </td>
              <td class="py-5 px-4"><?php echo $linha['Artigo']['criado'] ?></td>
              <td class="py-5 px-4">
                <?php if ($linha['Artigo']['ativo'] == 1) { ?>
                  <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-green-50 text-green-600 text-xs rounded-full">Ativo</span>
                  </div>
                <?php } ?>
                <?php if ($linha['Artigo']['ativo'] == 0) { ?>
                  <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-red-50 text-red-600 text-xs rounded-full">Inativo</span>
                  </div>
                <?php } ?>
              </td>
              <td class="py-5 px-4">
                <div class="min-h-full flex gap-3 md:gap-1 justify-start">
                  <button type="button" class="text-red-800 js-dashboard-artigos-remover" data-artigo-id="<?php echo $linha['Artigo']['id'] ?>" data-empresa-id="<?php echo $linha['Artigo']['empresa_id'] ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="min-h-full">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                      <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                    </svg>
                  </button>
                </div>
              </td>
            <?php } ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>