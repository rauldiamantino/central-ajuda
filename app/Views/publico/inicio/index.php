<div class="mx-auto w-full xl:w-8/12 flex flex-col items-center gap-20 px-6 py-12">

  <div class="w-full flex flex-col items-start gap-6">
    <div class="flex flex-col gap-3">
      <h2 class="font-bold text-4xl">Olá, como podemos te ajudar hoje?</h2>
      <div class="font-light">Explore nossos <span class="font-semibold">guias, tutoriais e artigos</span> para encontrar rapidamente as informações que você precisa.</div>
    </div>
    <div class="w-full flex flex-col justify-center">
      <?php require_once 'formulario-busca.php' ?>
    </div>
  </div>

  <div class="flex flex-col gap-2">
    <h3 class="font-light">Todas as categorias</h2>
    <div class="w-full h-max grid grid-cols-2 xl:grid-cols-3 justify-start gap-2">
      <?php foreach ($categorias as $chave => $linha): ?>
        <a href="/categoria/<?php echo $linha['Categoria.id'] ?>">
          <div class="border border-slate-200 hover:bg-slate-100 p-6 h-full rounded shadow">
            <h3 class="text-lg font-semibold"><?php echo $linha['Categoria.nome'] ?></h2>
            <div class="font-extralight"><?php echo $linha['Categoria.descricao'] ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>