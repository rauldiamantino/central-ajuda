<div class="w-full h-full bg-white p-5 lg:p-10 rounded-md dashboard-pre-visualizacao">
  <div class="flex flex-col justify-between items-start gap-2 md:pt-5 px-3">
    <div class="flex gap-2 items-center">
      <h1><?php echo $artigo['Artigo']['titulo'] ?></h1>
      <?php if ($artigo['Artigo']['ativo'] == INATIVO) { ?>
        <div class="text-red-800">
          <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
            <circle cx="8" cy="8" r="8"/>
          </svg>
        </div>
      <?php } ?>
    </div>
    <div class="text-xs font-light">
      <?php if ($this->buscarAjuste('artigo_criado') == 1 and $this->buscarAjuste('artigo_autor') == 1) { ?>
        <div>Criado por <span class="font-semibold"> <?php echo $artigo['Usuario']['nome'] ?> </span> em <?php echo traduzirDataPtBr($artigo['Artigo']['criado']); ?></div>
      <?php } ?>

      <?php if ($this->buscarAjuste('artigo_criado') == 1 and $this->buscarAjuste('artigo_autor') == 0) { ?>
        <div>Criado em <?php echo traduzirDataPtBr($artigo['Artigo.criado']); ?></div>
      <?php } ?>

      <?php if ($this->buscarAjuste('artigo_modificado') == 1) { ?>
        <div>Última atualização: <?php echo traduzirDataPtBr($artigo['Artigo']['modificado']); ?></div>
      <?php } ?>
    </div>

    <div class="w-full flex flex-col pt-6 pb-10 border-b border-slate-200">
      <?php if ($conteudos) { ?>
        <?php foreach ($conteudos as $chave => $linha) : ?>
          <div class="relative p-1 w-full h-full border-2 border-transparent group hover:border-black rounded div-pai-conteudo-editar">
            <?php if ($linha['Conteudo']['tipo'] == 1) { ?>
              <div class="flex flex-col gap-2 leading-7" data-conteudo-ordem="<?php echo $linha['Conteudo']['ordem'] ?>" data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>">
                <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == 0) { ?>
                  <h2><?php echo $linha['Conteudo']['titulo'] ?></h2>
                <?php } ?>
                <div>
                  <?php if (empty($linha['Conteudo']['conteudo'])) { ?><br>
                  <?php } else { echo htmlspecialchars_decode($linha['Conteudo']['conteudo']); } ?>
                </div>
              </div>
            <?php } ?>

            <?php if ($linha['Conteudo']['tipo'] == 2) { ?>
              <div data-conteudo-ordem="<?php echo $linha['Conteudo']['ordem'] ?>" data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>">
                <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == 0) { ?>
                  <h2><?php echo $linha['Conteudo']['titulo'] ?></h2>
                <?php } ?>
                <img src="<?php echo $linha['Conteudo']['url'] ?>" class="w-full">
              </div>
            <?php } ?>

            <?php if ($linha['Conteudo']['tipo'] == 3) { ?>
              <div data-conteudo-ordem="<?php echo $linha['Conteudo']['ordem'] ?>" data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>">
                <?php if ($linha['Conteudo']['titulo'] and $linha['Conteudo']['titulo_ocultar'] == 0) { ?>
                  <h2><?php echo $linha['Conteudo']['titulo'] ?></h2>
                <?php } ?>
                <iframe src="<?php echo str_replace('watch?v=', 'embed/', $linha['Conteudo']['url']) ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="width: 100%; height: auto; aspect-ratio: 16/9"></iframe>
              </div>
            <?php } ?>

            <div class="w-max h-full absolute top-0 -right-12 px-3 gap-5 hidden group-hover:flex flex-col justify-center rounded">
              <button type="button" class="w-max h-max text-blue-800 flex items-center hover:text-blue-600 text-xs rounded-lg group botao-abrir-menu-adicionar-imagem" data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>" data-conteudo-url="<?php echo $linha['Conteudo']['url'] ?>" data-conteudo-tipo="<?php echo $linha['Conteudo']['tipo'] ?>" data-conteudo-titulo="<?php echo $linha['Conteudo']['titulo'] ?>" data-ordem-prox="<?php echo $ordem['prox'] ?? 0; ?>" <?php echo $linha['Conteudo']['tipo'] == 2 ? 'onclick="editarImagemModal(event)"' : ''?>>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="pointer-events-none" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/></svg></button>

              <button type="button" class="w-max h-max text-red-800 flex items-center hover:text-red-600 text-xs rounded-lg group js-dashboard-conteudo-remover" data-conteudo-id="<?php echo $linha['Conteudo']['id'] ?>" data-conteudo-url="<?php echo $linha['Conteudo']['url'] ?>" data-conteudo-tipo="<?php echo $linha['Conteudo']['tipo'] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16" class="min-h-full"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" /><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" /></svg></button>
            </div>
          </div>
        <?php endforeach; ?>
      <?php } ?>
    </div>
  </div>
</div>