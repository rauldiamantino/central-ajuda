<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" sizes="16x16" href="<?php echo $this->renderImagem($empresaId, 'favicon'); ?>">
  <link rel="stylesheet" href="<?php echo baseUrl('/css/publico/index.css'); ?>">
  <link rel="stylesheet" href="<?php echo baseUrl('/css/publico/pers.css'); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

  <?php // Editorjs ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/editor.js/latest/editor.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/editor.js/latest/editor.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.30.6/dist/editorjs.umd.min.js"></script>

  <title><?php echo $titulo ?></title>
</head>