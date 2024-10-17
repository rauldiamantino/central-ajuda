<div class="w-full min-h-max border border-slate-300 shadow rounded-lg bg-white">
  <div class="flex w-full h-full flex-col justify-center px-6 py-12 md:px-10">
    <div class="w-full justify-center flex items-center">
      <img src="<?php echo baseUrl('/img/360help-branco.svg')?>" class="w-44">
    </div>
    <div class="w-full">
      <?php require_once 'formulario.php' ?>
      <p class="mt-10 text-center text-sm text-gray-500">
        Já possui cadastro?
        <a href="<?php echo baseUrl('/login'); ?>" class="font-semibold leading-6 text-blue-900 hover:text-blue-500">Clique aqui</a>
      </p>
    </div>
  </div>
</div>
