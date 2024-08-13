<div class="mb-10 text-xs font-light">
  <div>Criado por <span class="font-semibold"><?php echo $artigo['Usuario.nome']; ?></span> em <?php echo traduzirDataPtBr($artigo['Artigo.criado']); ?></div>
  <div>Última atualização: <?php echo traduzirDataPtBr($artigo['Artigo.modificado']); ?></div>
</div>