<?php if (count($filtroAtual) >= 1) { ?>
  <div class="flex flex-col md:flex-row gap-2 md:items-center text-blue-600 text-xs artigos-filtros-ativos">
    <div class="mt-6 flex gap-2 items-center justify-start">
      <div class="text-black">
        Filtrando por:
      </div>
      <div class="flex gap-2 flex-wrap">
        <?php foreach ($filtroAtual as $chave => $linha): ?>
          <?php if ($chave == 'codigo') { ?>
            <div class="w-max border-dashed border border-blue-600 px-2 py-1 whitespace-nowrap cursor-pointer" onclick="filtrarVisualizacoes()">
              Código: <span class="font-semibold"><?php echo $linha ?></span>
            </div>
          <?php } ?>
          <?php if ($chave == 'categoria_id') { ?>
            <div class="w-max border-dashed border border-blue-600 px-2 py-1 whitespace-nowrap cursor-pointer" onclick="filtrarVisualizacoes()">
              <?php $categoriaNome = $filtroAtual['categoria_nome'] ?? ''; ?>
              Categoria: <span class="font-semibold"><?php echo $categoriaNome ? $categoriaNome : '*** Sem Nome ***'; ?></span>
            </div>
          <?php } ?>
          <?php if ($chave == 'data_inicio') { ?>
            <div class="w-max border-dashed border border-blue-600 px-2 py-1 whitespace-nowrap cursor-pointer" onclick="filtrarVisualizacoes()">
              Data início: <span class="font-semibold"><?php echo $linha; ?></span>
            </div>
          <?php } ?>
          <?php if ($chave == 'data_fim') { ?>
            <div class="w-max border-dashed border border-blue-600 px-2 py-1 whitespace-nowrap cursor-pointer" onclick="filtrarVisualizacoes()">
              Data fim: <span class="font-semibold"><?php echo $linha; ?></span>
            </div>
          <?php } ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php } ?>