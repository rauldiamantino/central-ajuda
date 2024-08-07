const notificacaoErro = document.querySelector('.js-dashboard-notificacao-erro')
const notificacaoSucesso = document.querySelector('.js-dashboard-notificacao-sucesso')
const notificacaoNeutra = document.querySelector('.js-dashboard-notificacao-neutra')
const btnNotificacaoErroFechar = document.querySelector('.js-dashboard-notificacao-erro-btn-fechar')
const btnNotificacaoSucessoFechar = document.querySelector('.js-dashboard-notificacao-sucesso-btn-fechar')
const btnNotificacaoNeutraFechar = document.querySelector('.js-dashboard-notificacao-neutra-btn-fechar')

if (btnNotificacaoErroFechar) {
  btnNotificacaoErroFechar.addEventListener('click', () => {
    notificacaoErro.classList.add('hidden')
  })
}

if (btnNotificacaoSucessoFechar) {
  btnNotificacaoSucessoFechar.addEventListener('click', () => {
    notificacaoSucesso.classList.add('hidden')
  })
}

if (btnNotificacaoNeutraFechar) {
  btnNotificacaoNeutraFechar.addEventListener('click', () => {
    notificacaoNeutra.classList.add('hidden')
  })
}