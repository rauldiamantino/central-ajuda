<?php
$nivelAcesso = [
  USUARIO_TOTAL => 'Acesso total',
  USUARIO_RESTRITO => 'Acesso restrito',
];

$ultimoAcesso = $usuario['Usuario']['ultimo_acesso'] ?? '';
$ultimoAcesso = json_decode($ultimoAcesso, true);
?>

<form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/usuario/' . $usuario['Usuario']['id']); ?>" class="border-t border-slate-300 w-full h-full flex flex-col gap-4 form-editar-usuario" enctype="multipart/form-data">
  <input type="hidden" name="_method" value="PUT">
  <input type="hidden" name="empresa_id" value="<?php echo $usuario['Usuario']['empresa_id']; ?>">

  <?php if ($this->usuarioLogado['padrao'] != USUARIO_SUPORTE) { ?>
    <input type="hidden" name="padrao" value="<?php echo $usuario['Usuario']['padrao']; ?>">
  <?php } ?>

  <div class="w-full flex flex-col divide-y">
    <?php // Status ?>
    <div class="w-full lg:w-[700px] py-8 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <span class="block text-sm font-medium text-gray-700">Status</span>
      <label class="w-max flex flex-col items-start gap-1 cursor-pointer">
        <input type="hidden" name="ativo" value="0">
        <input type="checkbox" value="1" class="sr-only peer" <?php echo $usuario['Usuario']['ativo'] ? 'checked' : '' ?> name="ativo">
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
      </label>
    </div>

    <?php // Foto ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <input type="hidden" name="foto" value="<?php echo $usuario['Usuario']['foto']; ?>" class="url-imagem">
      <input type="file" accept="image/*" id="usuario-editar-foto" name="arquivo-foto" class="hidden usuario-editar-foto-escolher" onchange="mostrarImagemUsuario(event)">
      <div class="flex flex-col text-sm font-medium text-gray-700">
        <span>Foto</span>
        <span class="font-extralight">Envie uma imagem para representar o seu usuário. O arquivo deve ter até 2MB e estar no formato .svg, .jpg ou .png. Tamanho ideal: 200px de largura por 200px de altura.</span>
      </div>
      <div class="w-max flex flex-col items-center justify-center gap-4">
        <button type="button" for="usuario-editar-foto" class="mt-2 lg:mt-0 w-max h-max flex items-center justify-center border border-gray-200 hover:border-gray-300 rounded-full usuario-btn-foto-editar-escolher" onclick="alterarImagemUsuario(event)">
          <div class="w-max h-max">
            <img src="<?php echo $this->renderImagem($usuario['Usuario']['foto']); ?>" class="p-1 w-20 h-20 rounded-full usuario-alterar-foto" onerror="this.onerror=null; this.src='/img/sem-imagem-perfil.svg';">
          </div>
          <span class="text-gray-700 h-max w-max empresa-txt-imagem-editar-escolher"><?php echo $usuario['Usuario']['foto'] ? '' : ''; ?></span>
          <h3 class="hidden font-light text-left text-sm text-red-800 erro-usuario-foto"></h3>
        </button>

        <?php if ($usuario['Usuario']['foto']) { ?>
          <button type="button" class="text-xs text-red-600 hover:underline duration-150 usuario-remover-foto" onclick="removerFotoUsuario(<?php echo $usuario['Usuario']['id']; ?>)">Remover</button>
        <?php } ?>
      </div>
    </div>

    <?php // Nível de acesso?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="usuario-editar-nivel" class="block text-sm font-medium text-gray-700">Nível de acesso</label>
      <select id="usuario-editar-nivel" name="nivel" class="<?php echo CLASSES_DASH_INPUT; ?>" required>
        <?php foreach ($nivelAcesso as $chave => $linha) : ?>
          <option value="<?php echo $chave; ?>" <?php echo $chave == $usuario['Usuario']['nivel'] ? 'selected' : ''; ?>>
            <?php echo $linha; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <?php // Nome do usuário ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="usuario-editar-nome" class="block text-sm font-medium text-gray-700">Seu nome <span class="font-extralight">(opcional)</span></label>
      <input type="text" id="usuario-editar-nome" name="nome" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $usuario['Usuario']['nome']; ?>" autofocus>
    </div>

    <?php // Email do usuário?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="usuario-editar-email" class="block text-sm font-medium text-gray-700">Seu e-mail</label>
      <input type="text" id="usuario-editar-email" name="email" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $usuario['Usuario']['email']; ?>" required>
    </div>

    <?php // Senha atual ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="usuario-editar-senha-atual" class="flex flex-col text-sm font-medium text-gray-700">
        <span>Senha atual</span>
        <span class="font-extralight">Obrigatório apenas para alteração de senha</span>
      </label>
      <input type="password" id="usuario-editar-senha-atual" name="senha_atual" class="<?php echo CLASSES_DASH_INPUT; ?>" value="" autocomplete="off">
    </div>

    <?php // Nova senha ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="usuario-editar-senha" class="flex flex-col text-sm font-medium text-gray-700">
        <span>Nova senha</span>
        <span class="font-extralight">Obrigatório apenas para alteração de senha</span>
      </label>
      <input type="password" id="usuario-editar-senha" name="senha" class="<?php echo CLASSES_DASH_INPUT; ?>" value="" autocomplete="off">
    </div>

    <?php // Último acesso?>
    <?php if ($ultimoAcesso and (int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) {?>
      <?php foreach ($ultimoAcesso as $chave => $linha): ?>
        <?php if (in_array($chave, ['idSessao', 'url', 'tokenSessao'])) { continue; } ?>
        <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
          <div class="flex flex-col text-sm font-medium text-gray-700">
            <span><?php echo ucfirst($chave) ?></span>
          </div>
          <div class="<?php echo CLASSES_DASH_INPUT_BLOCK; ?>">
            <span class="text-sm"><?php echo $linha ?? '' ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    <?php } ?>

    <?php if ($this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
      <?php // Tentativas ?>
      <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
        <div class="flex flex-col text-sm font-medium text-gray-700">
          <span>Tentativas</span>
        </div>
        <div class="<?php echo CLASSES_DASH_INPUT_BLOCK; ?>"><span class="text-sm"><?php echo $usuario['Usuario']['tentativas_login'] ?></span></div>
      </div>

      <?php // Desbloquear ?>
      <?php if ($usuario['Usuario']['tentativas_login'] >= 10) { ?>
        <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
          <div class="w-max flex flex-col text-sm font-medium text-gray-700">
            <a href="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/usuario/desbloquear/' . $usuario['Usuario']['id']); ?>" class="py-2 flex gap-2 text-sm underline text-red-600 hover:text-red-900">Desbloquear acesso</a>
          </div>

        </div>
      <?php } ?>
    <?php } ?>
  </div>

  <?php // Não apagar ?>
  <button type="submit" class="hidden btn-gravar-usuario"></button>
</form>