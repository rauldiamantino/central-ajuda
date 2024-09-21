<?php
$tipoUsuario = [
  0 => 'Suporte',
  1 => 'Padrão',
  2 => 'Comum',
];

$nivelAcesso = [
  0 => 'Suporte',
  1 => 'Acesso total',
  2 => 'Acesso restrito',
];
?>

<div class="max-w-screen overflow-x-auto">
  <div class="inline-block min-w-full align-middle">
    <table class="table-fixed min-w-full text-sm text-left text-gray-500">
      <thead class="text-xs font-light text-gray-500 uppercase">
        <colgroup>
          <col class="w-[60px]">
          <col class="w-[400px]">
          <col class="w-[400px]">
          <col class="w-[150px]">
          <col class="w-[150px]">
          <col class="w-[100px]">
          <col class="w-[100px]">
          <col class="w-[100px]">
        </colgroup>
        <tr class="bg-slate-100 w-full border-b divide-x">
          <th class="p-6">ID</th>
          <th class="p-6">Nome</th>
          <th class="p-6">Email</th>
          <th class="p-6">Tipo de usuário</th>
          <th class="p-6">Nível</th>
          <th class="p-6">Status</th>
          <th class="p-6">Tentativas</th>
          <th class="p-6">Remover</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <?php foreach ($usuarios as $chave => $linha) : ?>
          <tr class="hover:bg-slate-100 divide-x">
            <?php if (isset($linha['Usuario.id'])) { ?>
              <?php foreach ($linha as $subChave => $subLinha) : ?>

                <?php // ID ?>
                <?php if ($subChave == 'Usuario.id') { ?>
                  <td class="py-5 px-4"><?php echo $subLinha ?></td>
                <?php } ?>

                <?php // Nome ?>
                <?php if ($subChave == 'Usuario.nome') { ?>
                  <td class="py-5 px-4">
                    <a href="/<?php echo $this->usuarioLogado['subdominio'] ?>/dashboard/usuario/editar/<?php echo $linha['Usuario.id'] ?>" class="font-semibold text-gray-700 underline js-dashboard-usuarios-editar" data-usuario-id="<?php echo $linha['Usuario.id'] ?>">
                      <?php echo $subLinha ? $subLinha : '** Sem nome **' ?>
                    </a>
                  </td>
                <?php } ?>

                <?php // Email ?>
                <?php if ($subChave == 'Usuario.email') { ?>
                  <td class="py-5 px-4"><?php echo $subLinha ?></td>
                <?php } ?>

                <?php // Padrão ?>
                <?php if ($subChave == 'Usuario.padrao') { ?>
                  <td class="py-5 px-4">
                    <div class="flex gap-2">
                      <?php if ($subLinha == 0) { ?>
                        <span class="w-max h-max text-yellow-800">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                          </svg>
                        </span>
                      <?php } ?>
                      <?php if ($subLinha == 1) { ?>
                        <span class="w-max h-max text-gray-800">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                          </svg>
                        </span>
                      <?php } ?>
                      <?php if ($subLinha == 2) { ?>
                        <span class="w-max h-max text-green-800">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                          </svg>
                        </span>
                      <?php } ?>
                      <?php echo $tipoUsuario[ $subLinha ]; ?>
                    </div>
                  </td>
                <?php } ?>

                <?php // Nível ?>
                <?php if ($subChave == 'Usuario.nivel') { ?>
                  <td class="py-5 px-4"><?php echo $nivelAcesso[ $subLinha ]; ?></td>
                <?php } ?>

                <?php // Ativo ?>
                <?php if ($subChave == 'Usuario.ativo') { ?>
                  <td class="py-5 px-4">
                    <?php if ($linha['Usuario.tentativas_login'] < 10 and $subLinha == 1) { ?>
                      <div class="flex items-center gap-2">
                        <span class="text-green-800">
                          <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                            <circle cx="8" cy="8" r="8"/>
                          </svg>
                        </span>
                        <span>Ativo</span>
                      </div>
                    <?php } ?>
                    <?php if ($linha['Usuario.tentativas_login'] < 10 and $subLinha == 0) { ?>
                      <div class="flex items-center gap-2">
                        <span class="text-red-800">
                          <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                            <circle cx="8" cy="8" r="8"/>
                          </svg>
                        </span>
                        <span>Inativo</span>
                      </div>
                    <?php } ?>
                    <?php if ($linha['Usuario.tentativas_login'] >= 10) { ?>
                      <div class="flex items-center gap-2">
                        <span class="text-gray-800">
                          <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                            <circle cx="8" cy="8" r="8"/>
                          </svg>
                        </span>
                        <span>Bloqueado</span>
                      </div>
                    <?php } ?>
                  </td>
                <?php } ?>
              <?php endforeach; ?>

              <?php // Acesso ?>
              <?php if ($subChave == 'Usuario.tentativas_login') { ?>
                <td class="py-5 px-4 text-center">
                  <?php echo $subLinha; ?>
                </td>
              <?php } ?>

              <?php // Remover ?>
              <?php if (isset($linha['Usuario.id'])) { ?>
                <td class="py-5 px-4 text-center">
                  <button type="button" class="text-red-800 js-dashboard-usuarios-remover" data-usuario-id="<?php echo $linha['Usuario.id'] ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="min-h-full">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                      <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                    </svg>
                  </button>
                </td>
              <?php } ?>
            <?php } ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>