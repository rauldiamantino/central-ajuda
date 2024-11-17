document.addEventListener('DOMContentLoaded', function() {
  const topoPublico = document.querySelector('.topo-publico')
  const topoPublicoInverter = document.querySelector('.inverter')
  const menuLateral = document.querySelector('.publico-menu-lateral')
  const btnMenuLateral = document.querySelector('.btn-publico-menu-lateral')
  const btnMenuLateralFechar = document.querySelector('.btn-publico-menu-lateral-fechar')
  const notificacaoErro = document.querySelector('.js-notificacao-erro-publico')
  const btnNotificacaoErroFechar = document.querySelector('.js-dashboard-notificacao-erro-btn-fechar')
  const notificacaoSucesso = document.querySelector('.js-notificacao-sucesso-publico')
  const btnNotificacaoSucessoFechar = document.querySelector('.js-dashboard-notificacao-sucesso-btn-fechar')

  const liberaScrollBody = () => {
    const body = document.body

    if (window.innerWidth >= 768) {
      body.classList.remove('overflow-hidden')
    }
  }

  // Escuta maximizar e minimizar
  window.addEventListener('resize', liberaScrollBody)

  if (btnMenuLateral) {
    btnMenuLateral.addEventListener('click', () => {
      menuLateralToggle(menuLateral)
    })
  }

  if (btnMenuLateralFechar) {
    btnMenuLateralFechar.addEventListener('click', () => {
      menuLateralToggle(menuLateral)
    })
  }

  if (notificacaoErro) {
    setTimeout(() => fecharNotificacao(notificacaoErro), 6000)
  }

  if (btnNotificacaoErroFechar) {
    btnNotificacaoErroFechar.addEventListener('click', () => {
     fecharNotificacao(notificacaoErro)
    })
  }

  if (notificacaoSucesso) {
    setTimeout(() => fecharNotificacao(notificacaoSucesso), 6000)
  }

  if (btnNotificacaoSucessoFechar) {
    btnNotificacaoSucessoFechar.addEventListener('click', () => {
     fecharNotificacao(notificacaoSucesso)
    })
  }

  const menuLateralToggle = (menuLateral) => {
    const body = document.body

    if (menuLateral.classList.contains('-translate-x-full') && ! menuLateral.classList.contains('translate-x-0')) {
      menuLateral.classList.add('translate-x-0')
      menuLateral.classList.remove('-translate-x-full')

      if (window.innerWidth < 768) {
        body.classList.add('overflow-hidden')
      }
    }
    else if (! menuLateral.classList.contains('-translate-x-full') && menuLateral.classList.contains('translate-x-0')) {
      menuLateral.classList.remove('translate-x-0')
      menuLateral.classList.add('-translate-x-full')
      body.classList.remove('overflow-hidden')
    }
  }

  const fecharNotificacao = (notificacao) => {

    if (! notificacao.classList.contains('hidden')) {
      notificacao.classList.add('hidden')
    }
  }
})