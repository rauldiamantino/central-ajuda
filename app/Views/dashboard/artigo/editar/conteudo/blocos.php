<div class="mb-4 border border-slate-200 p-4 w-full select-none flex flex-col gap-2 bg-gray-100 rounded-md shadow">
  <?php if (count($conteudos) == 0) { ?>
    <span class="text-xs w-full flex justify-center">Ops! Nenhum conteúdo adicionado</span>
  <?php } ?>
  <?php foreach ($conteudos as $linha): ?>
    <div class="flex gap-1 w-full" data-conteudo-ordem="<?php echo $linha['Conteudo.ordem'] ?>" data-conteudo-id="<?php echo $linha['Conteudo.id'] ?>">
      <div class="border border-slate-200 p-4 w-full flex items-center justify-between gap-4 hover:bg-slate-50 rounded-lg shadow bg-white">
        <div class="w-max">
          <?php if ($linha['Conteudo.tipo'] == 1) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" viewBox="0 0 16 16">
              <path d="M1.5 2.5A1.5 1.5 0 0 1 3 1h10a1.5 1.5 0 0 1 1.5 1.5v3.563a2 2 0 0 1 0 3.874V13.5A1.5 1.5 0 0 1 13 15H3a1.5 1.5 0 0 1-1.5-1.5V9.937a2 2 0 0 1 0-3.874zm1 3.563a2 2 0 0 1 0 3.874V13.5a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V9.937a2 2 0 0 1 0-3.874V2.5A.5.5 0 0 0 13 2H3a.5.5 0 0 0-.5.5zM2 7a1 1 0 1 0 0 2 1 1 0 0 0 0-2m12 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
              <path d="M11.434 4H4.566L4.5 5.994h.386c.21-1.252.612-1.446 2.173-1.495l.343-.011v6.343c0 .537-.116.665-1.049.748V12h3.294v-.421c-.938-.083-1.054-.21-1.054-.748V4.488l.348.01c1.56.05 1.963.244 2.173 1.496h.386z" />
            </svg>
          <?php } ?>
          <?php if ($linha['Conteudo.tipo'] == 2) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" viewBox="0 0 16 16">
              <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
              <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z" />
            </svg>
          <?php } ?>
          <?php if ($linha['Conteudo.tipo'] == 3) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" viewBox="0 0 16 16">
              <path d="M6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814z" />
              <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
            </svg>
          <?php } ?>
        </div>
        <div class="w-full group">
          <a href="/dashboard/<?php echo $this->usuarioLogado['empresaId'] ?>/conteudo/editar/<?php echo $linha['Conteudo.id'] ?>"class="text-start group-hover:underline js-dashboard-conteudo-editar">
            <?php echo $linha['Conteudo.titulo'] ? $linha['Conteudo.titulo'] : '** Sem título **' ?>
          </a>
        </div>
      </div>
      <button type="button" class="w-max h-max text-red-800 hover:text-red-600 text-xs rounded-lg js-dashboard-conteudo-remover" data-conteudo-id="<?php echo $linha['Conteudo.id'] ?>" data-conteudo-url="<?php echo $linha['Conteudo.url'] ?>" data-conteudo-tipo="<?php echo $linha['Conteudo.tipo'] ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" viewBox="0 0 16 16" stroke-width="0.2" stroke="currentColor">
          <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
        </svg>
      </button>
    </div>
  <?php endforeach; ?>
</div>