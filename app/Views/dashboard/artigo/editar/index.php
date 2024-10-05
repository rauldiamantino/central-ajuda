<div class="relative w-full min-h-full flex flex-col bg-white p-4 container-fundo">
  <h2 class="text-2xl font-semibold mb-4">
    Editar <a href="/artigo/<?php echo $artigo['Artigo.id'] ?>" target="_blank" class="text-sm text-gray-400 font-light italic hover:underline">(Artigo #<?php echo $artigo['Artigo.id']; ?>)</a>
  </h2>
  <?php require_once 'datas.php' ?>
  <div class="w-full min-w-max lg:w-3/6 flex flex-col md:items-center gap-4">
    <div class="w-full flex flex-col gap-10">
      <?php require_once 'formulario.php' ?>
      <?php require_once 'conteudo/menu-adicionar.php' ?>
      <?php require_once 'conteudo/modais/organizar.php' ?>
    </div>
    <?php require_once 'conteudo/blocos.php' ?>
  </div>
</div>
<?php require_once 'conteudo/modais/remover.php' ?>