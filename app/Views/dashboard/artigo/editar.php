<div class="relative w-full min-h-full flex flex-col bg-white p-4">
  <h2 class="text-2xl font-semibold mb-4">
    Editar artigo
    <span class="text-gray-400 font-light italic">
      #<?php echo $artigo['Artigo.id']; ?>
    </span>
  </h2>
  <div class="mb-10 text-xs font-light">
    <div>Criado por <span class="font-semibold"><?php echo $artigo['Usuario.nome']; ?></span> em <?php echo traduzirDataPtBr($artigo['Artigo.criado']); ?></div>
    <div>Última atualização: <?php echo traduzirDataPtBr($artigo['Artigo.modificado']); ?></div>
  </div>

  <div class="w-full flex gap-4">
    <?php // Artigo e Inserção de Conteúdos ?>
    <div class="w-full flex flex-col gap-10">
      <form method="POST" action="/artigo/<?php echo $artigo['Artigo.id'] ?>" class="border border-slate-200 w-full min-w-96 flex flex-col gap-4 p-4 rounded-lg shadow">
        <input type="hidden" name="_method" value="PUT">
        <div class="w-full flex gap-4">
          <div>
            <label class="flex flex-col items-start gap-1 cursor-pointer">
              <span class="block text-sm font-medium text-gray-700">Status</span>
              <input type="hidden" name="ativo" value="0">
              <input type="checkbox" value="1" class="sr-only peer" <?php echo $artigo['Artigo.ativo'] ? 'checked' : '' ?> name="ativo">
              <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-800"></div>
            </label>
          </div>
          <div class="w-full">
            <label for="artigo-editar-titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" id="artigo-editar-titulo" name="titulo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?php echo $artigo['Artigo.titulo']; ?>" required>
          </div>
        </div>
        <div class="mb-4">
          <label for="artigo-editar-categoria" class="block text-sm font-medium text-gray-700">Categoria</label>
          <select id="artigo-editar-categoria" name="categoria_id" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required>
            <?php foreach ($categorias as $chave => $linha) : ?>
              <option value="<?php echo $linha['Categoria.id']; ?>" <?php echo $linha['Categoria.id'] == $artigo['Artigo.categoria_id'] ? 'selected' : ''; ?>>
                <?php echo $linha['Categoria.nome']; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="flex gap-4">
          <a href="/dashboard/artigos" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-xs text-gray-700 rounded-lg">Cancelar</a>
          <button type="submit" class="flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg">Gravar</button>
        </div>
      </form>
      <form action="/conteudo" method="post" class="flex flex-col gap-4 w-full min-w-96 form-conteudo">
        <input type="hidden" name="artigo.id" value="<?php echo $artigo['Artigo.id'] ?>">
        <div class="flex gap-2 conteudo-botoes-adicionar">
          <button type="button" class="flex gap-2 items-center justify-center py-2 px-4 border border-gray-800 hover:border-gray-500 text-gray-800 text-xs rounded-lg conteudo-btn-texto-adicionar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-textarea-t" viewBox="0 0 16 16">
              <path d="M1.5 2.5A1.5 1.5 0 0 1 3 1h10a1.5 1.5 0 0 1 1.5 1.5v3.563a2 2 0 0 1 0 3.874V13.5A1.5 1.5 0 0 1 13 15H3a1.5 1.5 0 0 1-1.5-1.5V9.937a2 2 0 0 1 0-3.874zm1 3.563a2 2 0 0 1 0 3.874V13.5a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V9.937a2 2 0 0 1 0-3.874V2.5A.5.5 0 0 0 13 2H3a.5.5 0 0 0-.5.5zM2 7a1 1 0 1 0 0 2 1 1 0 0 0 0-2m12 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
              <path d="M11.434 4H4.566L4.5 5.994h.386c.21-1.252.612-1.446 2.173-1.495l.343-.011v6.343c0 .537-.116.665-1.049.748V12h3.294v-.421c-.938-.083-1.054-.21-1.054-.748V4.488l.348.01c1.56.05 1.963.244 2.173 1.496h.386z" />
            </svg>
            Texto
          </button>
          <button type="button" class="flex gap-2 items-center justify-center py-2 px-4 border border-yellow-800 hover:border-yellow-600 text-yellow-800 text-xs rounded-lg conteudo-btn-imagem-adicionar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
              <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
              <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z" />
            </svg>
            Imagem
          </button>
          <button type="button" class="flex gap-2 items-center justify-center py-2 px-4 border border-red-800 hover:border-red-600 text-red-800 text-xs rounded-lg conteudo-btn-video-adicionar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-btn" viewBox="0 0 16 16">
              <path d="M6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814z" />
              <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
            </svg>
            Vídeo
          </button>
        </div>
        <div class="flex flex-col items-end gap-2 hidden conteudo-texto-adicionar hidden">
          <div class="w-full">
            <label for="conteudo-texto-titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" id="conteudo-texto-titulo" name="titulo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
          </div>
          <textarea name="conteudo" id="conteudo-conteudo" class="border border-gray-300 w-full p-2 h-56 rounded-lg"></textarea>
          <button type="submit" class="w-max flex gap-2 items-center justify-center py-2 px-4 bg-green-800 hover:bg-green-600 text-white text-xs rounded-lg">Adicionar</button>
        </div>
        <div class="flex flex-col w-full items-end gap-2 hidden conteudo-imagem-adicionar">
          <div class="w-full">
            <label for="conteudo-imagem-titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" id="conteudo-imagem-titulo" name="titulo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
          </div>
          <input type="file" accept="image/*" name="url" id="conteudo-conteudo" class="hidden conteudo-imagem-escolher">
          <button type="button" for="conteudo-conteudo" class="w-full flex items-center justify-center cursor-pointer border border-gray-300 bg-gray-50 p-4 rounded-lg hover:bg-gray-100 conteudo-btn-imagem-escolher">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-image text-gray-500" viewBox="0 0 16 16">
              <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
              <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z" />
            </svg>
            <span class="ml-2 text-gray-700 conteudo-txt-imagem-escolher">Escolher Imagem</span>
          </button>
          <button type="submit" class="w-max flex gap-2 items-center justify-center py-2 px-4 bg-green-800 hover:bg-green-600 text-white text-sm text-xs rounded-lg">Adicionar</button>
        </div>
        <div class="flex flex-col items-end gap-2 hidden conteudo-video-adicionar">
          <div class="w-full">
            <label for="conteudo-video-titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" id="conteudo-video-titulo" name="titulo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
          </div>
          <input type="text" name="url" id="conteudo-conteudo" class="border border-gray-300 w-full p-2 rounded-lg text-sm" placeholder="https://www.youtube.com/watch?v=00000000000">
          <button type="submit" class="w-max flex gap-2 items-center justify-center py-2 px-4 bg-green-800 hover:bg-green-600 text-white text-xs rounded-lg">Adicionar</button>
        </div>
      </form>
    </div>

    <?php // Conteúdos ?>
    <div class="w-full select-none flex flex-col gap-4 conteudo-blocos">
      <?php foreach ($conteudos as $linha): ?>
        <div class="flex gap-1 w-full handle conteudo-bloco" data-conteudo-ordem="<?php echo $linha['Conteudo.ordem'] ?>" data-conteudo-id="<?php echo $linha['Conteudo.id'] ?>">
          <div class="border border-slate-200 p-4 w-full flex items-center justify-between gap-4 hover:bg-slate-50 rounded-lg shadow">
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
            <div class="w-full group"><button type="button" class="group-hover:underline js-dashboard-conteudo-editar" data-conteudo-id="<?php echo $linha['Conteudo.id'] ?>" data-conteudo-titulo="<?php echo $linha['Conteudo.titulo'] ?>" data-conteudo-conteudo="<?php echo $linha['Conteudo.conteudo'] ?>" data-conteudo-url="<?php echo $linha['Conteudo.url'] ?>" data-conteudo-tipo="<?php echo $linha['Conteudo.tipo'] ?>"><?php echo $linha['Conteudo.titulo'] ?></button></div>
          </div>
          <button type="button" class="w-max h-max text-red-800 hover:text-red-600 text-xs rounded-lg js-dashboard-conteudo-remover" data-conteudo-id="<?php echo $linha['Conteudo.id'] ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" viewBox="0 0 16 16" stroke-width="0.2" stroke="currentColor">
              <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
            </svg>
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php // Editar conteúdo ?>
<dialog class="p-4 w-full sm:w-[600px] rounded-lg shadow modal-conteudo-texto-editar">
  <form method="POST" class="flex flex-col items-end gap-2" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="artigo_id" value="<?php echo $artigo['Artigo.id'] ?>">
    <input type="hidden" name="tipo" value="1">
    
    <div class="w-full">
      <label for="conteudo-editar-texto-titulo" class="block text-sm font-medium text-gray-700">Título</label>
      <input type="text" id="conteudo-editar-texto-titulo" name="titulo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="">
    </div>
    <textarea name="conteudo" id="conteudo-editar-texto-conteudo" class="border border-gray-300 w-full p-2 h-56 rounded-lg"></textarea>
    <div class="flex gap-4">
      <button type="button" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-gray-700 text-xs rounded-lg modal-texto-editar-btn-cancelar w-full">Cancelar</button>
      <button type="submit" class="w-max flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg modal-conteudo-texto-btn-enviar">Editar</button>
    </div>
  </form>
