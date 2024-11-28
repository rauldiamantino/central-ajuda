<!DOCTYPE html>
<html lang="pt-br">

<?php require_once 'template/cabecalho.php' ?>

<body class="min-h-screen max-w-screen flex flex-col font-normal bg-white <?php echo isset($inicio) ? 'bg-gray-100' : 'bg-gray-100' ?>" data-base-url="<?php echo RAIZ; ?>">

  <?php require_once 'template/topo.php' ?>

  <main class="w-full h-full">

    <div class="px-10 w-full bg-white rounded-b-[90px]">
      <div class="py-10 mx-auto w-full max-w-[1140px] flex gap-2">
        <section class="pb-2 px-10 w-full flex flex-col items-left justify-center text-left gap-4">
          <h1 class="text-3xl font-semibold">Menos chamados, mais agilidade!</h1>
          <div class="text-lg font-light">
            Uma base de conhecimento prática e eficiente. Organize, compartilhe e ofereça soluções rápidas, aliviando a sobrecarga do seu time de suporte.
          </div>
          <button type="button" class="mt-10 w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-800 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
        </section>

        <section>
          <img src="/img/inicio/inicio-img-1.png" alt="Central de Ajuda" class="border-6 border-black rounded-3xl">
        </section>
      </div>
  </main>

  <?php require_once 'template/rodape.php' ?>
  <?php require_once 'scripts.php' ?>
</body>
</html>