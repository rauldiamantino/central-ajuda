<?php 
$nivelAcesso = [
  0 => 'Suporte',
  1 => 'Acesso total',
  2 => 'Acesso restrito',
];

if ($usuario['Usuario.nivel'] == 0) {
  unset($nivelAcesso[1]);
  unset($nivelAcesso[2]);
}
else {
  unset($nivelAcesso[0]);
}
?>

<div class="relative w-full min-h-full flex flex-col bg-white p-4">
  <h2 class="flex gap-1 text-2xl font-semibold mb-4">
    Editar usuário
    <span class="flex items-center gap-2 text-gray-400 font-light italic">
      #<?php echo $usuario['Usuario.id']; ?>
      <?php if ($usuario['Usuario.padrao'] == 0) { ?>
        <span class="w-max text-yellow-800">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
          </svg>
        </span>
      <?php } ?>
      <?php if ($usuario['Usuario.padrao'] == 1) { ?>
        <span class="w-max text-gray-800">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
          </svg>
        </span>
      <?php } ?>
      <?php if ($usuario['Usuario.padrao'] == 2) { ?>
        <span class="w-max text-green-800">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
          </svg>
        </span>
      <?php } ?>
    </span>
  </h2>

  <div class="mb-10 text-xs font-light">
    <div>Criado em <?php echo traduzirDataPtBr($usuario['Usuario.criado']); ?></div>
    <div>Última atualização: <?php echo traduzirDataPtBr($usuario['Usuario.modificado']); ?></div>
  </div>
  
  <div class="w-full flex gap-4">
    <div class="w-full flex flex-col gap-2">
      <form method="POST" action="/usuario/<?php echo $usuario['Usuario.id'] ?>" class="border border-slate-200 w-full min-w-96 flex flex-col gap-4 p-4 rounded-lg shadow">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="padrao" value="<?php echo $usuario['Usuario.padrao']; ?>">
        <input type="hidden" name="empresa_id" value="<?php echo $usuario['Usuario.empresa_id']; ?>">
        
        <div class="w-full flex flex-col gap-4">
          <div class="flex gap-10">
            <div class="w-full flex gap-4">
              <label class="flex flex-col items-start gap-1 cursor-pointer">
                <span class="block text-sm font-medium text-gray-700">Status</span>
                <input type="hidden" name="ativo" value="0">
                <input type="checkbox" value="1" class="sr-only peer" <?php echo $usuario['Usuario.ativo'] ? 'checked' : '' ?> name="ativo">
                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
              </label>

              <div class="w-full flex flex-col">
                <label for="usuario-editar-nivel" class="block text-sm font-medium text-gray-700">Nível de acesso</label>
                <select id="usuario-editar-nivel" name="nivel" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required>
                  <?php foreach ($nivelAcesso as $chave => $linha) : ?>
                    <option value="<?php echo $chave; ?>" <?php echo $chave == $usuario['Usuario.nivel'] ? 'selected' : ''; ?>>
                      <?php echo $linha; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="w-full">
            <label for="usuario-editar-nome" class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" id="usuario-editar-nome" name="nome" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?php echo $usuario['Usuario.nome']; ?>" required>
          </div>
          <div class="w-full">
            <label for="usuario-editar-email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="text" id="usuario-editar-email" name="email" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?php echo $usuario['Usuario.email']; ?>" required>
          </div>
          <div class="w-full">
            <label for="usuario-editar-telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
            <input type="text" id="usuario-editar-telefone" name="telefone" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" placeholder="00 00000 0000" value="<?php echo $usuario['Usuario.telefone']; ?>" required>
          </div>
          <div>
            <div class="mt-4 text-red-800 text-xs">*Preencha os campos abaixo apenas para alterar a senha</div>
            <div class="px-4 p-6 flex flex-col gap-4 border border-slate-200 bg-slate-50 rounded-md">
              <div class="flex gap-4">
                <div class="w-full">
                  <label for="usuario-editar-senha-atual" class="block text-sm font-medium text-gray-700">Senha atual</label>
                  <input type="password" id="usuario-editar-senha-atual" name="senha_atual" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="" autocomplete="off">
                </div>
                <div class="w-full">
                  <label for="usuario-editar-senha" class="block text-sm font-medium text-gray-700">Nova senha</label>
                  <input type="password" id="usuario-editar-senha" name="senha" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="" autocomplete="off">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="flex gap-4">
          <a href="/dashboard/usuarios" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-xs text-gray-700 rounded-lg">Cancelar</a>
          <button type="submit" class="flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg">Gravar</button>
        </div>
      </form>
    </div>
    <div class="w-full"></div>
  </div>
</div>

<?php // Modal remover ?>
<dialog class="p-4 sm:w-[440px] rounded-lg shadow modal-conteudo-remover">
  <div class="w-full flex gap-4">
    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
      <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
    </div>
    <div class="text-left">
      <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-conteudo-remover-titulo">Remover conteudo</h3>
      <div class="mt-2">
        <p class="text-sm text-gray-500">
          Tem certeza que deseja remover este conteudo? Todos os dados serão permanentemente apagados. Esta ação não poderá ser revertida.
        </p>
      </div>
    </div>
  </div>
  <div class="mt-4 w-full flex flex-col sm:flex-row gap-3 font-semibold text-xs sm:justify-end justify-center">
    <button type="button" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-gray-700 rounded-lg modal-conteudo-btn-cancelar w-full">Cancelar</button>
    <button type="button" class="flex gap-2 items-center justify-center py-2 px-3 bg-red-800 hover:bg-red-600 text-white rounded-lg w-full modal-conteudo-btn-remover">Remover</button>
  </div>
</dialog>