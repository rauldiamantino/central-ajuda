const notificacaoErro = document.querySelector('.js-dashboard-notificacao-erro')
const notificacaoSucesso = document.querySelector('.js-dashboard-notificacao-sucesso')
const btnNotificacaoErroFechar = document.querySelector('.js-dashboard-notificacao-erro-btn-fechar')
const btnNotificacaoSucessoFechar = document.querySelector('.js-dashboard-notificacao-sucesso-btn-fechar')

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