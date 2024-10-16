<div class="mx-auto w-full xl:w-8/12 flex flex-col items-center gap-16 px-6 py-12">

  <div class="w-full flex flex-col items-start gap-6">
    <div class="flex flex-col gap-3">
      <h2 class="font-bold text-4xl">Olá, como podemos te ajudar hoje?</h2>
      <div class="font-light">Explore nossos <span class="font-semibold">guias, tutoriais e artigos</span> para encontrar rapidamente as informações que você precisa.</div>
    </div>
    <div class="w-full flex flex-col justify-center">
      <?php require_once 'formulario-busca.php' ?>
    </div>
  </div>

  <div class="w-full flex flex-col gap-2">
    <h3 class="font-light">Principais tópicos...</h3>
    <div class="w-full h-max grid grid-cols-2 xl:grid-cols-3 justify-start gap-2">
      <?php foreach ($categorias as $chave => $linha): ?>
        <a href="/categoria/<?php echo $linha['Categoria']['id'] ?>" class="hover:scale-105 transition-scale duration-150">
          <div class="border border-slate-200 hover:shadow-xl p-6 h-full min-h-[200px] flex flex-col justify-between rounded shadow">
            <h3 class="text-lg"><?php echo $linha['Categoria']['nome'] ?></h3>
            <div class="font-extralight text-gray-400"><?php echo $linha['Categoria']['descricao'] ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
