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
<body class="w-full h-full">

  <?php require_once 'template/topo.php' ?>

  <div class="flex w-full h-screen">
    <asside>
      <?php require_once 'template/menu_lateral.php' ?>
    </asside>
    <div class="ml-64 w-full h-full bg-slate-50 p-4 flex gap-4 flex-col">
      <main>
        <?php require_once $visao ?>
      </main>
    </div>
  </div>
</body>
</html>