</dialog>

<dialog class="p-4 w-full sm:w-[600px] rounded-lg shadow modal-conteudo-imagem-editar">
  <form method="POST" class="flex flex-col items-end gap-2" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="artigo_id" value="<?php echo $artigo['Artigo.id'] ?>">
    <input type="hidden" name="tipo" value="2">

    <div class="w-full">
      <label for="conteudo-editar-imagem-titulo" class="block text-sm font-medium text-gray-700">Título</label>
      <input type="text" id="conteudo-editar-imagem-titulo" name="titulo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="">
    </div>
    <div class="w-full items-start flex flex-col gap-2">
      <input type="file" accept="image/*" name="url" id="conteudo-editar-imagem" class="hidden conteudo-editar-imagem-escolher">
      <button type="button" for="conteudo-editar-imagem" class="w-full flex items-center justify-center cursor-pointer border border-gray-300 bg-gray-50 p-4 rounded-lg hover:bg-gray-100 conteudo-btn-imagem-editar-escolher">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-image text-gray-500" viewBox="0 0 16 16">
          <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
          <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z" />
        </svg>
        <span class="ml-2 text-gray-700 conteudo-txt-imagem-editar-escolher">Alterar Imagem</span>
      </button>
      <div class="relative flex flex-col gap-2 w-full h-48 opacity-50">
        <img src="" class="object-cover w-full h-full opacity-0 transition-opacity duration-300 ease-in-out">
      </div>
    </div>
    <div class="flex gap-4">
      <button type="button" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-gray-700 text-xs rounded-lg modal-conteudo-imagem-btn-cancelar w-full">Cancelar</button>
      <button type="submit" class="w-max flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg modal-conteudo-imagem-btn-enviar">Editar</button>
    </div>
  </form>
