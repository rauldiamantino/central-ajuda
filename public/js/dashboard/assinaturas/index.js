document.addEventListener('DOMContentLoaded', function () {
   mascararValor()
})

const mascararValor = () => {
  const valor = document.querySelector('#assinatura-valor')

  if (! valor) {
    return
  }

  new Cleave(valor, {
    numeral: true,
    numeralDecimalMark: ',',
    delimiter: '.'
  });
}

const assinarPlano = (plano) => {
  const modal = document.querySelector('.modal-plano-assinar')
  const btnAssinar = modal.querySelector('.modal-plano-btn-assinar')
  const btnFechar = modal.querySelector('.modal-plano-btn-fechar')
  const assinaturaId = modal.dataset.assinaturaId

  if (! modal || ! btnAssinar || ! btnFechar || ! assinaturaId) {
    return false;
  }

  modal.showModal()

  const clicouAssinar = () => {
    window.location.href= `/d/assinaturas/gerar/${assinaturaId}?plano=${plano}`;
  }

  const clicouFechar = () => {
    modal.close()
    removerListeners()
  }

  const removerListeners = () => {
    btnAssinar.removeEventListener('click', clicouAssinar)
    btnFechar.removeEventListener('click', clicouFechar)
  }

  btnAssinar.addEventListener('click', clicouAssinar)
  btnFechar.addEventListener('click', clicouFechar)
}