<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/tailwindcss/output.css" rel="stylesheet">
  <link rel="icon" sizes="16x16" href="<?php echo $this->renderImagem($favicon); ?>">
  <link rel="stylesheet" href="/css/publico/index.css">
  <link rel="stylesheet" href="/css/publico/pers.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

  <title><?php echo htmlspecialchars($metaTitulo); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($metaDescricao); ?>">
  <meta property="og:title" content="<?php echo htmlspecialchars($metaTitulo); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($metaDescricao); ?>">
  <meta property="twitter:title" content="<?php echo htmlspecialchars($metaTitulo); ?>">
  <meta property="twitter:description" content="<?php echo htmlspecialchars($metaDescricao); ?>">
  <meta property="og:url" content="https://360help.com.br/<?php echo $subdominio; ?>">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?php echo $metaTitulo; ?>">

  <?php if ($empresaId == 1) { ?>
    <meta name="robots" content="noindex, nofollow">
  <?php } else { ?>
    <meta name="robots" content="index, follow">
  <?php } ?>

  <link rel="canonical" href="<?php echo $urlCanonica; ?>">
  <link rel="preload" href="<?php echo $this->renderImagem($logo); ?>" as="image" type="image/png">
</head>