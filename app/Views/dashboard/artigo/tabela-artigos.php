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
        </colgroup>
        <tr class="bg-slate-100 w-full border-b">
          <th class="py-5 px-4">ID</th>
          <th class="py-5 px-4">Título</th>
          <th class="py-5 px-4">Categoria</th>
          <th class="py-5 px-4">Criado</th>
          <th class="py-5 px-4">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <?php foreach ($artigos as $chave => $linha) : ?>
          <?php if (isset($linha['Artigo']['id'])) { ?>
            <tr class="hover:bg-slate-100 cursor-pointer" onclick="window.location='<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $linha['Artigo']['id']); ?>';">
              <td class="py-5 px-4"><?php echo $linha['Artigo']['id'] ?></td>
              <td class="py-5 px-4 font-semibold text-gray-900 truncate"><?php echo $linha['Artigo']['titulo'] ?></td>
              <td class="py-5 px-4"><?php echo $linha['Categoria']['nome'] ?></td>
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
            </tr>
          <?php } ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>