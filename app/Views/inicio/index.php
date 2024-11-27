<!DOCTYPE html>
<html lang="pt-br">

<?php require_once 'template/cabecalho.php' ?>

<body class="px-10 min-h-screen max-w-screen flex flex-col font-normal <?php echo isset($inicio) ? 'bg-gray-100' : 'bg-gray-100' ?>" data-base-url="<?php echo RAIZ; ?>">

  <?php require_once 'template/topo.php' ?>

  <main class="mx-auto w-full max-w-[1200px] py-16 h-full">

    <div class="pb-10 w-full flex flex-col items-center justify-center text-center gap-4">
      <h1 class="text-4xl font-semibold">Base de conhecimento sem complicações,<br><strong class="text-blue-800 font-bold">simples</strong> e <strong class="text-blue-800 font-bold">eficiente</strong></h1>
      <div class="text-xl">
        <strong class="font-semibold">Organize</strong>, gerencie e compartilhe informações com facilidade,<br> oferecendo soluções rápidas e claras aos seus clientes.
      </div>

      <button type="button" class="mt-10 w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-800 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
    </div>
    <div class="w-fullbg-gray-100">
      <img src="./img/inicio/inicio-img-1.png" alt="Imagem tela de início" class="w-full">
    </div>
  </main>

  <?php require_once 'template/rodape.php' ?>
  <?php require_once 'scripts.php' ?>
</body>
</html>