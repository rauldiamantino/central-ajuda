<?php $categoriaNome = $artigos[0]['Categoria.nome'] ?? ''; ?>
<div class="w-full flex flex-col px-6 md:px-12 py-14">
  <div class="pb-6 border-b border-slate-200 flex gap-2 font-light text-sm publico-migalhas">
    <a href="/" class="hover:underline">Início</a>
    <span>></span>
    <span class="underline"><?php echo $categoriaNome ?></span>
  </div>

  <div class="flex flex-col justify-between items-start gap-4 pt-10 publico-artigo-topo">
    <h2 class="text-4xl publico-artigo-titulo"><?php echo $categoriaNome ?></h2>
  </div>

  <?php if ($artigos) { ?>
    <div class="py-12 border-b border-slate-200 publico-artigo-blocos">
      <div class="flex flex-col gap-2 leading-8 publico-artigo-bloco">
        <ul class="leading-9">
          <?php foreach ($artigos as $chave => $linha): ?>
            <li class="flex gap-2 items-center">
              <a href="/artigo/<?php echo $linha['Artigo.id'] ?>" class="hover:underline"><?php echo $linha['Artigo.titulo'] ?></a>

              <?php if ($linha['Artigo.ativo'] == INATIVO) { ?>
                <div class="text-red-800">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="8"/>
                  </svg>
                </div>
              <?php } ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  <?php } ?>
</div>