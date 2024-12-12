<div class="mb-10 relative w-full h-max flex flex-col container-assinatura">
  <div class="mb-4 w-full flex flex-col lg:flex-row justify-between items-start lg:items-center">
    <div class="mb-5 w-full h-full flex flex-col justify-end">
      <h2 class="text-3xl font-semibold flex gap-2">Assinatura <span class="flex items-center gap-2 text-gray-400 font-light italic text-sm">#<?php echo $assinatura['Assinatura']['id']; ?></span>
      </h2>
      <p class="text-gray-600">Gerencie seu plano e consulte o histórico de pagamentos.</p>
    </div>
    <div class="py-2 w-full h-full flex gap-2 items-start justify-end">
      <?php if ((int) $this->usuarioLogado['padrao'] == USUARIO_SUPORTE) { ?>
        <form action="/dashboard/validar_assinatura" method="GET" class="<?php echo CLASSES_DASH_BUTTON_LIMPAR; ?> flex justify-start items-center gap-2">
          <input type="hidden" name="asaas_id" value="<?php echo $assinatura['Assinatura']['asaas_id']; ?>">
          <button type="submit">Reprocessar</button>
        </form>
        <button type="submit" class="<?php echo CLASSES_DASH_BUTTON_GRAVAR; ?> btn-gravar-assinatura" form="form-editar-assinatura" onclick="evitarDuploCliqueRedirect(event, '.form-editar-assinatura');">Gravar</button>
      <?php } ?>
    </div>
  </div>

  <?php require_once 'plano.php' ?>
  <?php require_once 'formulario.php' ?>
  <?php require_once 'modais/assinar.php' ?>
</div>