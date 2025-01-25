<div class="hidden dashboard-tabela-menos-populares">
  <h3 class="mt-6 py-3 text-lg font-semibold">
    Artigos menos populares
  </h3>
  <div class="w-full min-h-max border border-gray-300 rounded-t-2xl rounded-b-lg shadow bg-white overflow-x-auto overflow-y-hidden overflow-estilo-tabela">
    <div class="inline-block min-w-full align-middle">
      <table class="table-fixed min-w-full text-sm text-left text-gray-500">
        <thead class="text-xs font-light text-gray-500 uppercase">
          <colgroup>

            <?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
              <col class="w-[60px]">
            <?php } ?>

            <col class="w-[60px]">
            <col class="w-[600px]">
            <col class="w-[400px]">
            <col class="w-[150px]">
            <col class="w-[150px]">
          </colgroup>
          <tr class="bg-gray-100 w-full border-b text-xs">

            <?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
              <th class="text-gray-400 font-extralight py-4 px-6">ID</th>
            <?php } ?>

            <th class="py-4 px-6">Código</th>
            <th class="py-4 px-4">Título</th>
            <th class="py-4 px-4">Categoria</th>
            <th class="py-4 px-4 underline" onclick="alternarPopulares(true)"><span class="underline cursor-pointer">Gostou</span></th>
            <th class="py-4 px-4">Não gostou</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <?php foreach ($artigosMenosPopulares as $chave => $linha) : ?>
            <?php if (isset($linha['Artigo']['id'])) { ?>
              <tr class="hover:bg-gray-100 cursor-pointer select-none lg:select-auto" onclick="window.location='<?php echo '/dashboard/artigo/editar/' . $linha['Artigo']['codigo']; ?>';">

                <?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
                  <td class="text-gray-400 font-extralight py-6 px-6"><?php echo $linha['Artigo']['id']; ?></td>
                <?php } ?>

                <td class="py-6 px-6"><?php echo $linha['Artigo']['codigo']; ?></td>
                <td class="py-6 px-4 font-semibold text-gray-900 break-words"><?php echo $linha['Artigo']['titulo']; ?></td>
                <td class="py-6 px-4 break-words"><?php echo $linha['Categoria']['nome']; ?></td>
                <td class="py-6 px-4 whitespace-nowrap"><?php echo $linha['Feedback']['gostou']; ?></td>
                <td class="py-6 px-4 whitespace-nowrap"><?php echo $linha['Feedback']['nao_gostou']; ?></td>
              </tr>
            <?php } ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>