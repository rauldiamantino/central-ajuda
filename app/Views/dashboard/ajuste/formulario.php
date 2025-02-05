<form method="POST" action="/d/ajustes" class="border-t border-slate-300 w-full flex flex-col gap-4 py-6 form-dashboard-ajustes">
  <input type="hidden" name="_method" value="PUT">
  <div class="grid lg:grid-cols-2">
    <div class="w-full flex flex-col gap-6">
      <?php foreach ($ajustes as $linha): ?>
        <div class="w-full">

          <?php if(isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'artigo_autor') { ?>
            <label class="w-max flex gap-3 items-center justify-start">
              <input type="hidden" name="artigo_autor" value="0">
              <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="artigo_autor">
              <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
              <span class="block text-sm font-medium text-gray-700">Exibir autor do artigo</span>
            </label>
          <?php } ?>

          <?php if(isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'botao_whatsapp') { ?>
            <label class="w-max flex gap-3 items-center justify-start">
              <input type="hidden" name="botao_whatsapp" value="0">
              <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="botao_whatsapp">
              <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
              <span class="block text-sm font-medium text-gray-700">Exibir botão WhatsApp</span>
            </label>
          <?php } ?>

          <?php if(isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_cate_busca') { ?>
            <label class="w-max flex gap-3 items-center justify-start">
              <input type="hidden" name="publico_cate_busca" value="0">
              <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="publico_cate_busca">
              <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
              <span class="block text-sm font-medium text-gray-700">Exibir categorias na página de busca</span>
            </label>
          <?php } ?>

          <?php if(isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_cate_abrir_primeira') { ?>
            <label class="w-max flex gap-3 items-center justify-start">
              <input type="hidden" name="publico_cate_abrir_primeira" value="0">
              <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="publico_cate_abrir_primeira">
              <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
              <span class="block text-sm font-medium text-gray-700">Exibir automaticamente primeira categoria</span>
            </label>
          <?php } ?>

          <?php if(isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_topo_fixo') { ?>
            <label class="w-max flex gap-3 items-center justify-start">
              <input type="hidden" name="publico_topo_fixo" value="0">
              <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="publico_topo_fixo">
              <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
              <span class="block text-sm font-medium text-gray-700">Fixar barra de navegação superior</span>
            </label>
          <?php } ?>

          <?php if (isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_inicio_template') { ?>

            <div class="py-2 border-t margem-divisao"></div>

            <?php
            $templates = [
              1 => 'No máximo 4 colunas',
              2 => 'No máximo 3 colunas',
              3 => 'No máximo 2 colunas',
              4 => 'Coluna única',
            ];
            ?>
            <div class="w-full flex flex-col gap-2">
              <label for="publico_inicio_template" class="block text-sm font-medium text-gray-700">Tela inicial colunas</label>
              <select id="publico_inicio_template" name="publico_inicio_template" class="<?php echo CLASSES_DASH_INPUT; ?>">
                <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
                  <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $linha['Ajuste']['ativo'] ? 'selected' : ''; ?>>
                    <?php echo $linhaTemplate; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php } ?>

          <?php if (isset($linha['Ajuste']['nome']) and $linha['Ajuste']['nome'] == 'publico_inicio_template_alinhamento') { ?>
            <?php
            $templates = [
              1 => 'Texto e ícone centralizados',
              2 => 'Texto e ícone à esquerda',
              3 => 'Texto e ícone na mesma linha e à esquerda',
              4 => 'Texto e ícone na mesma linha e centralizados',
            ];
            ?>
            <div class="w-full flex flex-col gap-2">
              <label for="publico_inicio_template_alinhamento" class="block text-sm font-medium text-gray-700">Tela inicial colunas (alinhamento do texto)</label>
              <select id="publico_inicio_template_alinhamento" name="publico_inicio_template_alinhamento" class="<?php echo CLASSES_DASH_INPUT; ?>">
                <?php foreach ($templates as $chaveTemplate => $linhaTemplate): ?>
                  <option value="<?php echo $chaveTemplate; ?>" <?php echo $chaveTemplate == $linha['Ajuste']['ativo'] ? 'selected' : ''; ?>>
                    <?php echo $linhaTemplate; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php } ?>
        </div>
      <?php endforeach; ?>
      <?php // fim coluna 1 ?>
    </div>
  </div>
</form>