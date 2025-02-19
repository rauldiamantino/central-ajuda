document.addEventListener('DOMContentLoaded', function() {
  const efeitoLoaderDiv = document.querySelector('.efeito-loader-publico-div')
  const efeitoLoader = document.querySelector('.efeito-loader-publico')
  const conteudoPublico = document.querySelector('#conteudo-publico')

  setTimeout(() => {
    efeitoLoaderDiv.classList.add('hidden')
    efeitoLoader.classList.add('hidden')
    conteudoPublico.classList.remove('hidden')
  }, 700)

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

  const alteraCorLupa = () => {
    const formsBuscaInicio = document.querySelectorAll('.form-busca-inicio')

    if (! formsBuscaInicio) {
      return
    }

    formsBuscaInicio.forEach(form => {
      const inputBusca = form.querySelector('.input_busca')
      const botaoBusca = form.querySelector('.botao_busca')

      inputBusca?.addEventListener('focus', () => {
        botaoBusca.classList.add('text-gray-600')
        botaoBusca.classList.remove('text-white')
      })

      inputBusca?.addEventListener('blur', () => {
        botaoBusca.classList.add('text-white')
        botaoBusca.classList.remove('text-gray-600')
      })
    })
  }

  const scrollMenuTopo = () => {
    let menuLoginCor = ''

    document.addEventListener('scroll', function () {
      const topo = document.querySelector('#topo')

      if (! topo) {
        return
      }

      const logo = topo.querySelector('img')
      const menuLogin = topo.querySelector('.menu-login-desktop')
      const menuHamburguer = topo.querySelector('.menu-hamburguer-mobile')
      const telaInicio = topo.dataset.inicio
      const topoFixo = topo.dataset.topoFixo
      const topoInverter = topo.dataset.topoInverter
      const topoTransparente = topo.dataset.topoTransparente

      if (topoFixo == 0) {
        return
      }

      if (telaInicio && menuLogin && menuLoginCor == '') {
        menuLoginCor = getComputedStyle(menuLogin).color
      }

      if (window.scrollY > 50) {
        topo.classList.add('shadow-md')

        if (telaInicio && menuLogin) {
          menuLogin.style.color = '#374151'
        }

        if (telaInicio && topoInverter == 1) {
          logo?.classList.remove('filter', 'invert', 'grayscale', 'brightness-0')
          menuHamburguer?.classList.remove('filter', 'invert', 'grayscale', 'brightness-0')
        }

        if (telaInicio && topoTransparente == 1) {
          topo.classList.add('bg-white')
        }
      }
      else {
        topo.classList.remove('shadow-md')

        if (telaInicio && menuLogin) {
          menuLogin.style.color = menuLoginCor
        }

        if (telaInicio && topoInverter == 1) {
          logo?.classList.add('filter', 'invert', 'grayscale', 'brightness-0')
          menuHamburguer?.classList.add('filter', 'invert', 'grayscale', 'brightness-0')
        }

        if (telaInicio && topoTransparente == 1) {
          topo.classList.remove('bg-white')
        }
      }
    })
  }

  scrollMenuTopo()
  alteraCorLupa()
})