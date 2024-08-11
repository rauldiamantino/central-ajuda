<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
  <title><?php echo $titulo ?></title>
  <style>
    body {font-family: 'Inter', sans-serif;}
    .ck-editor__editable {min-height: 200px !important;}
    .ck.ck-editor {width: 100% !important;}

    @import url('https://fonts.googleapis.com/css2?family=Oswald&family=PT+Serif:ital,wght@0,400;0,700;1,400&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap');

    @media print {
      body {
        margin: 0 !important;
      }
    }

    textarea {
      font-family: 'Lato';
      width: fit-content;
      margin-left: auto;
      margin-right: auto;
    }

    .ck-content {
      font-family: 'Lato';
      line-height: 1.6;
      word-break: break-word;
    }

    .editor-container_classic-editor .editor-container__editor {
      min-width: 795px;
      max-width: 795px;
    }

    .ck-content h3.category {
      font-family: 'Oswald';
      font-size: 20px;
      font-weight: bold;
      color: #555;
      letter-spacing: 10px;
      margin: 0;
      padding: 0;
    }

    .ck-content h2.document-title {
      font-family: 'Oswald';
      font-size: 50px;
      font-weight: bold;
      margin: 0;
      padding: 0;
      border: 0;
    }

    .ck-content h3.document-subtitle {
      font-family: 'Oswald';
      font-size: 20px;
      color: #555;
      margin: 0 0 1em;
      font-weight: bold;
      padding: 0;
    }

    .ck-content p.info-box {
      --background-size: 30px;
      --background-color: #e91e63;
      padding: 1.2em 2em;
      border: 1px solid var(--background-color);
      background: linear-gradient(
          135deg,
          var(--background-color) 0%,
          var(--background-color) var(--background-size),
          transparent var(--background-size)
        ),
        linear-gradient(
          135deg,
          transparent calc(100% - var(--background-size)),
          var(--background-color) calc(100% - var(--background-size)),
          var(--background-color)
        );
      border-radius: 10px;
      margin: 1.5em 2em;
      box-shadow: 5px 5px 0 #ffe6ef;
    }

    .ck-content blockquote.side-quote {
      font-family: 'Oswald';
      font-style: normal;
      float: right;
      width: 35%;
      position: relative;
      border: 0;
      overflow: visible;
      z-index: 1;
      margin-left: 1em;
    }

    .ck-content blockquote.side-quote::before {
      content: '“';
      position: absolute;
      top: -37px;
      left: -10px;
      display: block;
      font-size: 200px;
      color: #e7e7e7;
      z-index: -1;
      line-height: 1;
    }

    .ck-content blockquote.side-quote p {
      font-size: 2em;
      line-height: 1;
    }

    .ck-content blockquote.side-quote p:last-child:not(:first-child) {
      font-size: 1.3em;
      text-align: right;
      color: #555;
    }

    .ck-content span.marker {
      background: yellow;
    }

    .ck-content span.spoiler {
      background: #000;
      color: #000;
    }

    .ck-content span.spoiler:hover {
      background: #000;
      color: #fff;
    }

    .ck-content pre.fancy-code {
      border: 0;
      margin-left: 2em;
      margin-right: 2em;
      border-radius: 10px;
    }

    .ck-content pre.fancy-code::before {
      content: '';
      display: block;
      height: 13px;
      background: url(data:image/svg+xml;base64,PHN2ZyBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1NCAxMyI+CiAgPGNpcmNsZSBjeD0iNi41IiBjeT0iNi41IiByPSI2LjUiIGZpbGw9IiNGMzZCNUMiLz4KICA8Y2lyY2xlIGN4PSIyNi41IiBjeT0iNi41IiByPSI2LjUiIGZpbGw9IiNGOUJFNEQiLz4KICA8Y2lyY2xlIGN4PSI0Ny41IiBjeT0iNi41IiByPSI2LjUiIGZpbGw9IiM1NkM0NTMiLz4KPC9zdmc+Cg==);
      margin-bottom: 8px;
      background-repeat: no-repeat;
    }

    .ck-content pre.fancy-code-dark {
      background: #272822;
      color: #fff;
      box-shadow: 5px 5px 0 #0000001f;
    }

    .ck-content pre.fancy-code-bright {
      background: #dddfe0;
      color: #000;
      box-shadow: 5px 5px 0 #b3b3b3;
    }

  </style>
</head>

<body class="font-normal h-screen max-w-screen" data-editor="ClassicEditor">
  <?php require_once 'template/topo.php' ?>
  <div class="flex p-4">
    <?php require_once 'template/menu_lateral.php' ?>
    <main class="ml-64 pt-16 flex w-screen h-screen flex-col">
      <?php // Notificação Sucesso ?>
      <?php if (isset($_SESSION['ok'])) { ?>
        <div class="js-dashboard-notificacao-sucesso" onload="fecharNotificacao()">
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
        <div class="js-dashboard-notificacao-erro">
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
        <div class="js-dashboard-notificacao-neutra">
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

      <div class="w-full max-w-screen h-max flex gap-6">
        <?php require_once $visao ?>
      </div>
    </main>
  </div>
  
  <!------------------- scripts ------------------->
  <script type="importmap">
  {
    "imports": {
      "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js",
      "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.0.0/"
    }
  }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/addons/cleave-phone.br.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
  <script src="/js/dashboard.js"></script>
  <script src="/js/dashboard-artigos.js"></script>
  <script src="/js/dashboard-artigos-editar.js" type="module"></script>
  <script src="/js/dashboard-categorias.js"></script>
  <script src="/js/dashboard-usuarios.js"></script>
  <script src="/js/dashboard-usuarios-editar.js"></script>
  <script src="/js/dashboard-empresa.js"></script>
  <script>document.addEventListener('DOMContentLoaded', () => setTimeout(() => window.scrollTo({top: 0, left: 0, behavior: 'smooth'}), 60))</script>
</body>

</html>