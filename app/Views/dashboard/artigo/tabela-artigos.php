<div class="w-full min-h-max border border-gray-300 rounded-t-2xl rounded-b-lg shadow bg-white overflow-x-auto overflow-y-hidden overflow-estilo-tabela">
  <div class="inline-block min-w-full align-middle">
    <table class="table-fixed min-w-full text-sm text-left text-gray-500">
      <thead class="text-xs font-light text-gray-500 uppercase">
        <colgroup>
          <col class="w-[60px]">
          <col class="w-[400px]">
          <col class="w-[350px]">
          <col class="w-[250px]">
          <col class="w-[200px]">
          <col class="w-[100px]">
        </colgroup>
        <tr class="bg-gray-100 w-full border-b text-xs">
          <th class="py-4 px-6">ID</th>
          <th class="py-4 px-4">Título</th>
          <th class="py-4 px-4">Categoria</th>
          <th class="py-4 px-4">Autor</th>
          <th class="py-4 px-4">Criado</th>
          <th class="py-4 px-4">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <?php foreach ($artigos as $chave => $linha) : ?>
          <?php if (isset($linha['Artigo']['id'])) { ?>
            <tr class="hover:bg-gray-100 cursor-pointer select-none lg:select-auto" onclick="window.location='<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/dashboard/artigo/editar/' . $linha['Artigo']['id']); ?>';">
              <td class="py-6 px-6"><?php echo $linha['Artigo']['id'] ?></td>
              <td class="py-6 px-4 font-semibold text-gray-900 break-words"><?php echo $linha['Artigo']['titulo'] ?></td>
              <td class="py-6 px-4 break-words"><?php echo $linha['Categoria']['nome'] ?></td>
              <td class="py-6 px-4 whitespace-nowrap flex flex-col">
                <span><?php echo $linha['Usuario']['nome'] ? $linha['Usuario']['nome'] : '** Sem nome **' ?></span>
                <span class="text-xs font-extralight"><?php echo $linha['Usuario']['email'] ?></span>
              </td>
              <td class="py-6 px-4 whitespace-nowrap"><?php echo $linha['Artigo']['criado'] ?></td>
              <td class="py-6 px-4">
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