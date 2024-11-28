<!DOCTYPE html>
<html lang="pt-br">

<?php require_once 'template/cabecalho.php' ?>

<body class="min-h-screen max-w-screen flex flex-col font-normal bg-white <?php echo isset($inicio) ? 'bg-gray-100' : 'bg-gray-100' ?>" data-base-url="<?php echo RAIZ; ?>">

  <?php require_once 'template/topo.php' ?>

  <main class="w-full h-full">
    <div class="px-10 w-full bg-white rounded-b-[90px]">

      <?php // Bloco 1 ?>
      <section class="py-10 mx-auto w-full max-w-[1140px] flex gap-2">
        <div class="pb-2 px-2 w-full flex flex-col items-left justify-center text-left gap-4">
          <h1 class="text-3xl font-semibold">Menos chamados, mais agilidade!</h1>
          <div class="text-lg font-light">
            Uma base de conhecimento prática e eficiente. Organize, compartilhe e ofereça soluções rápidas, aliviando a sobrecarga do seu time de suporte.
          </div>
          <button type="button" class="mt-10 w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-800 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
        </div>

        <div class="w-full">
          <img src="/img/inicio/inicio-img-1.png" alt="busca-completa" class="border-6 border-black rounded-3xl">
        </div>
      </section>

      <?php // Bloco 2 ?>
      <section class="mt-16 py-10 mx-auto w-full max-w-[1140px] flex items-center gap-2">
        <div class="w-full">
          <img src="/img/inicio/inicio-img-2.png" alt="promessas" class="border-6 border-black rounded-3xl">
        </div>

        <div class="w-full h-full flex flex-col gap-8 items-center">
          <div class="grid grid-cols-[auto,1fr] gap-4 items-start">
            <div class="border-8 border-blue-50 p-1 w-14 h-auto bg-blue-100 text-blue-800/95 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke="currentColor" class="p-1">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </div>
            <div class="w-full flex flex-col gap-2">
              <h2 class="text-xl font-semibold">Teste gratuitamente por 14 Dias</h2>
              <span class="font-light">Explore todos os recursos essenciais para a gestão da sua base de conhecimento sem custos iniciais.</span>
            </div>
          </div>
          <div class="grid grid-cols-[auto,1fr] gap-4 items-start">
            <div class="border-8 border-blue-50 p-1 w-14 h-auto bg-blue-100 text-blue-800/95 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke="currentColor" class="p-1">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </div>
            <div class="w-full flex flex-col gap-2">
              <h2 class="text-xl font-semibold">Crie e organize conteúdos com facilidade</h2>
              <span class="font-light">Com nosso editor profissional, você cria e personaliza conteúdos sem complicação, tornando sua central de ajuda mais completa e funcional.</span>
            </div>
          </div>
          <div class="grid grid-cols-[auto,1fr] gap-4 items-start">
            <div class="border-8 border-blue-50 p-1 w-14 h-auto bg-blue-100 text-blue-800/95 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke="currentColor" class="p-1">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </div>
            <div class="w-full flex flex-col gap-2">
              <h2 class="text-xl font-semibold">Busque e encontre com agilidade</h2>
              <span class="font-light">Nossa busca FULL TEXT garante que os resultados apareçam rapidamente, seja pelo título, descrição ou conteúdo dos artigos.</span>
            </div>
          </div>
          <div class="grid grid-cols-[auto,1fr] gap-4 items-start">
            <div class="border-8 border-blue-50 p-1 w-14 h-auto bg-blue-100 text-blue-800/95 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke="currentColor" class="p-1">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </div>
            <div class="w-full flex flex-col gap-2">
              <h2 class="text-xl font-semibold">Reduza a carga do suporte</h2>
              <span class="font-light">Com a organização eficiente e o acesso fácil, seus clientes encontram soluções rápidas, diminuindo o volume de chamados no suporte.</span>
            </div>
          </div>
          <div class="w-full grid grid-cols-[auto,1fr] gap-4 items-start">
            <div class="w-14"></div>
            <button type="button" class="w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-800 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
          </div>
        </div>
      </section>

      <?php // Bloco 3 ?>
      <section class="py-20 mx-auto w-full max-w-[1140px] flex flex-col items-center justify-center gap-2">
        <div class="pb-2 px-2 w-full flex flex-col items-center justify-center gap-4 text-center">
          <h1 class="text-3xl font-semibold">Gerencie seu conteúdo de forma simples e sem complicação</h1>
          <div class="text-lg font-light">
            Nossa plataforma foi criada para tornar a organização do seu conteúdo rápida e intuitiva. Sem telas confusas, sem recursos desnecessários. Vamos direto ao que importa, para você focar no que realmente faz a diferença!
          </div>
        </div>
        <div class="border-4 border-black relative w-full overflow-hidden rounded-2xl shadow-lg bg-black">
          <div class="flex transition-transform duration-500 ease-in-out" id="carrossel-imagens">
            <img src="/img/inicio/dash-1.png" alt="Imagem 1" class="w-full rounded-xl">
            <img src="/img/inicio/dash-2.png" alt="Imagem 2" class="w-full rounded-xl">
            <img src="/img/inicio/dash-3.png" alt="Imagem 3" class="w-full rounded-xl">
            <img src="/img/inicio/dash-4.png" alt="Imagem 4" class="w-full rounded-xl">
            <img src="/img/inicio/dash-5.png" alt="Imagem 5" class="w-full rounded-xl">
            <img src="/img/inicio/dash-6.png" alt="Imagem 6" class="w-full rounded-xl">
            <img src="/img/inicio/publico-1.png" alt="Imagem 7" class="w-full rounded-xl">
            <img src="/img/inicio/publico-2.png" alt="Imagem 8" class="w-full rounded-xl">
            <img src="/img/inicio/publico-3.png" alt="Imagem 9" class="w-full rounded-xl">
          </div>
          <button class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-gray-800 text-white w-12 h-12 rounded-full shadow-lg opacity-70 hover:opacity-100 transition-opacity" onclick="moveSlide(-1)">&#10094;</button>
          <button class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-gray-800 text-white w-12 h-12 rounded-full shadow-lg opacity-70 hover:opacity-100 transition-opacity" onclick="moveSlide(1)">&#10095;</button>
        </div>

        <div class="mt-2 w-full flex items-center justify-center">
          <button type="button" class="w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-800 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
        </div>
      </section>
  </main>

  <script>
    let indiceAtual = 0;

    function moveSlide(direcao) {
      const imagens = document.querySelector('#carrossel-imagens');
      const totalImagens = document.querySelectorAll('#carrossel-imagens img').length;
      indiceAtual = (indiceAtual + direcao + totalImagens) % totalImagens;
      imagens.style.transform = `translateX(-${indiceAtual * 100}%)`;
    }
  </script>

  <?php require_once 'template/rodape.php' ?>
  <?php require_once 'scripts.php' ?>
</body>
</html>