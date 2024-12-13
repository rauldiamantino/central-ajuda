<form class="space-y-4" action="/login/redir" method="POST">
  <div class="relative flex flex-col gap-2">
    <input name="empresa" type="empresa" class="<?php echo CLASSES_LOGIN_INPUT; ?>" placeholder="Identificação da sua empresa" autocomplete="off">
    <div class="p-1 h-full absolute right-0 flex items-center justify-center">
      <button type="submit" class="<?php echo CLASSES_LOGIN_REDIR_BUTTON; ?>">Ir</button>
    </div>
  </div>
</form>