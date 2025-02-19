<form method="POST" action="/d/ajustes" class="border-t border-slate-300 w-full flex flex-col gap-4 py-6 form-dashboard-ajustes" enctype="multipart/form-data">
  <input type="hidden" name="_method" value="PUT">

  <div class="w-full grid lg:grid-cols-2 gap-10">

    <?php // coluna 1 ?>
    <div class="w-full flex flex-col gap-6">
      <?php if(isset($ajustes['publico_menu_lateral'])) { ?>
        <label class="w-full flex gap-3 items-center justify-start">
          <input type="hidden" name="publico_menu_lateral" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $ajustes['publico_menu_lateral'] == ATIVO ? 'checked' : '' ?> name="publico_menu_lateral">
          <div class="relative min-w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Exibir menu lateral</span>
        </label>
      <?php } ?>

      <?php if(isset($ajustes['publico_cate_busca'])) { ?>
        <label class="w-full flex gap-3 items-center justify-start">
          <input type="hidden" name="publico_cate_busca" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $ajustes['publico_cate_busca'] == ATIVO ? 'checked' : '' ?> name="publico_cate_busca">
          <div class="relative min-w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Exibir categorias na página de busca</span>
        </label>
      <?php } ?>

      <?php if(isset($ajustes['publico_cate_abrir_primeira'])) { ?>
        <label class="w-full flex gap-3 items-center justify-start">
          <input type="hidden" name="publico_cate_abrir_primeira" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $ajustes['publico_cate_abrir_primeira'] == ATIVO ? 'checked' : '' ?> name="publico_cate_abrir_primeira">
          <div class="relative min-w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Exibir automaticamente primeira categoria</span>
        </label>
      <?php } ?>

      <?php if(isset($ajustes['publico_topo_borda_inicio'])) { ?>
        <div class="mt-6 border-t margem"></div>

        <label class="w-full flex gap-3 items-center justify-start">
          <input type="hidden" name="publico_topo_borda_inicio" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $ajustes['publico_topo_borda_inicio'] == ATIVO ? 'checked' : '' ?> name="publico_topo_borda_inicio">
          <div class="relative min-w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Exibir borda do topo (início)</span>
        </label>
      <?php } ?>

      <?php if(isset($ajustes['publico_topo_borda_demais'])) { ?>
        <label class="w-full flex gap-3 items-center justify-start">
          <input type="hidden" name="publico_topo_borda_demais" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $ajustes['publico_topo_borda_demais'] == ATIVO ? 'checked' : '' ?> name="publico_topo_borda_demais">
          <div class="relative min-w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Exibir borda do topo (demais telas)</span>
        </label>
      <?php } ?>

      <?php if(isset($ajustes['publico_topo_fixo'])) { ?>
        <label class="w-full flex gap-3 items-center justify-start">
          <input type="hidden" name="publico_topo_fixo" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $ajustes['publico_topo_fixo'] == ATIVO ? 'checked' : '' ?> name="publico_topo_fixo">
          <div class="relative min-w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Fixar barra de navegação superior</span>
        </label>
      <?php } ?>

      <?php if(isset($ajustes['publico_inicio_topo_transparente'])) { ?>
        <div class="mt-6 border-t margem"></div>

        <label class="w-full flex gap-3 items-center justify-start">
          <input type="hidden" name="publico_inicio_topo_transparente" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $ajustes['publico_inicio_topo_transparente'] == ATIVO ? 'checked' : '' ?> name="publico_inicio_topo_transparente">
          <div class="relative min-w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>

          <div class="flex flex-col text-sm font-medium text-gray-700">
            <span class="block text-sm font-medium text-gray-700">Topo transparente</span>
          </div>
        </label>
      <?php } ?>


      <?php if(isset($ajustes['publico_inicio_cor_fundo'])) { ?>
        <label class="w-full flex gap-3 items-center justify-start">
          <input type="hidden" name="publico_inicio_cor_fundo" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $ajustes['publico_inicio_cor_fundo'] == ATIVO ? 'checked' : '' ?> name="publico_inicio_cor_fundo">
          <div class="relative min-w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Exibir cor primária no fundo da tela de início</span>
        </label>
      <?php } ?>

      <?php if(isset($ajustes['publico_inicio_logo_cor_inverter'])) { ?>
        <label class="w-full flex gap-3 items-center justify-start">
          <input type="hidden" name="publico_inicio_logo_cor_inverter" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $ajustes['publico_inicio_logo_cor_inverter'] == ATIVO ? 'checked' : '' ?> name="publico_inicio_logo_cor_inverter">
          <div class="relative min-w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <div class="flex flex-col text-sm font-medium text-gray-700">
            <span class="block text-sm font-medium text-gray-700">Inverter a cor do logo na tela de início</span>
            <span class="font-extralight">Também inverte a cor do menu hamburguer no mobile</span>
          </div>
        </label>
      <?php } ?>

      <?php if(isset($ajustes['botao_whatsapp'])) { ?>
        <div class="mt-6 border-t margem"></div>

        <label class="w-full flex gap-3 items-center justify-start">
          <input type="hidden" name="botao_whatsapp" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $ajustes['botao_whatsapp'] == ATIVO ? 'checked' : '' ?> name="botao_whatsapp">
          <div class="relative min-w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Exibir botão WhatsApp</span>
        </label>
      <?php } ?>

      <?php if(isset($ajustes['artigo_autor'])) { ?>
        <label class="w-full flex gap-3 items-center justify-start">
          <input type="hidden" name="artigo_autor" value="0">
          <input type="checkbox" value="1" class="sr-only peer" <?php echo $ajustes['artigo_autor'] == ATIVO ? 'checked' : '' ?> name="artigo_autor">
          <div class="relative min-w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
          <span class="block text-sm font-medium text-gray-700">Exibir autor do artigo</span>
        </label>
      <?php } ?>
    </div>
    <?php // fim coluna 2 ?>

    <?php // coluna 2 ?>
    <div class="w-full flex flex-col gap-6">
      <?php if (isset($ajustes['publico_largura_geral'])) { ?>
        <?php
        $templates = [
          1 => 'Grande',
          2 => 'Média (padrão)',
          3 => 'Pequena',
          4 => 'Extra pequena',
        ];
        ?>
        <div class="w-full flex flex-col gap-2">
          <label for="publico_largura_geral" class="block text-sm font-medium text-gray-700">Largura geral</label>
          <select id="publico_largura_geral" name="publico_largura_geral" class="<?php echo CLASSES_DASH_INPUT; ?>">
            <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
              <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $ajustes['publico_largura_geral'] ? 'selected' : ''; ?>>
                <?php echo $linhaTemplate; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_cor_primaria'])) { ?>
        <div class="mt-6 border-t margem"></div>

        <div class="w-full flex flex-col gap-2">
          <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
            <div class="flex flex-col text-sm font-medium text-gray-700">
              <span>Cor primária</span>
              <span class="font-extralight">Escolha a cor principal que representará sua empresa. Esta cor será usada em partes de destaque da sua Central de Ajuda, como botões e cabeçalhos.</span>
            </div>
            <div class="mt-4 lg:mt-0 flex flex-wrap gap-2">
              <?php // Azul escuro ?>
              <label class="inline-block">
                <input type="radio" name="publico_cor_primaria" value="1" class="hidden peer" <?php echo ($ajustes['publico_cor_primaria'] == 1) ? 'checked' : ''; ?> />
                <span class="block w-9 h-9 bg-[#2d2d2d] cursor-pointer rounded peer-checked:ring-2 peer-checked:ring-offset-1 peer-checked:ring-gray-500"></span>
              </label>
              <?php // Laranja ?>
              <label class="inline-block">
                <input type="radio" name="publico_cor_primaria" value="2" class="hidden peer" <?php echo ($ajustes['publico_cor_primaria'] == 2) ? 'checked' : ''; ?> />
                <span class="block w-9 h-9 bg-[#f05829] cursor-pointer rounded peer-checked:ring-2 peer-checked:ring-offset-1 peer-checked:ring-gray-500"></span>
              </label>
              <?php // Cinza azulado ?>
              <label class="inline-block">
                <input type="radio" name="publico_cor_primaria" value="8" class="hidden peer" <?php echo ($ajustes['publico_cor_primaria'] == 8) ? 'checked' : ''; ?> />
                <span class="block w-9 h-9 bg-[#4A5568] cursor-pointer rounded peer-checked:ring-2 peer-checked:ring-offset-1 peer-checked:ring-gray-500"></span>
              </label>
              <?php // Roxo escuro ?>
              <label class="inline-block">
                <input type="radio" name="publico_cor_primaria" value="9" class="hidden peer" <?php echo ($ajustes['publico_cor_primaria'] == 9) ? 'checked' : ''; ?> />
                <span class="block w-9 h-9 bg-[#4B0082] cursor-pointer rounded peer-checked:ring-2 peer-checked:ring-offset-1 peer-checked:ring-gray-500"></span>
              </label>
            </div>
          </div>
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_inicio_texto_cor_desktop'])) { ?>

        <div class="w-full flex flex-col gap-2">
          <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
            <div class="flex flex-col text-sm font-medium text-gray-700">
              <span>Tela inicial texto</span>
              <span class="font-extralight">Selecione uma cor alternativa para o título, subtítulo e botão login <span class="font-normal">quando anexar uma imagem</span>.</span>
            </div>
            <input type="color" id="publico_inicio_texto_cor_desktop" name="publico_inicio_texto_cor_desktop" class="w-10 h-10 cursor-pointer" value="<?php echo $ajustes['publico_inicio_texto_cor_desktop']; ?>">
          </div>
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_inicio_foto_desktop'])) { ?>
        <div class="w-full flex flex-col gap-2">
          <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
            <input type="hidden" name="publico_inicio_foto_desktop" value="<?php echo $ajustes['publico_inicio_foto_desktop']; ?>" class="url-imagem">
            <input type="file" accept="image/*" id="inicio-editar-foto-desktop" name="arquivo-foto-desktop" class="hidden inicio-editar-foto-desktop-escolher" onchange="mostrarImagemDesktopInicio(event)">
            <div class="flex flex-col text-sm font-medium text-gray-700">
              <span>Tela inicial imagem</span>
              <span class="font-extralight">Envie uma imagem de fundo para a tela inicial. O arquivo deve ter até 2MB e estar no formato .svg, .jpg ou .png.</span>
            </div>
            <div class="w-max flex flex-col items-center justify-center gap-4">
              <button type="button" for="inicio-editar-foto-desktop" class="relative mt-2 lg:mt-0 w-max h-max flex items-center justify-center border border-gray-200 hover:border-gray-300 rounded-lg inicio-btn-foto-desktop-editar-escolher" onclick="alterarImagemDesktopInicio(event)">
                <div class="w-max h-max">
                  <img src="<?php echo $this->renderImagem($ajustes['publico_inicio_foto_desktop']); ?>" class="p-1 w-20 h-20 rounded-lg inicio-alterar-foto-desktop" onerror="this.onerror=null; this.src='/img/sem-imagem.svg';">
                </div>
                <span class="text-gray-700 h-max w-max empresa-txt-imagem-desktop-editar-escolher"><?php echo $ajustes['publico_inicio_foto_desktop'] ? '' : ''; ?></span>
                <div class="hidden efeito-loader-min"></div>
              </button>

              <?php if ($ajustes['publico_inicio_foto_desktop']) { ?>
                <button type="button" class="text-xs text-red-600 hover:underline duration-150 inicio-remover-foto" onclick="removerFotoDesktopInicio()">Remover</button>
              <?php } ?>
            </div>
            <h3 class="mt-4 hidden font-light text-left text-sm text-red-800 erro-inicio-foto-desktop">Erro</h3>
          </div>
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_inicio_titulo'])) { ?>
        <div class="mt-6 border-t margem"></div>

        <div class="w-full flex flex-col gap-2">
          <label for="publico_inicio_titulo" class="block text-sm font-medium text-gray-700">Tela inicial título</label>
          <input type="text" id="publico_inicio_titulo" name="publico_inicio_titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $ajustes['publico_inicio_titulo']; ?>">
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_inicio_subtitulo'])) { ?>
        <div class="w-full flex flex-col gap-2">
          <label for="publico_inicio_subtitulo" class="block text-sm font-medium text-gray-700">Tela inicial subtítulo</label>
          <input type="text" id="publico_inicio_subtitulo" name="publico_inicio_subtitulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $ajustes['publico_inicio_subtitulo']; ?>">
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_inicio_busca_tamanho'])) { ?>

        <div class="mt-6 border-t margem"></div>

        <?php
        $templates = [
          1 => 'Pequeno',
          2 => 'Médio (padrão)',
          3 => 'Grande',
        ];
        ?>
        <div class="w-full flex flex-col gap-2">
          <label for="publico_inicio_busca_tamanho" class="block text-sm font-medium text-gray-700">Tela inicial busca (tamanho)</label>
          <select id="publico_inicio_busca_tamanho" name="publico_inicio_busca_tamanho" class="<?php echo CLASSES_DASH_INPUT; ?>">
            <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
              <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $ajustes['publico_inicio_busca_tamanho'] ? 'selected' : ''; ?>>
                <?php echo $linhaTemplate; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_inicio_busca_alinhamento'])) { ?>
        <?php
        $templates = [
          1 => 'Alinhado à esquerda',
          2 => 'Centralizado (padrão)',
          3 => 'Alinhado à direita',
        ];
        ?>
        <div class="w-full flex flex-col gap-2">
          <label for="publico_inicio_busca_alinhamento" class="block text-sm font-medium text-gray-700">Tela inicial busca (alinhamento do bloco)</label>
          <select id="publico_inicio_busca_alinhamento" name="publico_inicio_busca_alinhamento" class="<?php echo CLASSES_DASH_INPUT; ?>">
            <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
              <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $ajustes['publico_inicio_busca_alinhamento'] ? 'selected' : ''; ?>>
                <?php echo $linhaTemplate; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_inicio_busca_alinhamento_texto'])) { ?>
        <?php
        $templates = [
          1 => 'Alinhado à esquerda (padrão)',
          2 => 'Centralizado',
          3 => 'Alinhado à direita',
        ];
        ?>
        <div class="w-full flex flex-col gap-2">
          <label for="publico_inicio_busca_alinhamento_texto" class="block text-sm font-medium text-gray-700">Tela inicial busca (alinhamento do texto)</label>
          <select id="publico_inicio_busca_alinhamento_texto" name="publico_inicio_busca_alinhamento_texto" class="<?php echo CLASSES_DASH_INPUT; ?>">
            <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
              <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $ajustes['publico_inicio_busca_alinhamento_texto'] ? 'selected' : ''; ?>>
                <?php echo $linhaTemplate; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_inicio_busca'])) { ?>
        <?php
        $templates = [
          1 => 'Campo de busca com transparência (padrão)',
          2 => 'Campo de busca sem transparência e com borda',
          3 => 'Campo de busca sem transparência e sem borda',
        ];
        ?>
        <div class="w-full flex flex-col gapbusca2">
          <label for="publico_inicio_busca" class="block text-sm font-medium text-gray-700">Tela inicial busca</label>
          <select id="publico_inicio_busca" name="publico_inicio_busca" class="<?php echo CLASSES_DASH_INPUT; ?>">
            <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
              <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $ajustes['publico_inicio_busca'] ? 'selected' : ''; ?>>
                <?php echo $linhaTemplate; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_inicio_arredondamento'])) { ?>
        <div class="mt-6 border-t margem"></div>

        <?php
        $templates = [
          0 => 'Sem efeito (padrão)',
          1 => 'Arredondamento externo',
          2 => 'Arredondamento interno',
        ];
        ?>
        <div class="w-full flex flex-col gapbusca2">
          <label for="publico_inicio_arredondamento" class="block text-sm font-medium text-gray-700">Tela inicial (efeito de arredondamento)</label>
          <select id="publico_inicio_arredondamento" name="publico_inicio_arredondamento" class="<?php echo CLASSES_DASH_INPUT; ?>">
            <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
              <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $ajustes['publico_inicio_arredondamento'] ? 'selected' : ''; ?>>
                <?php echo $linhaTemplate; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_inicio_template'])) { ?>
        <div class="mt-6 border-t margem"></div>

        <?php
        $templates = [
          1 => 'No máximo 4 colunas',
          2 => 'No máximo 3 colunas (padrão)',
          3 => 'No máximo 2 colunas',
          4 => 'Coluna única',
        ];
        ?>
        <div class="w-full flex flex-col gap-2">
          <label for="publico_inicio_template" class="block text-sm font-medium text-gray-700">Tela inicial colunas</label>
          <select id="publico_inicio_template" name="publico_inicio_template" class="<?php echo CLASSES_DASH_INPUT; ?>">
            <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
              <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $ajustes['publico_inicio_template'] ? 'selected' : ''; ?>>
                <?php echo $linhaTemplate; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_inicio_template_alinhamento'])) { ?>
        <?php
        $templates = [
          1 => 'Texto e ícone centralizados (padrão)',
          2 => 'Texto e ícone à esquerda',
          3 => 'Texto e ícone na mesma linha (somente largura grande ou até 2 colunas)',
        ];
        ?>
        <div class="w-full flex flex-col gap-2">
          <label for="publico_inicio_template_alinhamento" class="block text-sm font-medium text-gray-700">Tela inicial colunas (alinhamento do texto)</label>
          <select id="publico_inicio_template_alinhamento" name="publico_inicio_template_alinhamento" class="<?php echo CLASSES_DASH_INPUT; ?>">
            <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
              <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $ajustes['publico_inicio_template_alinhamento'] ? 'selected' : ''; ?>>
                <?php echo $linhaTemplate; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php } ?>

      <?php if (isset($ajustes['publico_inicio_colunas_efeito'])) { ?>
        <?php
        $templates = [
          0 => 'Sem efeito',
          1 => 'Zoom card (padrão)',
          2 => 'Escurecer borda',
          3 => 'Escurecer fundo',
          4 => 'Cor primária no fundo',
        ];
        ?>
        <div class="w-full flex flex-col gap-2">
          <label for="publico_inicio_colunas_efeito" class="block text-sm font-medium text-gray-700">Tela inicial colunas (efeito ao passar o mouse)</label>
          <select id="publico_inicio_colunas_efeito" name="publico_inicio_colunas_efeito" class="<?php echo CLASSES_DASH_INPUT; ?>">
            <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
              <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $ajustes['publico_inicio_colunas_efeito'] ? 'selected' : ''; ?>>
                <?php echo $linhaTemplate; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php } ?>
    </div>
    <?php // fim coluna 2 ?>

  </div>
</form>