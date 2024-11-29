<div class="mx-auto w-full flex flex-col items-center gap-16 md:px-4 xl:px-6 py-12">
  <div class="w-full flex flex-col gap-2">
    <h3 class="font-extralight text-xs text-gray-800">CATEGORIAS</h3>
    <div class="w-full h-max grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 justify-start gap-4">
      <?php foreach ($categorias as $chave => $linha): ?>
        <a href="<?php echo baseUrl('/' . $subdominio . '/categoria/' . $linha['Categoria']['id']); ?>" class="border border-slate-200 shadow p-5 h-full min-h-[200px] bg-white flex flex-col gap-4 justify-center items-center text-center rounded-lg hover:scale-105 duration-100">
          <?php if ($linha['Categoria']['icone'] and $this->iconeExiste($linha['Categoria']['icone'])) { ?>
            <div class="w-full flex items-center justify-center">
              <div class="w-12 h-12 pers-publico-icones template-cor-<?php echo $corPrimaria; ?>">
                <?php echo $this->renderIcone($linha['Categoria']['icone']); ?>
              </div>
            </div>
          <?php } ?>
          <h3 class="text-xl bloco-categoria-nome break-words w-full"><?php echo $linha['Categoria']['nome'] ?></h3>
          <div class="font-extralight text-gray-400 bloco-categoria-descricao"><?php echo $linha['Categoria']['descricao'] ?></div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
