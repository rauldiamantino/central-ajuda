<details class="bg-gray-100 p-4 rounded-lg" open>
  <summary class="w-max cursor-pointer font-semibold text-gray-700 py-3 text-lg flex items-center gap-2">
    <span>Armazenamento</span>
    <svg class="icon w-5 h-5 transition-transform transform rotate-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
    </svg>
  </summary>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-4 lg:gap-6 inicio-rapido-visao-geral">
    <div class="w-full sm:col-span-2 lg:col-span-1 border border-gray-200 flex gap-4 items-center p-6 bg-white rounded-xl shadow armazenamento-geral">
      <span class="flex items-center p-3 text-blue-800 bg-blue-100/75 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
        </svg>
      </span>
      <div class="w-full flex flex-col justify-center items-start">
        <span class="font-semibold text-gray-800">Espaço utilizado</span>
        <div class="w-full h-full flex flex-col justify-start">
          <div class="h-full flex mb-2 w-full">
            <div class="w-full bg-gray-200 rounded-full">
              <div class="max-w-full p-1 rounded-md barra-progresso transition-all duration-300"></div>
            </div>
          </div>
          <p class="h-full block text-xs text-gray-600 espaco-utilizado opacity-50 transition-all duration-300">Calculando...</p>
        </div>
      </div>
    </div>
  </div>
</details>