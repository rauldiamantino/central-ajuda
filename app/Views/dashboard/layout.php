<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
  <title><?php echo $titulo ?></title>
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="font-normal h-screen max-w-screen">
  <?php require_once 'template/topo.php' ?>
  <div class="flex p-4">
    <?php require_once 'template/menu_lateral.php' ?>
    <main class="ml-64 pt-16 flex w-screen h-screen flex-col">
      <?php // Notificação Sucesso ?>
      <?php if (isset($_SESSION['ok'])) { ?>
        <div class="js-dashboard-notificacao-sucesso">
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
                <span class="block sm:inline"><?php echo $linha; ?></span>
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
      <?php // Limpa notificações ?>
      <?php $_SESSION['ok'] = null; ?>
      <?php $_SESSION['erro'] = null; ?>

      <div class="w-full max-w-screen h-max flex gap-6">
        <?php require_once $visao ?>
      </div>
    </main>
  </div>
  
  <script src="/js/dashboard.js"></script>
  <script src="/js/dashboard-artigos.js"></script>
  <script src="/js/dashboard-artigos-editar.js"></script>
  <script>document.addEventListener('DOMContentLoaded', () => setTimeout(() => window.scrollTo({top: 0, left: 0, behavior: 'smooth'}), 60))</script>
</body>

</html>