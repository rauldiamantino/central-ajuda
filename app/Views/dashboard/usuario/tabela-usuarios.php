<?php
$tipoUsuario = [
  USUARIO_SUPORTE => 'Suporte',
  USUARIO_PADRAO => 'Padrão',
  USUARIO_COMUM => 'Comum',
];

$nivelAcesso = [
  USUARIO_TOTAL => 'Acesso total',
  USUARIO_LEITURA => 'Modo leitura',
];
?>

<div class="w-full min-h-max border border-gray-300 rounded-t-2xl rounded-b-lg shadow bg-white overflow-x-auto overflow-y-hidden overflow-estilo-tabela">
  <div class="inline-block min-w-full align-middle">
    <table class="table-fixed min-w-full text-sm text-left text-gray-500">
      <thead class="text-xs font-light text-gray-500 uppercase">
        <colgroup>
          <col class="w-[60px]">
          <col class="w-[450px]">
          <col class="w-[400px]">
          <col class="w-[150px]">
          <col class="w-[150px]">
          <col class="w-[150px]">
        </colgroup>
        <tr class="bg-gray-100 w-full border-b text-xs">
          <th class="py-4 px-6">ID</th>
          <th class="py-4 px-4">Nome</th>
          <th class="py-4 px-4">Email</th>
          <th class="py-4 px-4">Tipo de usuário</th>
          <th class="py-4 px-4">Nível</th>
          <th class="py-4 px-4">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <?php foreach ($usuarios as $chave => $linha) : ?>
          <tr class="hover:bg-gray-100 cursor-pointer select-none md:select-auto" onclick="window.location='<?php echo '/dashboard/usuario/editar/' . $linha['Usuario']['id']; ?>';">
            <?php if (isset($linha['Usuario']['id'])) { ?>
              <td class="py-4 px-4">
                <?php echo $linha['Usuario']['id'] ?>
              </td>
              <td class="py-6 px-6 font-semibold text-gray-900"><?php echo $linha['Usuario']['nome'] ? $linha['Usuario']['nome'] : '** Sem nome **' ?></td>
              <td class="py-6 px-4"><?php echo $linha['Usuario']['email'] ?></td>
              <td class="py-6 px-4">
                <div class="flex gap-2">
                  <?php if ($linha == USUARIO_SUPORTE) { ?>
                    <span class="w-max h-max text-yellow-800">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                        <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                      </svg>
                    </span>
                  <?php } ?>
                  <?php if ($linha == USUARIO_PADRAO) { ?>
                    <span class="w-max h-max text-gray-800">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                      </svg>
                    </span>
                  <?php } ?>
                  <?php if ($linha == USUARIO_COMUM) { ?>
                    <span class="w-max h-max text-green-800">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                        <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                      </svg>
                    </span>
                  <?php } ?>
                  <?php echo $tipoUsuario[ $linha['Usuario']['padrao'] ]; ?>
                </div>
              </td>
              <td class="py-6 px-4"><?php echo $nivelAcesso[ $linha['Usuario']['nivel'] ]; ?></td>
              <td class="py-6 px-4">
                <?php if ($linha['Usuario']['tentativas_login'] < 10 and $linha['Usuario']['ativo'] == ATIVO) { ?>
                  <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-green-50 text-green-600 text-xs rounded-full">Ativo</span>
                  </div>
                <?php } ?>
                <?php if ($linha['Usuario']['tentativas_login'] < 10 and $linha['Usuario']['ativo'] == INATIVO) { ?>
                  <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-red-50 text-red-600 text-xs rounded-full">Inativo</span>
                  </div>
                <?php } ?>
                <?php if ($linha['Usuario']['tentativas_login'] >= 10) { ?>
                  <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-gray-50 text-gray-600 text-xs rounded-full">Bloqueado</span>
                  </div>
                <?php } ?>
              </td>
            <?php } ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>