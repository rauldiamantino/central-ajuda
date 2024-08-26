<?php $categoriaNome = $artigos[0]['Categoria.nome'] ?? ''; ?>
<div class="w-full flex flex-col px-6 md:px-12 py-14">
  <div class="pb-6 border-b border-slate-200 flex gap-2 font-light text-sm publico-migalhas">
    <a href="/p/<?php echo $subdominio ?>" class="hover:underline">Início</a>
    <span>></span>
    <span class="underline"><?php echo $categoriaNome ?></span>
  </div>

  <div class="flex flex-col justify-between items-start gap-4 pt-10 publico-artigo-topo">
    <h2 class="text-4xl publico-artigo-titulo"><?php echo $categoriaNome ?></h2>
  </div>

  <div class="py-12 border-b border-slate-200 publico-artigo-blocos">
    <div class="flex flex-col gap-2 leading-8 publico-artigo-bloco">
      <ul class="leading-9">
        <?php foreach ($artigos as $chave => $linha) : ?>
          <li class="ml-4 list-disc">
            <a href="/p/<?php echo $subdominio ?>/artigo/<?php echo $linha['Artigo.id'] ?>" class="hover:underline"><?php echo $linha['Artigo.titulo'] ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>