</dialog>

<dialog class="p-4 w-full sm:w-[600px] rounded-lg shadow modal-conteudo-video-editar">
  <form method="POST" class="flex flex-col items-end gap-2" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="artigo_id" value="<?php echo $artigo['Artigo.id'] ?>">
    <input type="hidden" name="tipo" value="3">

    <div class="w-full">
      <label for="conteudo-editar-video-titulo" class="block text-sm font-medium text-gray-700">Título</label>
      <input type="text" id="conteudo-editar-video-titulo" name="titulo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="">
    </div>
    <input type="text" name="url" id="conteudo-editar-video-url" class="border border-gray-300 w-full p-2 rounded-lg text-sm" placeholder="https://www.youtube.com/watch?v=00000000000">
    <div class="flex gap-4">
      <button type="button" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-gray-700 text-xs rounded-lg modal-conteudo-video-btn-cancelar w-full">Cancelar</button>
      <button type="submit" class="w-max flex gap-2 items-center justify-center py-2 px-4 bg-blue-800 hover:bg-blue-600 text-white text-xs rounded-lg modal-conteudo-video-btn-enviar">Editar</button>
    </div>
  </form>
</dialog>

<?php // Modal remover ?>
<dialog class="p-4 sm:w-[440px] rounded-lg shadow modal-conteudo-remover">
  <div class="w-full flex gap-4">
    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
      <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
    </div>
    <div class="text-left">
      <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-conteudo-remover-titulo">Remover conteudo</h3>
      <div class="mt-2">
        <p class="text-sm text-gray-500">
          Tem certeza que deseja remover este conteudo? Todos os dados serão permanentemente apagados. Esta ação não poderá ser revertida.
        </p>
      </div>
    </div>
  </div>
  <div class="mt-4 w-full flex flex-col sm:flex-row gap-3 font-semibold text-xs sm:justify-end justify-center">
    <button type="button" class="border border-slate-400 flex gap-2 items-center justify-center py-2 px-3 hover:bg-slate-50 text-gray-700 rounded-lg modal-conteudo-btn-cancelar w-full">Cancelar</button>
    <button type="button" class="flex gap-2 items-center justify-center py-2 px-3 bg-red-800 hover:bg-red-600 text-white rounded-lg w-full modal-conteudo-btn-remover">Remover</button>
  </div>
</dialog>