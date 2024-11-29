<div class="w-full flex flex-col py-14">
  <div class="pb-6 flex gap-2 font-light text-sm publico-migalhas">
    <a href="<?php echo baseUrl('/' . $subdominio); ?>" class="hover:underline">Início</a>
    <span>></span>
    <span class="underline">Busca</span>
  </div>

  <div class="flex flex-col justify-between items-start gap-4 py-5 mb-0 publico-artigo-blocos publico-artigo-topo">
    <h1 class="publico-artigo-titulo"> Exibindo resultados para "<?php echo $textoBusca ?>"</h1>
  </div>

  <?php if ($resultadoBuscar) { ?>
    <div class="flex flex-col gap-2">
      <?php foreach ($resultadoBuscar as $chave => $linha) : ?>
          <div class="flex flex-col gap-1 publico-artigo-bloco bg-white hover:bg-gray-100 p-4 shadow rounded-lg">
            <?php if (isset($linha['Artigo']['id']) and $linha['Artigo']['id'] > 0) { ?>
                <div class="pb-0 flex gap-2 items-center font-light text-sm publico-migalhas">
                  <?php if ($linha['Categoria']['icone'] and $this->iconeExiste($linha['Categoria']['icone'])) { ?>
                    <div class="w-6 pers-publico-icones template-cor-<?php echo $corPrimaria; ?>">
                      <?php echo $this->renderIcone($linha['Categoria']['icone']); ?>
                    </div>
                  <?php } else { ?>
                    <span class="pers-publico-tag-cate template-cor-<?php echo $corPrimaria; ?>">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z"/>
                        <path d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z"/>
                      </svg>
                    </span>
                  <?php } ?>
                  <?php if (empty($linha['Categoria']['nome']) or empty($linha['Artigo']['categoria_id'])) { ?>
                    <a href="" class="italic hover:underline">Sem categoria</a>
                  <?php } ?>
                  <?php if ($linha['Categoria']['nome'] and $linha['Artigo']['categoria_id']) { ?>
                    <a href="<?php echo baseUrl('/' . $subdominio . '/categoria/' . $linha['Artigo']['categoria_id']); ?>" class="italic hover:underline"><?php echo $linha['Categoria']['nome'] ?></a>
                  <?php } ?>
                </div>
              <h2 class="text-xl flex gap-2 items-center">
                <a href="<?php echo baseUrl('/' . $subdominio . '/artigo/' . $linha['Artigo']['id']); ?>" class="hover:underline"><?php echo $linha['Artigo']['titulo'] ?></a>

                <?php if ($linha['Artigo']['ativo'] == INATIVO) { ?>
                  <div class="text-red-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                      <circle cx="8" cy="8" r="8"/>
                    </svg>
                  </div>
                <?php } ?>
              </h2>
            <?php } ?>
          </div>
      <?php endforeach; ?>

      <?php require_once 'paginacao.php' ?>
    </div>
  <?php } ?>
</div>