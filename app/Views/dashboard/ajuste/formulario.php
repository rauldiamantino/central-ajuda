<form method="POST" action="<?php echo baseUrl('/' . $this->usuarioLogado['subdominio'] . '/d/ajustes'); ?>" class="border-t border-slate-300 w-full flex flex-col gap-4 py-6 form-dashboard-ajustes">
  <input type="hidden" name="_method" value="PUT">
  <div class="w-full flex">
    <div class="flex flex-col gap-6">
      <?php foreach($ajustes as $chave => $linha): ?>
        <label class="w-max flex items-center justify-start gap-2 cursor-pointer">
          <?php if ($linha['Ajuste']['nome'] == 'artigo_autor') { ?>
            <input type="hidden" name="artigo_autor" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="artigo_autor">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Exibir autor do artigo</span>
          <?php } elseif ($linha['Ajuste']['nome'] == 'artigo_criado') { ?>
            <input type="hidden" name="artigo_criado" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="artigo_criado">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Exibir data de criação do artigo</span>
          <?php } elseif ($linha['Ajuste']['nome'] == 'artigo_modificado') { ?>
            <input type="hidden" name="artigo_modificado" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="artigo_modificado">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Exibir data de atualização do artigo</span>
          <?php } elseif ($linha['Ajuste']['nome'] == 'botao_whatsapp') { ?>
            <input type="hidden" name="botao_whatsapp" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="botao_whatsapp">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Exibir botão WhatsApp</span>
          <?php } elseif ($linha['Ajuste']['nome'] == 'publico_cate_busca') { ?>
            <input type="hidden" name="publico_cate_busca" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="publico_cate_busca">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Exibir categorias na página de busca</span>
          <?php } elseif ($linha['Ajuste']['nome'] == 'publico_cate_abrir_primeira') { ?>
            <input type="hidden" name="publico_cate_abrir_primeira" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="publico_cate_abrir_primeira">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Exibir automaticamente primeira categoria</span>
          <?php } elseif ($linha['Ajuste']['nome'] == 'publico_topo_fixo') { ?>
            <input type="hidden" name="publico_topo_fixo" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="publico_topo_fixo">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Fixar barra de navegação superior</span>
          <?php } elseif ($linha['Ajuste']['nome'] == 'publico_cor_bloco_inicio') { ?>
            <input type="hidden" name="publico_cor_bloco_inicio" value="0">
            <input type="checkbox" value="1" class="sr-only peer" <?php echo $linha['Ajuste']['ativo'] == ATIVO ? 'checked' : '' ?> name="publico_cor_bloco_inicio">
            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            <span class="block text-sm font-medium text-gray-700">Exibir cor de fundo ao passar o mouse nos Principais tópicos</span>
          <?php } ?>
        </label>
      <?php endforeach; ?>
    </div>
  </div>
</form>