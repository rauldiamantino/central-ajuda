<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title><?php echo $titulo ?></title>
</head>
<body class="w-full h-full">

  <?php require_once('template/topo.php') ?>

  <div class="flex w-full h-screen">
    <asside>
      <?php require_once('template/menu_lateral.php') ?>
    </asside>
    <div class="ml-64 w-full h-full bg-slate-50 p-4 flex gap-4 flex-col">
      <main>
        <?php require_once('template/corpo.php') ?>
      </main>
    </div>
  </div>
</body>
</html>