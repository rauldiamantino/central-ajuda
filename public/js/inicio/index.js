let indiceAtual = 0

const moveSlide = (direcao) => {
  const imagens = document.querySelector('#carrossel-imagens')
  const totalImagens = document.querySelectorAll('#carrossel-imagens img').length

  indiceAtual = (indiceAtual + direcao + totalImagens) % totalImagens
  imagens.style.transform = `translateX(-${indiceAtual * 100}%)`
}

const fecharNotificacao = (notificacao) => {

  if (! notificacao.classList.contains('hidden')) {
    notificacao.classList.add('hidden')
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const notificacaoErro = document.querySelector('.js-inicio-notificacao-erro')
  const notificacaoSucesso = document.querySelector('.js-inicio-notificacao-sucesso')
  const notificacaoNeutra = document.querySelector('.js-inicio-notificacao-neutra')
  const btnNotificacaoErroFechar = document.querySelector('.js-inicio-notificacao-erro-btn-fechar')
  const btnNotificacaoSucessoFechar = document.querySelector('.js-inicio-notificacao-sucesso-btn-fechar')
  const btnNotificacaoNeutraFechar = document.querySelector('.js-inicio-notificacao-neutra-btn-fechar')

  if (notificacaoErro) {
    setTimeout(() => fecharNotificacao(notificacaoErro), 6000)
  }

  if (notificacaoSucesso) {
    setTimeout(() => fecharNotificacao(notificacaoSucesso), 6000)
  }

  if (notificacaoNeutra) {
    setTimeout(() => fecharNotificacao(notificacaoNeutra), 6000)
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

  window.addEventListener('scroll', function() {
    const header = document.querySelector('#header')
    const logo = document.querySelector('#logo-img')
    const botaoLogin = document.querySelector('#login-btn')

    if (window.scrollY > 0) {
      header.classList.add('bg-gray-800', 'text-white', 'border-gray-800')
      header.classList.remove('bg-white', 'border-gray-100')
      botaoLogin.classList.remove('text-gray-800', 'bg-gray-100')
      botaoLogin.classList.add('text-white', 'bg-gray-700/50')
      logo.classList.add('invert')
    }
    else {
      header.classList.remove('bg-gray-800', 'text-white', 'border-gray-800')
      header.classList.add('bg-white', 'border-gray-100')
      botaoLogin.classList.add('text-gray-800', 'bg-gray-100')
      botaoLogin.classList.remove('text-white', 'bg-gray-700/50')
      logo.classList.remove('invert')
    }
  })
})