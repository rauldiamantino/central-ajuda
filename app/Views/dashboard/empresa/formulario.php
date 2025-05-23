<?php
// Preload
if (isset($empresa['Empresa']['logo']) and $empresa['Empresa']['logo']) {
  echo '<link rel="preload" href="' . $this->renderImagem($empresa['Empresa']['logo']) . '" as="image">';
}

if (isset($empresa['Empresa']['favicon']) and $empresa['Empresa']['favicon']) {
  echo '<link rel="preload" href="' . $this->renderImagem($empresa['Empresa']['favicon']) . '" as="image">';
}
?>

<form method="POST" action="<?php echo '/d/empresa/editar/' . $empresa['Empresa']['id']; ?>" class="border-t border-slate-300 w-full h-full flex flex-col gap-4 form-editar-empresa" id="form-editar-empresa" data-empresa-id="<?php echo $empresa['Empresa']['id'] ?>" data-imagem-atual="<?php echo $empresa['Empresa']['logo']; ?>" data-favicon-atual="<?php echo $empresa['Empresa']['favicon']; ?>" enctype="multipart/form-data">
  <input type="hidden" name="_method" value="PUT">
  <div class="w-full flex flex-col divide-y">

    <?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
      <?php // Status da empresa ?>
      <div class="w-full lg:w-[700px] py-6 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
        <div class="flex flex-col text-sm font-medium text-gray-700">
          <span class="block text-sm font-medium text-gray-700">Status plataforma</span>
          <span class="font-extralight">Campo restrito somente para o suporte. Ao desativá-lo, todos os usuários perdem o acesso e a página pública é desativada</span>
        </div>
        <label class="w-max flex flex-col items-start gap-1 cursor-pointer">
          <input type="hidden" name="ativo" value="0">
          <input type="checkbox" name="ativo" value="1" class="sr-only peer" <?php echo $empresa['Empresa']['ativo'] ? 'checked' : '' ?> <?php echo $this->usuarioLogado['padrao'] != USUARIO_SUPORTE ? 'disabled' : '' ?>>
          <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        </label>
      </div>

      <?php // Domínio personalizado ?>
      <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
        <div class="flex flex-col text-sm font-medium text-gray-700">
          <span>Domínio personalizado</span>
          <span class="font-extralight">
            Campo restrito somente para o suporte. Será utilizado para acessar a sua Central de Ajuda, por exemplo: <strong>ajuda.suaempresa.com.br</strong>.<br>
            Certifique-se de que está apontado para <a href="https://www.360help.com.br" target="_blank" class="text-blue-600">https://360help.com.br</a>.
          </span>
        </div>
        <input type="text" id="empresa-editar-subdominio-2" name="subdominio_2" class="<?php echo CLASSES_DASH_INPUT; ?>" placeholder="https://ajuda.suaempresa.com.br" value="<?php echo $empresa['Empresa']['subdominio_2']; ?>">
      </div>
    <?php } ?>

    <?php // Nome da empresa ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="empresa-editar-assinatura-id" class="w-full block text-sm font-medium text-gray-700">Nome da empresa <span class="text-red-800 font-extralight">(obrigatório)</span></label>
      <input type="text" id="empresa-editar-nome" name="nome" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $empresa['Empresa']['nome']; ?>">
    </div>

    <?php // CNPJ ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="empresa-editar-cnpj" class="block text-sm font-medium text-gray-700">CNPJ <span class="text-red-800 font-extralight">(obrigatório)</span></label>
      <input type="text" id="empresa-editar-cnpj" name="cnpj" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $empresa['Empresa']['cnpj']; ?>">
    </div>

    <?php // Telefone ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <label for="empresa-editar-telefone" class="block text-sm font-medium text-gray-700">Celular com DDD</label>
      <input type="text" id="empresa-editar-telefone" name="telefone" class="<?php echo CLASSES_DASH_INPUT; ?>" placeholder="00 00000 0000" value="<?php echo $empresa['Empresa']['telefone']; ?>">
    </div>

    <?php // URL Site empresa ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <div class="flex flex-col text-sm font-medium text-gray-700">
        <span>Link para site da empresa</span>
        <span class="font-extralight">Adicionando o link do seu site, um botão de redirecionamento aparecerá na sua Central de Ajuda.</span>
      </div>
      <input type="text" id="empresa-editar-url_site" name="url_site" class="<?php echo CLASSES_DASH_INPUT; ?>" placeholder="https://www.seusite.com.br" value="<?php echo $empresa['Empresa']['url_site']; ?>">
    </div>

    <?php // Meta Title ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <div class="flex flex-col text-sm font-medium text-gray-700">
        <span>Meta Title</span>
        <span class="font-extralight">
          Defina o título padrão que será exibido nos resultados de busca para a página de início da sua Central de Ajuda.
        </span>
      </div>
      <input type="text" name="meta_titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" placeholder="Ex.: Central de Ajuda - Nome da Empresa" value="<?php echo $empresa['Empresa']['meta_titulo']; ?>">
    </div>

    <?php // Meta Description ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <div class="flex flex-col text-sm font-medium text-gray-700">
        <span>Meta Description</span>
        <span class="font-extralight">
          Insira uma descrição breve que resuma o propósito da página de início da sua Central de Ajuda.
        </span>
      </div>
      <input type="text" name="meta_descricao" class="<?php echo CLASSES_DASH_INPUT; ?>" placeholder="Ex.: Saiba mais sobre nossos serviços e como podemos ajudar sua empresa." value="<?php echo $empresa['Empresa']['meta_descricao']; ?>">
    </div>

    <?php // Logo ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <input type="hidden" name="logo" value="<?php echo $empresa['Empresa']['logo']; ?>" class="url-imagem">
      <input type="file" accept="image/*" id="empresa-editar-imagem" name="arquivo-logo" class="hidden empresa-editar-imagem-escolher" onchange="mostrarImagemLogo(event)">
      <div class="flex flex-col text-sm font-medium text-gray-700">
        <span>Logo</span>
        <span class="font-extralight">Envie uma imagem para representar a sua empresa. O arquivo deve ter até 2MB e estar no formato .svg, .jpg ou .png. Tamanho ideal: 200px de largura por 70px de altura.</span>
      </div>
      <button type="button" for="empresa-editar-imagem" class="w-full h-24 flex items-center justify-center <?php echo CLASSES_DASH_INPUT; ?> empresa-btn-imagem-editar-escolher" onclick="alterarLogo(event)">
        <div class="h-full">
          <img src="<?php echo $this->renderImagem($empresa['Empresa']['logo']); ?>" class="w-full h-full empresa-alterar-logo" onerror="this.onerror=null; this.src='/img/sem-imagem.svg';">
        </div>
        <span class="ml-2 text-gray-700 h-max w-max empresa-txt-imagem-editar-escolher"><?php echo $empresa['Empresa']['logo'] ? '' : 'Adicionar'; ?></span>
        <h3 class="hidden font-light text-left text-sm text-red-800 erro-empresa-imagem"></h3>
      </button>
    </div>

    <?php // Favicon ?>
    <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
      <input type="hidden" name="favicon" value="<?php echo $empresa['Empresa']['favicon']; ?>" class="url-favicon">
      <input type="file" accept="image/*" id="empresa-editar-favicon" name="arquivo-favicon"  class="hidden empresa-editar-favicon-escolher" onchange="mostrarImagemFavicon(event)">
      <div class="flex flex-col text-sm font-medium text-gray-700">
        <span>Favicon</span>
        <span class="font-extralight">Adicione um ícone personalizado para a barra de navegação. Deve ser uma imagem .svg, .jpg ou .png.</span>
      </div>
      <button type="button" for="empresa-editar-favicon" class="w-full h-24 flex items-center justify-center <?php echo CLASSES_DASH_INPUT; ?> empresa-btn-favicon-editar-escolher" onclick="alterarFavicon(event)">
        <div class="h-full">
          <img src="<?php echo $this->renderImagem($empresa['Empresa']['favicon']); ?>" class="w-full h-full empresa-alterar-favicon" onerror="this.onerror=null; this.src='/img/sem-imagem.svg';">
        </div>
        <span class="ml-2 text-gray-700 h-max w-max empresa-txt-favicon-editar-escolher"><?php echo $empresa['Empresa']['favicon'] ? '' : 'Adicionar'; ?></span>
        <h3 class="hidden font-light text-left text-sm text-red-800 erro-empresa-favicon"></h3>
      </button>
    </div>
  </div>
</form>