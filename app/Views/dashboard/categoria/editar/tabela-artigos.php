<div class="w-full">
  <div class="w-full min-h-max border border-gray-300 rounded-t-2xl rounded-b-lg shadow bg-white overflow-x-auto overflow-y-hidden overflow-estilo-tabela">
    <div class="inline-block min-w-full align-middle">
      <table class="table-fixed min-w-full text-sm text-left text-gray-500">
        <thead class="text-xs font-light text-gray-500 uppercase">
          <colgroup>

          <?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
            <col class="w-[60px]">
          <?php } ?>

          <col class="w-[60px]">
          <col class="w-[400px]">
          <col class="w-[350px]">
          <col class="w-[250px]">
          <col class="w-[200px]">
          <col class="w-[100px]">
          </colgroup>
          <tr class="bg-gray-100 w-full border-b text-xs">

            <?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
              <th class="text-gray-400 font-extralight py-4 px-6">ID</th>
            <?php } ?>

            <th class="py-4 px-6">Código</th>
            <th class="py-4 px-4">Artigo</th>
            <th class="py-4 px-4">Categoria</th>
            <th class="py-4 px-4">Autor</th>
            <th class="py-4 px-4">Criado</th>
            <th class="py-4 px-4">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <?php if (isset($categoria['artigos'])) { ?>
            <?php foreach ($categoria['artigos'] as $chave => $linha): ?>

              <?php if (isset($linha['Artigo']['id'])) { ?>
                <tr class="hover:bg-gray-100 cursor-pointer select-none lg:select-auto" onclick="window.location='<?php echo '/dashboard/artigo/editar/' . $linha['Artigo']['codigo'] . '?referer=' . urlencode('/dashboard/categoria/editar/' . $categoria['Categoria']['id']); ?>';">

                  <?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
                    <td class="text-gray-400 font-extralight py-6 px-6"><?php echo $linha['Artigo']['id'] ?></td>
                  <?php } ?>

                  <td class="py-6 px-6"><?php echo $linha['Artigo']['codigo'] ?></td>
                  <td class="py-6 px-4 font-semibold text-gray-900 break-words"><?php echo $linha['Artigo']['titulo'] ?></td>
                  <td class="py-6 px-4 break-words"><?php echo $categoria['Categoria']['nome'] ?></td>
                  <td class="py-6 px-4 whitespace-nowrap flex flex-col">
                    <span><?php echo $linha['Usuario']['nome'] ? $linha['Usuario']['nome'] : '** Sem nome **' ?></span>
                    <span class="text-xs font-extralight"><?php echo $linha['Usuario']['email'] ?></span>
                  </td>
                  <td class="py-6 px-4 whitespace-nowrap"><?php echo traduzirDataPtBr($linha['Artigo']['criado']) ?></td>
                  <td class="py-6 px-4">
                    <?php if ($linha['Artigo']['ativo'] == 1) { ?>
                      <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-green-50 text-green-600 text-xs rounded-full">Público</span>
                      </div>
                    <?php } ?>
                    <?php if ($linha['Artigo']['ativo'] == 0) { ?>
                      <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-red-50 text-red-600 text-xs rounded-full">Privado</span>
                      </div>
                    <?php } ?>
                  </td>
                </tr>
              <?php } ?>
            <?php endforeach; ?>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php require_once 'paginacao.php' ?>
</div>
