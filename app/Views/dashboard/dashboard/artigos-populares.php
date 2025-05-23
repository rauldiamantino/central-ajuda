<?php
if ($this->usuarioLogado['nivel'] == USUARIO_LEITURA) {
  return;
}
?>

<details class="bg-gray-100 py-4 rounded-lg" open>
  <summary class="w-max cursor-pointer font-semibold text-gray-700 py-3 text-lg flex items-center gap-2">
    <span>Artigos mais populares</span>
    <svg class="icon w-5 h-5 transition-transform transform rotate-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
    </svg>
  </summary>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-4 lg:gap-6">
    <?php foreach ($artigosPopulares as $chave => $linha) : ?>
      <?php if (isset($linha['Artigo']['id'])) { ?>
        <div class="border border-gray-200 flex gap-4 items-start p-6 bg-white rounded-xl shadow">
          <?php
          $classeTrofeu = 'text-purple-800 bg-purple-100/75';

          if ($chave == 1) {
            $classeTrofeu = 'text-pink-800 bg-pink-100/75';
          }
          elseif ($chave == 2) {
            $classeTrofeu = 'text-amber-800 bg-amber-100/75';
          }
          ?>
          <span class="mt-2 flex items-center p-3 <?php echo $classeTrofeu; ?> rounded-lg">
            <div class="flex gap-2 items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-trophy" viewBox="0 0 16 16">
                <path d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5q0 .807-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33 33 0 0 1 2.5.5m.099 2.54a2 2 0 0 0 .72 3.935c-.333-1.05-.588-2.346-.72-3.935m10.083 3.935a2 2 0 0 0 .72-3.935c-.133 1.59-.388 2.885-.72 3.935M3.504 1q.01.775.056 1.469c.13 2.028.457 3.546.87 4.667C5.294 9.48 6.484 10 7 10a.5.5 0 0 1 .5.5v2.61a1 1 0 0 1-.757.97l-1.426.356a.5.5 0 0 0-.179.085L4.5 15h7l-.638-.479a.5.5 0 0 0-.18-.085l-1.425-.356a1 1 0 0 1-.757-.97V10.5A.5.5 0 0 1 9 10c.516 0 1.706-.52 2.57-2.864.413-1.12.74-2.64.87-4.667q.045-.694.056-1.469z"/>
              </svg>
            </div>
          </span>
          <div class="w-full h-full flex flex-col gap-2 justify-center items-start">
            <span class="h-full text-lg break-words"><?php echo $linha['Artigo']['titulo']; ?></span>
            <ul class="h-full font-extralight text-xs">
              <li>Código: <?php echo $linha['Artigo']['codigo']; ?></li>
              <li>Categoria: <?php echo $linha['Categoria']['nome']; ?></li>
              <li class="font-normal">Gostou: <?php echo $linha['Feedback']['gostou']; ?> | Não gostou: <?php echo $linha['Feedback']['nao_gostou']; ?></li>
            </ul>
          </div>
        </div>
      <?php } ?>
    <?php endforeach; ?>
  </div>
</details>