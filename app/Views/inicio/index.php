<!DOCTYPE html>
<html lang="pt-br">

<?php require_once 'template/cabecalho.php' ?>

<body class="w-full min-h-screen max-w-screen flex flex-col font-normal bg-white <?php echo isset($inicio) ? 'bg-gray-100' : 'bg-gray-100' ?>" data-base-url="<?php echo RAIZ; ?>">

  <?php require_once 'template/topo.php' ?>

  <main class="w-full h-full">
    <div class="px-4 xl:px-10 w-full bg-white rounded-b-[90px]">

      <?php // Bloco 1 ?>
      <section class="py-10 mx-auto w-full max-w-[1140px] flex flex-col md:flex-row gap-10 md:gap-2">
        <div class="pb-2 px-2 w-full flex flex-col items-left justify-center text-left gap-4">
          <h1 class="text-3xl font-semibold">Menos chamados, mais agilidade!</h1>
          <div class="text-lg font-light">
            Uma base de conhecimento prática e eficiente. Organize, compartilhe e ofereça soluções rápidas, aliviando a sobrecarga do seu time de suporte.
          </div>
          <button type="button" class="mt-10 w-full sm:w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
        </div>

        <div class="w-full">
          <img src="/img/inicio/inicio-img-1.png" alt="busca-completa" class="border-6 border-black rounded-3xl shadow-xl">
        </div>
      </section>

      <?php // Bloco 2 ?>
      <section class="mt-10 py-10 mx-auto w-full max-w-[1140px] flex flex-col items-center justify-center md:flex-row gap-10 md:gap-2">
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
              <span class="font-light">Explore todos os recursos essenciais para a gestão da sua base de conhecimento sem custos iniciais</span>
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
              <span class="font-light">Com nosso editor profissional, você cria e personaliza conteúdos sem complicação, tornando sua central de ajuda mais completa e funcional</span>
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
              <span class="font-light">Nossa busca FULL TEXT garante que os resultados apareçam rapidamente, seja pelo título, descrição ou conteúdo dos artigos</span>
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
              <span class="font-light">Com a organização eficiente e o acesso fácil, seus clientes encontram soluções rápidas, diminuindo o volume de chamados no suporte</span>
            </div>
          </div>
          <div class="w-full sm:grid grid-cols-[auto,1fr] gap-4 items-start">
            <div class="w-14"></div>
            <button type="button" class="w-full sm:w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
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
        <div class="border-4 border-black relative w-full overflow-hidden rounded-2xl shadow-lg bg-black shadow-xl">
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
        <div class="mt-6 w-full flex items-center justify-center">
          <button type="button" class="w-full sm:w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
        </div>
      </section>

      <?php // Bloco 4 ?>
      <section class="mt-10 py-10 mx-auto w-full max-w-[1140px] flex flex-col items-center justify-center md:flex-row gap-10 md:gap-2">
        <div class="w-full h-full flex flex-col gap-8 items-center">
          <div class="w-full flex flex-col gap-6">
            <h2 class="text-3xl font-semibold">Cadastre seus artigos com facilidade, <strong class="font-semibold text-blue-600">sem dor de cabeça</strong> ou complicações!</h2>
            <ul class="flex flex-col gap-2 font-extralight">
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Acesso rápido para criar novos artigos na tela inicial</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Organize os artigos por categorias</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Escolha se o artigo será público ou privado</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Altere a ordem dos artigos a qualquer momento</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Visualize rapidamente o artigo na central de ajuda</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Filtre artigos por ID, status, título ou categoria na dashboard</span>
              </li>
            </ul>
          </div>

          <div class="w-full flex items-center justify-start">
            <button type="button" class="w-full sm:w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
          </div>
        </div>

        <div class="w-full">
          <img src="/img/inicio/artigo-1.png" alt="promessas" class="border-6 border-black rounded-3xl shadow-xl">
        </div>
      </section>

      <?php // Bloco 5 ?>
      <section class="mt-10 py-10 mx-auto w-full max-w-[1140px] flex flex-col-reverse items-center justify-center md:flex-row gap-10 md:gap-10">
        <div class="w-full">
          <img src="/img/inicio/categoria-1.png" alt="promessas" class="border-6 border-black rounded-3xl shadow-xl">
        </div>
        <div class="w-full h-full flex flex-col gap-8 items-center">
          <div class="w-full flex flex-col gap-6">
            <h2 class="text-3xl font-semibold">Organize seus <strong class="text-blue-600 font-semibold">artigos por temas</strong>, facilitando a localização dos tutoriais pelos seus clientes</h2>
            <ul class="flex flex-col gap-2 font-extralight">
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Crie novas categorias diretamente na tela inicial</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Altere a ordem de exibição das categorias na sua central</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Defina se a categoria será pública ou privada</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Personalize suas categorias com ícones modernos da nossa galeria</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Visualize rapidamente suas categorias na central de ajuda</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Filtre categorias por ID, status ou título na dashboard</span>
              </li>
            </ul>
          </div>

          <div class="w-full flex items-center justify-start">
            <button type="button" class="w-full sm:w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
          </div>
        </div>
      </section>

      <?php // Bloco 6 ?>
      <section class="mt-10 py-10 mx-auto w-full max-w-[1140px] flex flex-col items-center justify-center md:flex-row gap-10 md:gap-2">
        <div class="w-full h-full flex flex-col gap-8 items-center">
          <div class="w-full flex flex-col gap-6">
            <h2 class="text-3xl font-semibold">Sua equipe vai adorar a <strong class="text-blue-600 font-semibold">facilidade</strong> para adicionar novos conteúdos!</h2>
            <ul class="flex flex-col gap-2 font-extralight">
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Adicione blocos de texto, imagens e vídeos facilmente</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Criação e edição simplificadas, com poucos cliques</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Insira links de vídeos do YouTube e Vimeo</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Anexe imagens aos seus artigos de forma prática</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Altere a ordem dos blocos sempre que precisar</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Crie artigos profissionais com nosso editor intuitivo</span>
              </li>
            </ul>
          </div>

          <div class="w-full flex items-center justify-start">
            <button type="button" class="w-full sm:w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
          </div>
        </div>

        <div class="w-full">
          <img src="/img/inicio/conteudo-1.png" alt="promessas" class="border-6 border-black rounded-3xl">
        </div>
      </section>

      <?php // Bloco 7 ?>
      <section class="mt-10 py-10 mx-auto w-full max-w-[1140px] flex flex-col-reverse items-center justify-center md:flex-row gap-10 md:gap-10">
        <div class="w-full">
          <img src="/img/inicio/ajustes-1.png" alt="promessas" class="border-6 border-black rounded-3xl">
        </div>
        <div class="w-full h-full flex flex-col gap-8 items-center">
          <div class="w-full flex flex-col gap-6">
            <h2 class="text-3xl font-semibold"><strong class="text-blue-600 font-semibold">Personalize</strong> sua central de ajuda para refletir a <strong class="text-blue-600 font-semibold">identidade da sua empresa</strong></h2>
            <ul class="flex flex-col gap-2 font-extralight">
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Adicione seu nome e CNPJ no rodapé com facilidade</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Fixe a barra superior para uma navegação mais prática</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Inclua um botão de WhatsApp e um link direto para o seu site</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Exiba o autor, a data de criação e a última atualização dos artigos</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Altere o logo e favicon da sua empresa sempre que quiser</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Escolha entre 4 temas de cores para personalizar ainda mais</span>
              </li>
            </ul>
          </div>

          <div class="w-full flex items-center justify-start">
            <button type="button" class="w-full sm:w-max whitespace-nowrap flex items-center justify-center py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg duration-100 font-semibold" onclick="window.open('/cadastro')">Iniciar teste grátis</button>
          </div>
        </div>
      </section>
      <section class="py-20 mx-auto w-full max-w-[1140px] flex flex-col items-center gap-0">
        <strong class="w-full text-center text-xs text-blue-600 font-semibold sm:text-lg">Aproveite nossos planos de inauguração</strong>
        <div class="text-center">
          <h2 class="text-4xl font-bold text-gray-800">O plano perfeito para o seu negócio</h2>
          <p class="mt-4 text-lg text-gray-600">Transforme sua central de ajuda e impressione seus clientes com profissionalismo e eficiência.</p>
        </div>
        <div class="w-full grid gap-8 mt-8 sm:grid-cols-2">
          <div class="p-6 border border-gray-300 flex flex-col items-center justify-between rounded-xl shadow-sm hover:shadow-md transition duration-300">
            <h3 class="text-2xl font-semibold text-gray-800">Mensal</h3>
            <p class="mt-2 text-gray-500 line-through">R$ 119,00/mês</p>
            <p class="mt-2 mb-6 text-3xl font-bold text-blue-600">R$ 99,00/mês</p>
            <ul class="mt-4 text-gray-600">
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Até 10 usuários</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>1 GB de armazenamento ou 300 artigos</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Artigos públicos e privados</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>4 Templates de cores</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Personalização de Logo e Favicon</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Busca FULL TEXT na Central de Ajuda</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Editor de textos profissional</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Inserção de vídeos do YouTube e Vimeo</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Botão de WhatsApp para contato direto</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Link personalizado para redirecionamento para sua empresa</span>
              </li>
            </ul>
            <div class="mt-6">
              <button type="button" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition" onclick="window.open('/cadastro')">
                Iniciar teste grátis
              </button>
            </div>
          </div>

          <div class="p-6 border border-gray-300 flex flex-col items-center justify-between rounded-xl shadow-sm hover:shadow-md transition duration-300">
            <h3 class="text-2xl font-semibold text-gray-800">Anual</h3>
            <p class="mt-2 text-gray-500 line-through">R$ 99,00/mês</p>
            <p class="mt-2 text-3xl font-bold text-blue-600">R$ 79,00/mês</p>
            <p class="mt-1 text-sm text-gray-500">(Cobrado anualmente: R$ 948/ano)</p>
            <ul class="mt-4 text-gray-600">
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Até 10 usuários</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>1 GB de armazenamento ou 300 artigos</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Artigos públicos e privados</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>4 Templates de cores</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Personalização de Logo e Favicon</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Busca FULL TEXT na Central de Ajuda</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Editor de textos profissional</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Inserção de vídeos do YouTube e Vimeo</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Botão de WhatsApp para contato direto</span>
              </li>
              <li class="font-light grid grid-cols-[auto,1fr] gap-2 items-center">
                <span class="text-blue-600 w-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </span>
                <span>Link personalizado para redirecionamento para sua empresa</span>
              </li>
            </ul>
            <div class="mt-6">
              <button type="button" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition" onclick="window.open('/cadastro')">
                Iniciar teste grátis
              </button>
            </div>
          </div>
        </div>
      </section>


      <section class="py-16 mx-auto w-full max-w-[1140px]">
        <h2 class="text-3xl font-semibold text-center text-gray-800">Perguntas Frequentes</h2>
      <div class="mt-8 space-y-4">
        <details class="bg-gray-100 p-4 rounded-lg">
          <summary class="font-semibold text-gray-700">Como posso mudar meu plano?</summary>
          <p class="mt-2 text-gray-600">Trocar de plano é super fácil! Basta acessar as configurações na sua conta e escolher o que mais atende às suas necessidades.</p>
        </details>

        <details class="bg-gray-100 p-4 rounded-lg">
          <summary class="font-semibold text-gray-700">Quais formas de pagamento vocês aceitam?</summary>
          <p class="mt-2 text-gray-600">Por enquanto, aceitamos cartões de crédito para facilitar sua vida.</p>
        </details>

        <details class="bg-gray-100 p-4 rounded-lg">
          <summary class="font-semibold text-gray-700">Como posso cancelar minha assinatura?</summary>
          <p class="mt-2 text-gray-600">Claro, você pode cancelar sua assinatura a qualquer momento! Basta nos enviar um e-mail para <span class="font-semibold">suporte@360help.com.br</span>, mas esperamos que você não precise fazer isso!</p>
        </details>

        <details class="bg-gray-100 p-4 rounded-lg">
          <summary class="font-semibold text-gray-700">E se eu ultrapassar o limite de dados do meu plano?</summary>
          <p class="mt-2 text-gray-600">Não se preocupe! Você receberá uma notificação e poderá facilmente ajustar seu plano ou pagar pelos dados extras que precisar.</p>
        </details>

        <details class="bg-gray-100 p-4 rounded-lg">
          <summary class="font-semibold text-gray-700">Posso adicionar mais usuários ao meu plano?</summary>
          <p class="mt-2 text-gray-600">Sim, claro! Vá até o menu "Usuários" para gerenciar os acessos de todos que você deseja incluir no seu plano.</p>
        </details>

        <details class="bg-gray-100 p-4 rounded-lg">
          <summary class="font-semibold text-gray-700">Como a plataforma pode beneficiar minha empresa?</summary>
          <p class="mt-2 text-gray-600">Nossa plataforma foi pensada para te ajudar a otimizar a distribuição de conteúdo e oferecer um atendimento ágil e eficiente. Deixe seus clientes mais satisfeitos com uma central de ajuda profissional e organizada!</p>
        </details>
      </div>
    </section>

  </main>

  <?php require_once 'template/rodape.php' ?>
  <?php require_once 'scripts.php' ?>
</body>
</html>