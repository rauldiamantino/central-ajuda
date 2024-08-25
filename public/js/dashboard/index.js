const fecharNotificacao = (notificacao) => {

  if (! notificacao.classList.contains('hidden')) {
    notificacao.classList.add('hidden')
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const notificacaoErro = document.querySelector('.js-dashboard-notificacao-erro')
  const notificacaoSucesso = document.querySelector('.js-dashboard-notificacao-sucesso')
  const notificacaoNeutra = document.querySelector('.js-dashboard-notificacao-neutra')
  const btnNotificacaoErroFechar = document.querySelector('.js-dashboard-notificacao-erro-btn-fechar')
  const btnNotificacaoSucessoFechar = document.querySelector('.js-dashboard-notificacao-sucesso-btn-fechar')
  const btnNotificacaoNeutraFechar = document.querySelector('.js-dashboard-notificacao-neutra-btn-fechar')
  const menuLateral = document.querySelector('.dashboard-menu-lateral')
  const btnMenuLateral = document.querySelector('.btn-dashboard-menu-lateral')

  if (notificacaoErro) {
    setTimeout(() => fecharNotificacao(notificacaoErro), 5000)
  }

  if (notificacaoSucesso) {
    setTimeout(() => fecharNotificacao(notificacaoSucesso), 5000)
  }

  if (notificacaoNeutra) {
    setTimeout(() => fecharNotificacao(notificacaoNeutra), 5000)
  }

  if (btnNotificacaoErroFechar) {
    btnNotificacaoErroFechar.addEventListener('click', () => {
     fecharNotificacao(notificacaoErro)
    })
  }

  if (btnNotificacaoSucessoFechar) {
    btnNotificacaoSucessoFechar.addEventListener('click', () => {
      fecharNotificacao(notificacaoSucesso)
    })
  }

  if (btnNotificacaoNeutraFechar) {
    btnNotificacaoNeutraFechar.addEventListener('click', () => {
      fecharNotificacao(notificacaoNeutra)
    })
  }

  if (btnMenuLateral) {
    btnMenuLateral.addEventListener('click', () => {
      menuLateralToggle(menuLateral)
    })
  }
  
  removerAutocomplete()
  setTimeout(() => window.scrollTo({top: 0, left: 0, behavior: 'smooth'}), 60)
})

const menuLateralToggle = (menuLateral) => {

  if (menuLateral.classList.contains('-translate-x-full') && ! menuLateral.classList.contains('translate-x-0')) {
    menuLateral.classList.add('translate-x-0')
    menuLateral.classList.remove('-translate-x-full')
  }
  else if (! menuLateral.classList.contains('-translate-x-full') && menuLateral.classList.contains('translate-x-0')) {
    menuLateral.classList.remove('translate-x-0')
    menuLateral.classList.add('-translate-x-full')
  }
}

const removerAutocomplete = () => {
  let inputs = document.querySelectorAll('input[autocomplete="off"]')

  if (! inputs) {
    return
  }

  inputs.forEach(input => {
    input.setAttribute('disabled', 'disabled')

    setTimeout(function(){
      input.removeAttribute('disabled')
    }, 1000)
  })
}