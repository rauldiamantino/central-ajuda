document.addEventListener('DOMContentLoaded', function () {
  const containerAssinatura = document.querySelector('.container-assinatura')
  const efeitoLoader = document.querySelector('.efeito-loader')

  if (! containerAssinatura || ! efeitoLoader) {
    return
  }

  setTimeout(() => {
    efeitoLoader.classList.add('hidden')
    containerAssinatura.classList.remove('hidden')
  }, 300);
})

const assinarPlano = (plano) => {
  const modal = document.querySelector('.modal-plano-assinar')
  const btnAssinar = modal.querySelector('.modal-plano-btn-assinar')
  const btnFechar = modal.querySelector('.modal-plano-btn-fechar')

  if (! modal || ! btnAssinar || ! btnFechar) {
    return false;
  }

  modal.showModal()

  const clicouAssinar = () => {
    window.location.href= `/${empresa}/d/assinaturas/gerar?plano=${plano}`;
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