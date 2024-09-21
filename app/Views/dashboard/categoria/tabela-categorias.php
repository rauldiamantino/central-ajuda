<div class="max-w-screen overflow-x-auto">
  <div class="inline-block min-w-full align-middle">
    <table class="table-fixed min-w-full text-sm text-left text-gray-500">
      <thead class="text-xs font-light text-gray-500 uppercase">
        <colgroup>
          <col class="w-[60px]">
          <col class="w-[400px]">
          <col class="w-[500px]">
          <col class="w-[150px]">
          <col class="w-[100px]">
          <col class="w-[100px]">
        </colgroup>
        <tr class="bg-slate-100 w-full border-b divide-x">
          <th class="p-6">ID</th>
          <th class="p-6">Título</th>
          <th class="p-6">Descrição</th>
          <th class="p-6">Criado</th>
          <th class="p-6">Status</th>
          <th class="p-6">Ação</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <?php foreach ($categorias as $chave => $linha) : ?>
          <tr class="hover:bg-slate-100 divide-x">
            <?php if (isset($linha['Categoria.id'])) { ?>
              <?php foreach ($linha as $subChave => $subLinha) : ?>

                <?php // ID ?>
                <?php if ($subChave == 'Categoria.id') { ?>
                  <td class="py-5 px-4"><?php echo $subLinha ?></td>
                <?php } ?>

                <?php // Nome ?>
                <?php if ($subChave == 'Categoria.nome') { ?>
                  <td class="py-5 px-4">
                    <a href="/<?php echo $this->usuarioLogado['subdominio'] ?>/dashboard/categoria/editar/<?php echo $linha['Categoria.id'] ?>" class="font-semibold text-gray-700 hover:underline js-dashboard-categorias-editar" data-categoria-id="<?php echo $linha['Categoria.id'] ?>">
                      <?php echo $subLinha ?>
                    </a>
                  </td>
                <?php } ?>

                <?php // Descrição ?>
                <?php if ($subChave == 'Categoria.descricao') { ?>
                  <td class="py-5 px-4"><?php echo $subLinha ?></td>
                <?php } ?>

                <?php // Criado ?>
                <?php if ($subChave == 'Categoria.criado') { ?>
                  <td class="py-5 px-4"><?php echo $subLinha ?></td>
                <?php } ?>

                <?php // Ativo ?>
                <?php if ($subChave == 'Categoria.ativo') { ?>
                  <td class="py-5 px-4">
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

            <?php // Ação ?>
            <?php if (isset($linha['Categoria.id'])) { ?>
              <td class="py-5 px-4">
                <div class="flex gap-3 md:gap-1 justify-around">
                  <a href="/<?php echo $this->usuarioLogado['subdominio'] ?>/dashboard/artigos?categoria_id=<?php echo $linha['Categoria.id'] ?>" target="_blank" class="text-slate-800" title="Filtrar artigos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-funnel" viewBox="0 0 16 16">
                      <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z"/>
                    </svg>
                  </a>
                  <button type="button" class="text-red-800 js-dashboard-categorias-remover" data-categoria-id="<?php echo $linha['Categoria.id'] ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
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