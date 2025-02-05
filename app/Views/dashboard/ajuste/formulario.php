<form method="POST" action="/d/ajustes" class="border-t border-slate-300 w-full flex flex-col gap-4 py-6 form-dashboard-ajustes" enctype="multipart/form-data">
  <input type="hidden" name="_method" value="PUT">

  <div class="w-full grid lg:grid-cols-2 gap-10">
    <?php // coluna 1 ?>
    <div class="w-full flex flex-col gap-6">
      <?php foreach ($ajustes as $linha): ?>

        <?php if(isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'artigo_autor') { ?>
          <label class="w-max flex gap-3 items-center justify-start">
            <input type="hidden" name="artigo_autor" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['valor'] == ATIVO ? 'checked' : '' ?> name="artigo_autor">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Exibir autor do artigo</span>
          </label>
        <?php } elseif(isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'botao_whatsapp') { ?>
          <label class="w-max flex gap-3 items-center justify-start">
            <input type="hidden" name="botao_whatsapp" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['valor'] == ATIVO ? 'checked' : '' ?> name="botao_whatsapp">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Exibir botão WhatsApp</span>
          </label>
        <?php } elseif(isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_cate_busca') { ?>
          <label class="w-max flex gap-3 items-center justify-start">
            <input type="hidden" name="publico_cate_busca" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['valor'] == ATIVO ? 'checked' : '' ?> name="publico_cate_busca">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Exibir categorias na página de busca</span>
          </label>
        <?php } elseif(isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_cate_abrir_primeira') { ?>
          <label class="w-max flex gap-3 items-center justify-start">
            <input type="hidden" name="publico_cate_abrir_primeira" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['valor'] == ATIVO ? 'checked' : '' ?> name="publico_cate_abrir_primeira">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Exibir automaticamente primeira categoria</span>
          </label>
        <?php } elseif(isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_topo_fixo') { ?>
          <label class="w-max flex gap-3 items-center justify-start">
            <input type="hidden" name="publico_topo_fixo" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['valor'] == ATIVO ? 'checked' : '' ?> name="publico_topo_fixo">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Fixar barra de navegação superior</span>
          </label>
        <?php } elseif (isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_inicio_foto') { ?>

          <div class="mt-6 border-t margem"></div>

          <div class="w-full flex flex-col gap-2">
            <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
              <input type="hidden" name="publico_inicio_foto" value="<?php echo $linha['Ajuste']['valor']; ?>" class="url-imagem">
              <input type="file" accept="image/*" id="inicio-editar-foto" name="arquivo-foto" class="hidden inicio-editar-foto-escolher" onchange="mostrarImagemInicio(event)">
              <div class="flex flex-col text-sm font-medium text-gray-700">
                <span>Tela inicial busca (imagem)</span>
                <span class="font-extralight">Envie uma imagem de fundo para a tela inicial. O arquivo deve ter até 2MB e estar no formato .svg, .jpg ou .png. Tamanho ideal: 2000px de largura por 600px de altura.</span>
              </div>
              <div class="w-max flex flex-col items-center justify-center gap-4">
                <button type="button" for="inicio-editar-foto" class="mt-2 lg:mt-0 w-max h-max flex items-center justify-center border border-gray-200 hover:border-gray-300 rounded-lg inicio-btn-foto-editar-escolher" onclick="alterarImagemInicio(event)">
                  <div class="w-max h-max">
                    <img src="<?php echo $this->renderImagem($linha['Ajuste']['valor']); ?>" class="p-1 w-20 h-20 rounded-lg inicio-alterar-foto" onerror="this.onerror=null; this.src='/img/sem-imagem.svg';">
                  </div>
                  <span class="text-gray-700 h-max w-max empresa-txt-imagem-editar-escolher"><?php echo $linha['Ajuste']['valor'] ? '' : ''; ?></span>
                </button>

                <?php if ($linha['Ajuste']['valor']) { ?>
                  <button type="button" class="text-xs text-red-600 hover:underline duration-150 inicio-remover-foto" onclick="removerFotoInicio()">Remover</button>
                <?php } ?>
              </div>
              <h3 class="mt-4 hidden font-light text-left text-sm text-red-800 erro-inicio-foto">Erro</h3>
            </div>
          </div>
        <?php } elseif (isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_inicio_texto_cor') { ?>
          <div class="w-full flex flex-col gap-2">
            <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
              <div class="flex flex-col text-sm font-medium text-gray-700">
                <span>Tela inicial (cor do texto)</span>
                <span class="font-extralight">Selecione uma cor alternativa para o título e subtítulo, quando anexar uma imagem.</span>
              </div>
              <input type="color" id="publico_inicio_texto_cor" name="publico_inicio_texto_cor" class="w-10 h-10" value="<?php echo $linha['Ajuste']['valor']; ?>">
            </div>
          </div>
        <?php } elseif (isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_inicio_busca_cor') { ?>
          <div class="w-full flex flex-col gap-2">
            <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
              <div class="flex flex-col text-sm font-medium text-gray-700">
                <span>Tela inicial busca (cor do texto e ícone)</span>
                <span class="font-extralight">Selecione uma cor alternativa para o ícone e texto do campo de busca, quando anexar uma imagem.</span>
              </div>
              <input type="color" id="publico_inicio_busca_cor" name="publico_inicio_busca_cor" class="w-10 h-10 rounded-lg" value="<?php echo $linha['Ajuste']['valor']; ?>">
            </div>
          </div>
        <?php } elseif (isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_inicio_busca_borda') { ?>
          <div class="w-full flex flex-col gap-2">
            <div class="w-full lg:w-[700px] py-4 grid lg:gap-10 lg:grid-cols-[250px_1fr] items-center">
              <div class="flex flex-col text-sm font-medium text-gray-700">
                <span>Tela inicial busca (cor da borda)</span>
                <span class="font-extralight">Selecione uma cor alternativa para a borda do campo de busca, quando anexar uma imagem.</span>
              </div>
              <input type="color" id="publico_inicio_busca_borda" name="publico_inicio_busca_borda" class="w-10 h-10 rounded-lg" value="<?php echo $linha['Ajuste']['valor']; ?>">
            </div>
          </div>
        <?php } elseif (isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_inicio_template') { ?>

          <div class="mt-6 border-t margem"></div>

          <?php
          $templates = [
            1 => 'No máximo 4 colunas (padrão)',
            2 => 'No máximo 3 colunas',
            3 => 'No máximo 2 colunas',
            4 => 'Coluna única',
          ];
          ?>
          <div class="w-full flex flex-col gap-2">
            <label for="publico_inicio_template" class="block text-sm font-medium text-gray-700">Tela inicial colunas</label>
            <select id="publico_inicio_template" name="publico_inicio_template" class="<?php echo CLASSES_DASH_INPUT; ?>">
              <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
                <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $linha['Ajuste']['valor'] ? 'selected' : ''; ?>>
                  <?php echo $linhaTemplate; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php } elseif (isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_inicio_template_alinhamento') { ?>
          <?php
          $templates = [
            1 => 'Texto e ícone centralizados (padrão)',
            2 => 'Texto e ícone à esquerda',
            3 => 'Texto e ícone na mesma linha',
          ];
          ?>
          <div class="w-full flex flex-col gap-2">
            <label for="publico_inicio_template_alinhamento" class="block text-sm font-medium text-gray-700">Tela inicial colunas (alinhamento do texto)</label>
            <select id="publico_inicio_template_alinhamento" name="publico_inicio_template_alinhamento" class="<?php echo CLASSES_DASH_INPUT; ?>">
              <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
                <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $linha['Ajuste']['valor'] ? 'selected' : ''; ?>>
                  <?php echo $linhaTemplate; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php } elseif (isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_inicio_titulo') { ?>

          <div class="mt-6 border-t margem"></div>

          <div class="w-full flex flex-col gap-2">
            <label for="publico_inicio_titulo" class="block text-sm font-medium text-gray-700">Tela inicial título</label>
            <input type="text" id="publico_inicio_titulo" name="publico_inicio_titulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $linha['Ajuste']['valor']; ?>">
          </div>
        <?php } elseif (isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_inicio_subtitulo') { ?>
          <div class="w-full flex flex-col gap-2">
            <label for="publico_inicio_subtitulo" class="block text-sm font-medium text-gray-700">Tela inicial subtítulo</label>
            <input type="text" id="publico_inicio_subtitulo" name="publico_inicio_subtitulo" class="<?php echo CLASSES_DASH_INPUT; ?>" value="<?php echo $linha['Ajuste']['valor']; ?>">
          </div>
        <?php } elseif (isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_inicio_busca_tamanho') { ?>

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
                <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $linha['Ajuste']['valor'] ? 'selected' : ''; ?>>
                  <?php echo $linhaTemplate; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php } elseif (isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_inicio_busca_alinhamento') { ?>
          <?php
          $templates = [
            1 => 'Alinhado à esquerda',
            2 => 'Centralizado (padrão)',
            3 => 'Alinhado à direita',
          ];
          ?>
          <div class="w-full flex flex-col gap-2">
            <label for="publico_inicio_busca_alinhamento" class="block text-sm font-medium text-gray-700">Tela inicial busca (alinhamento)</label>
            <select id="publico_inicio_busca_alinhamento" name="publico_inicio_busca_alinhamento" class="<?php echo CLASSES_DASH_INPUT; ?>">
              <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
                <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $linha['Ajuste']['valor'] ? 'selected' : ''; ?>>
                  <?php echo $linhaTemplate; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php } else {
          continue;
        }
        ?>
      <?php endforeach; ?>
    </div>
    <?php // fim coluna 1 ?>
  </div>
</form>