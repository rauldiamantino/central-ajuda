<form method="POST" action="/d/<?php echo $this->usuarioLogado['empresaId'] ?>/ajustes" class="border border-slate-300 w-full flex flex-col gap-4 p-4 rounded-lg shadow bg-white">
  <input type="hidden" name="_method" value="PUT">
  <div class="w-full flex">
    <div class="flex flex-col gap-6">
      <label class="flex items-center justify-start gap-2 cursor-pointer">
        <input type="hidden" name="artigo_autor" value="0">
        <input type="checkbox" value="1" class="sr-only peer" <?php echo $this->buscarAjuste('artigo_autor') ? 'checked' : '' ?> name="artigo_autor">
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        <span class="block text-sm font-medium text-gray-700">Exibir autor do artigo</span>
      </label>
      <label class="flex items-center justify-start gap-2 cursor-pointer">
        <input type="hidden" name="artigo_criado" value="0">
        <input type="checkbox" value="1" class="sr-only peer" <?php echo $this->buscarAjuste('artigo_criado') ? 'checked' : '' ?> name="artigo_criado">
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        <span class="block text-sm font-medium text-gray-700">Exibir data de criação do artigo</span>
      </label>
      <label class="flex items-center justify-start gap-2 cursor-pointer">
        <input type="hidden" name="artigo_modificado" value="0">
        <input type="checkbox" value="1" class="sr-only peer" <?php echo $this->buscarAjuste('artigo_modificado') ? 'checked' : '' ?> name="artigo_modificado">
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        <span class="block text-sm font-medium text-gray-700">Exibir data de atualização do artigo</span>
      </label>
      <label class="flex items-center justify-start gap-2 cursor-pointer">
        <input type="hidden" name="botao_whatsapp" value="0">
        <input type="checkbox" value="1" class="sr-only peer" <?php echo $this->buscarAjuste('botao_whatsapp') ? 'checked' : '' ?> name="botao_whatsapp">
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        <span class="block text-sm font-medium text-gray-700">Exibir botão WhatsApp</span>
      </label>
      <label class="flex items-center justify-start gap-2 cursor-pointer">
        <input type="hidden" name="publico_cate_busca" value="0">
        <input type="checkbox" value="1" class="sr-only peer" <?php echo $this->buscarAjuste('publico_cate_busca') ? 'checked' : '' ?> name="publico_cate_busca">
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        <span class="block text-sm font-medium text-gray-700">Exibir categorias na página de busca</span>
      </label>
      <label class="flex items-center justify-start gap-2 cursor-pointer">
        <input type="hidden" name="publico_cate_abrir_primeira" value="0">
        <input type="checkbox" value="1" class="sr-only peer" <?php echo $this->buscarAjuste('publico_cate_abrir_primeira') ? 'checked' : '' ?> name="publico_cate_abrir_primeira">
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        <span class="block text-sm font-medium text-gray-700">Exibir automaticamente primeira categoria</span>
      </label>
      <label class="flex items-center justify-start gap-2 cursor-pointer">
        <input type="hidden" name="publico_topo_fixo" value="0">
        <input type="checkbox" value="1" class="sr-only peer" <?php echo $this->buscarAjuste('publico_topo_fixo') ? 'checked' : '' ?> name="publico_topo_fixo">
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
        <span class="block text-sm font-medium text-gray-700">Fixar barra de navegação superior</span>
      </label>
    </div>
  </div>
  <div class="w-full flex justify-between">
    <div class="flex gap-2">
      <a href="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/artigos" class="<?php echo CLASSES_DASH_BUTTON_VOLTAR; ?>">Voltar</a>
      <button type="submit" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?>">Gravar</button>
    </div>
  </div>
</form>