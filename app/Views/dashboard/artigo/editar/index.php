<div class="relative w-full min-h-full flex flex-col p-4">
  <h2 class="text-2xl font-semibold mb-4">
    Editar <a href="/<?php echo $this->usuarioLogado['subdominio']; ?>/artigo/<?php echo $artigo['Artigo']['id'] ?>" target="_blank" class="text-sm text-gray-400 font-light italic hover:underline">(Artigo #<?php echo $artigo['Artigo']['id']; ?>)</a>
  </h2>
  <?php require_once 'datas.php' ?>
  <div class="w-full flex flex-col lg:flex-row lg:justify-between gap-5">
    <div class="w-full lg:w-7/12 flex flex-col md:items-center gap-4">
      <div class="w-full flex flex-col gap-10">
        <?php require_once 'formulario.php' ?>
        <?php require_once 'conteudo/menu-adicionar.php' ?>
        <?php require_once 'conteudo/modais/organizar.php' ?>
      </div>
      <?php require_once 'conteudo/blocos.php' ?>
    </div>
    <div class="relative pb-10 w-full border border-slate-300 bg-white shadow rounded-md">

      <?php if((int) $artigo['Artigo']['ativo'] == INATIVO) { ?>
        <div class="md:absolute w-full p-4 flex justify-end">
          <div class="w-full md:w-max py-1 px-4 bg-red-900 text-center text-white text-xs font-light rounded">
            Não publicado
          </div>
        </div>
      <?php } ?>

      <?php require_once 'conteudo/pre-visualizacao.php' ?>
    </div>
  </div>

</div>
<?php require_once 'conteudo/modais/remover.php' ?>