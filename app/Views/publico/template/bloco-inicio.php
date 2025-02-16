<div class="w-full <?php echo $classesLarguraGeral ?> flex <?php echo $classesBuscaAlinhamento; ?>">
  <div class="w-full <?php echo $classesBuscaTamanho; ?> flex flex-col items-start gap-6">
    <div class="flex flex-col gap-3">
      <h2 class="font-bold text-3xl"><?php echo Helper::ajuste('publico_inicio_titulo'); ?></h2>
      <div class="font-light"><?php echo Helper::ajuste('publico_inicio_subtitulo'); ?></div>
    </div>
    <div class="w-full flex flex-col justify-center">
      <?php require_once '../inicio/formulario-busca.php' ?>
    </div>
  </div>
</div>