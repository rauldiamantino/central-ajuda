<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="/img/favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css" />

<!-- If you are using premium features: -->
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5-premium-features/43.0.0/ckeditor5-premium-features.css" />

  <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
  <title><?php echo $titulo ?></title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap');

    body { margin: 0 !important; font-family: 'Inter', sans-serif; }
    .ck-content { min-height: 300px; line-height: 1.6; word-break: break-word; }
    .ck-content h1 { font-size: 2rem; font-weight: 700; margin-top: 1rem; margin-bottom: 0.5rem; }
    .ck-content h2 { font-size: 1.5rem; font-weight: 600; margin-top: 0.75rem; margin-bottom: 0.25rem; }
    .ck-content h3 { font-size: 1.25rem; font-weight: 500; margin-top: 0.5rem; margin-bottom: 0.25rem; }
    .ck-content ul, .ck-content ol { padding-left: 1.5rem; margin-bottom: 1rem; } 
    .ck-content ul li, .ck-content ol li { margin-bottom: 0.5rem; line-height: 1.6; }
    .ck-content a { color: #1E40AF; cursor: pointer; text-decoration: underline; }
    .editor-container_classic-editor { width: 100%; }
  </style>
</head>

<body class="font-normal h-screen max-w-screen" data-editor="ClassicEditor">
  <?php if (isset($pagLogin)) { ?>
    <main>
      <div class="w-full h-screen flex justify-center items-center"> 
        <div class="relative w-max min-w-96 h-max bg-white">
          <?php require_once $visao ?>

          <div class="w-full flex justify-center absolute inset-x-0 -bottom-10">
            <div class="w-max">
              <?php // Notificação Sucesso ?>
              <?php if (isset($_SESSION['ok'])) { ?>
                <div class="js-dashboard-notificacao-sucesso js-dashboard-notificacao-sucesso-btn-fechar"">
                  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Ok!</strong>
                    <span class="block sm:inline"><?php echo $_SESSION['ok']; ?></span>
                  </div>
                </div>
              <?php } ?>
              <?php // Notificação Erro ?>
              <?php if (isset($_SESSION['erro'])) { ?>
                <div class="js-dashboard-notificacao-erro js-dashboard-notificacao-erro-btn-fechar">
                  <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Erro!</strong>
                    <?php if (is_array($_SESSION['erro'])) { ?>
                      <?php foreach($_SESSION['erro'] as $linha): ?>
                        <div class="flex flex-col gap-1">
                          <span class="block sm:inline"><?php echo $linha; ?></span>
                        </div>
                      <?php endforeach; ?>
                    <?php } ?>
                    <?php if (is_string($_SESSION['erro'])) { ?>
                      <span class="block sm:inline"><?php echo $_SESSION['erro']; ?></span>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
              <?php // Limpa notificações ?>
              <?php $_SESSION['ok'] = null; ?>
              <?php $_SESSION['erro'] = null; ?>
            </div>
          </div>
        </div>
      </div>
    </main>
  <?php } ?>

  <?php if (isset($pagCadastro)) { ?>
    <main>
      <div class="w-full h-screen flex justify-center items-center"> 
        <div class="relative w-max min-w-96 h-max bg-white">
          <?php require_once $visao ?>

          <div class="w-full flex justify-center absolute inset-x-0 -bottom-10">
            <div class="w-max">
              <?php // Notificação Erro ?>
              <?php if (isset($_SESSION['erro'])) { ?>
                <div class="js-dashboard-notificacao-erro js-dashboard-notificacao-erro-btn-fechar">
                  <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Erro!</strong>
                    <?php if (is_array($_SESSION['erro'])) { ?>
                      <?php foreach($_SESSION['erro'] as $linha): ?>
                        <div class="flex flex-col gap-1">
                          <span class="block sm:inline"><?php echo $linha; ?></span>
                        </div>
                      <?php endforeach; ?>
                    <?php } ?>
                    <?php if (is_string($_SESSION['erro'])) { ?>
                      <span class="block sm:inline"><?php echo $_SESSION['erro']; ?></span>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
              <?php // Limpa notificações ?>
              <?php $_SESSION['erro'] = null; ?>
            </div>
          </div>
        </div>
      </div>
    </main>
  <?php } ?>

  <?php if (! isset($pagLogin) and ! isset($pagCadastro)) { ?>
    <?php require_once 'template/topo.php' ?>
    <div class="flex">
      <?php require_once 'template/menu_lateral.php' ?>
      <main class="ml-64 pt-16 flex w-screen h-screen flex-col">
        <?php // Notificação Sucesso ?>
        <?php if (isset($_SESSION['ok'])) { ?>
          <div class="p-4 js-dashboard-notificacao-sucesso" onload="fecharNotificacao()">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
              <strong class="font-bold">Ok!</strong>
              <span class="block sm:inline"><?php echo $_SESSION['ok']; ?></span>
              <span class="absolute top-0 bottom-0 right-0 px-4 py-3 js-dashboard-notificacao-sucesso-btn-fechar">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <title>Fechar</title>
                  <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
              </span>
            </div>
          </div>
        <?php } ?>
        <?php // Notificação Erro ?>
        <?php if (isset($_SESSION['erro'])) { ?>
          <div class="p-4 js-dashboard-notificacao-erro">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
              <strong class="font-bold">Erro!</strong>
              <?php if (is_array($_SESSION['erro'])) { ?>
                <?php foreach($_SESSION['erro'] as $linha): ?>
                  <div class="flex flex-col gap-1">
                    <span class="block sm:inline"><?php echo $linha; ?></span>
                  </div>
                <?php endforeach; ?>
              <?php } ?>
              <?php if (is_string($_SESSION['erro'])) { ?>
                <span class="block sm:inline"><?php echo $_SESSION['erro']; ?></span>
              <?php } ?>
              <span class="absolute top-0 bottom-0 right-0 px-4 py-3 js-dashboard-notificacao-erro-btn-fechar">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <title>Fechar</title>
                  <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
              </span>
            </div>
          </div>
        <?php } ?>
        <?php // Notificação Neutra ?>
        <?php if (isset($_SESSION['neutra'])) { ?>
          <div class="p-4 js-dashboard-notificacao-neutra">
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
              <?php if (is_array($_SESSION['neutra'])) { ?>
                <?php foreach($_SESSION['neutra'] as $linha): ?>
                  <span class="block sm:inline"><?php echo $linha; ?></span>
                <?php endforeach; ?>
              <?php } ?>
              <?php if (is_string($_SESSION['neutra'])) { ?>
                <span class="block sm:inline"><?php echo $_SESSION['neutra']; ?></span>
              <?php } ?>
              <span class="absolute top-0 bottom-0 right-0 px-4 py-3 js-dashboard-notificacao-neutra-btn-fechar">
                <svg class="fill-current h-6 w-6 text-blue-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <title>Fechar</title>
                  <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
              </span>
            </div>
          </div>
        <?php } ?>
        <?php // Limpa notificações ?>
        <?php $_SESSION['ok'] = null; ?>
        <?php $_SESSION['erro'] = null; ?>
        <?php $_SESSION['neutra'] = null; ?>

        <div class="w-full max-w-screen h-full h-max-screen flex gap-6">
          <?php require_once $visao ?>
        </div>
      </main>
    </div>
  <?php } ?>

  <!----------------- bibliotecas ----------------->
  <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/addons/cleave-phone.br.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
  <script type="importmap">{ "imports": { "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js", "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.0.0/" }}</script>
  <script src="/js/dashboard/ckeditor.js" type="module"></script>

  <!------------------- scripts ------------------->
  <script src="/js/dashboard/index.js"></script>
  <script src="/js/dashboard/empresas/index.js"></script>
  <script src="/js/dashboard/usuarios/index.js"></script>
  <script src="/js/dashboard/usuarios/editar.js"></script>
  <script src="/js/dashboard/categorias/index.js"></script>
  <script src="/js/dashboard/categorias/organizar.js"></script>
  <script src="/js/dashboard/artigos/index.js"></script>
  <script src="/js/dashboard/artigos/filtrar.js"></script>
  <script src="/js/dashboard/artigos/organizar.js"></script>
  <script src="/js/dashboard/artigos/conteudos/remover.js"></script>
  <script src="/js/dashboard/artigos/conteudos/organizar.js"></script>
  <script src="/js/dashboard/artigos/conteudos/adicionar.js"></script>
  <script src="/js/dashboard/artigos/conteudos/editar.js" type="module"></script>
</body>

</html>