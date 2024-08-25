<?php if (isset($usuarios[0]) and is_array($usuarios[0])) { ?>
  <div class="relative p-4 w-full min-h-full flex flex-col bg-white">
    <div class="pb-4 md:pb-0 w-full flex flex-col justify-center items-start md:flex-row md:justify-between md:items-center">
      <h2 class="py-4 text-2xl font-semibold">Usuários</h2>
      <div class="w-full flex flex-wrap sm:flex-nowrap justify-between md:justify-end items-center gap-2">
        <a href="/dashboard/usuario/adicionar" class="w-full md:w-max flex gap-2 items-center justify-center py-2 px-4 bg-green-800 hover:bg-green-600 text-white text-sm text-xs rounded-lg">
          Adicionar
        </a>
      </div>
    </div>
    <?php require_once 'tabela-usuarios.php' ?>
    <?php require_once 'paginacao.php' ?>
  </div>
  <?php require_once 'modais/remover.php' ?>
<?php } ?>

<?php if (! isset($usuarios[0]) or empty($usuarios[0])) { ?>
  <div class="p-4 w-full flex flex-col gap-4 items-center justify-center">
    <h2 class="text-xl">Ops! Você ainda não possui categorias</h2>
    <a href="/dashboard/usuario/adicionar" class="w-max flex gap-2 items-center justify-center py-2 px-4 bg-green-800 hover:bg-green-600 text-white text-sm text-xs rounded-lg">Adicionar</a>
  </div>
<?php } ?>