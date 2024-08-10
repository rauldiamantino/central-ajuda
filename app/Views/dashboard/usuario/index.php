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

<div class="relative w-full min-h-full flex flex-col bg-white">
  <div class="w-full flex justify-between items-center">
    <h2 class="py-4 text-2xl font-semibold">Usuários</h2>
    <a href="/dashboard/usuario/adicionar" class="w-max flex gap-2 items-center justify-center py-2 px-4 bg-green-800 hover:bg-green-600 text-white text-sm text-xs rounded-lg">Adicionar</a>
  </div>
  <table class="table-fixed text-sm text-left text-gray-500">
    <thead class="p-6 text-xs font-light text-gray-500 uppercase bg-slate-100">
      <tr class="w-full border-b divide-x">
        <th class="p-6 w-20">ID</th>
        <th class="p-6 w-96">Nome</th>
        <th class="p-6 min-w-96">Email</th>
        <th class="p-6 w-40 whitespace-nowrap">Tipo de usuário</th>
        <th class="p-6 w-40">Nível</th>
        <th class="p-6 w-28">Status</th>
        <th class="p-6 w-32 min-w-max">Ações</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php foreach ($usuarios as $chave => $linha) : ?>
        <tr class="hover:bg-slate-100 divide-x">
          <?php foreach ($linha as $subChave => $subLinha) : ?>
            <?php if ($subChave == 'Usuario.id') { ?>
              <td class="whitespace-nowrap p-6 w-20"><?php echo $subLinha ?></td>
            <?php } ?>
            <?php if ($subChave == 'Usuario.nome') { ?>
              <td class="whitespace-nowrap p-6 w-96 font-semibold text-gray-700"><?php echo $subLinha ?></td>
            <?php } ?>
            <?php if ($subChave == 'Usuario.email') { ?>
              <td class="whitespace-nowrap p-6 min-w-96 truncate"><?php echo $subLinha ?></td>
            <?php } ?>
            <?php if ($subChave == 'Usuario.padrao') { ?>
              <td class="whitespace-nowrap p-6 max-w-40 flex items-center gap-2 truncate">
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
              </td>
            <?php } ?>
            <?php if ($subChave == 'Usuario.nivel') { ?>
              <td class="whitespace-nowrap p-6 max-w-40 truncate"><?php echo $nivelAcesso[ $subLinha ]; ?></td>
            <?php } ?>
            <?php if ($subChave == 'Usuario.ativo') { ?>
              <td class="whitespace-nowrap p-6 w-28">
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
          <td class="whitespace-normal p-6 w-32 min-w-max flex gap-2 items-center">
            <a href="/dashboard/usuario/editar/<?php echo $linha['Usuario.id'] ?>" class="flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg js-dashboard-usuarios-editar" data-usuario-id="<?php echo $linha['Usuario.id'] ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
              </svg>
              Editar
            </a>
            <button type="button" class="flex gap-2 items-center justify-center py-2 px-4 bg-red-800 hover:bg-red-600 text-white text-xs rounded-lg js-dashboard-usuarios-remover" data-usuario-id="<?php echo $linha['Usuario.id'] ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
              </svg>
              Remover
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php // Paginação ?>
  <div class="sticky inset-x-0 bottom-0 border-t border-slate-200 w-full h-max py-2 flex justify-center items-center gap-1 bg-white">
    <?php if ($pagina > 1) { ?>
      <a href="/dashboard/usuarios?pagina=<?php echo $pagina - 1 ?>" class="border border-slate-100 bg-slate-100 hover:bg-slate-200 text-gray-500 hover:text-black p-3 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" stroke="currentColor" stroke-width="2" />
        </svg>
      </a>
    <?php } ?>

    <?php if ($pagina <= 1) { ?>
      <div class="border border-slate-100 text-gray-500 p-3 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" stroke="currentColor" stroke-width="2" />
        </svg>
      </div>
    <?php } ?>

    <form action="/dashboard/usuarios" method="get">
      <input type="number" name="pagina" value="<?php echo $pagina ?>" class="p-2 w-16 border-2 border-slate-100 text-center [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none rounded-lg usuario-numero-pagina">
    </form>

    <?php if ($pagina < $paginasTotal) { ?>
      <a href="/dashboard/usuarios?pagina=<?php echo $pagina + 1 ?>" class="border border-slate-100 bg-slate-100 hover:bg-slate-200 text-gray-500 hover:text-black p-3 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" stroke="currentColor" stroke-width="2" />
        </svg>
      </a>
    <?php } ?>

    <?php if ($pagina == $paginasTotal) { ?>
      <div class="border border-slate-100 text-gray-500 p-3 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" stroke="currentColor" stroke-width="2" />
        </svg>
      </div>
    <?php } ?>

    <div class="pl-2 text-xs">Exibindo <?php echo $intervaloInicio; ?>-<?php echo $intervaloFim; ?> de <?php echo $usuariosTotal; ?></div>
  </div>
</div>

<?php // Modal remover ?>
<dialog class="p-4 sm:w-[440px] rounded-lg shadow modal-usuario-remover">
  <div class="w-full flex gap-4">
    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
      <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
    </div>
    <div class="text-left">
      <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-usuario-remover-titulo">Remover usuario</h3>
      <div class="mt-2">
        <p class="text-sm text-gray-500">
          Tem certeza que deseja remover este usuario? Todos os dados serão permanentemente apagados. Esta ação não poderá ser revertida.
        </p>
      </div>
    </div>
  </div>
  <div class="mt-4 w-full flex flex-col sm:flex-row gap-3 font-semibold text-xs sm:justify-end justify-center">
    <button type="button" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-gray-700 rounded-lg modal-usuario-btn-cancelar w-full">Cancelar</button>
    <button type="button" class="flex gap-2 items-center justify-center py-2 px-3 bg-red-800 hover:bg-red-600 text-white rounded-lg w-full modal-usuario-btn-remover">Remover</button>
  </div>
</dialog>