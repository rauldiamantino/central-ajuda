const empresaId = document.querySelector('body').dataset.empresaId
const empresa = document.querySelector('body').dataset.empresa
let raiz = document.querySelector('body').dataset.baseUrl

const baseUrl = (url = '') => {
  return raiz + url.replace(/^\//, '')
}

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
  const btnMenuLateralFechar = document.querySelector('.btn-dashboard-menu-lateral-fechar')
  const btnMenuTopoUsuario = document.querySelector('.btn-menu-topo-usuario')
  const menuTopoUsuario = document.querySelector('.menu-topo-usuario')
  const menuTopoUsuarioCima = document.querySelector('.perfil-usuario-cima')
  const menuTopoUsuarioBaixo = document.querySelector('.perfil-usuario-baixo')

  // Menu auxiliar
  const menuAuxiliar = document.querySelector('.menu-auxiliar')

  if (menuAuxiliar) {
    const botaoAbrirMenuAuxiliar = document.querySelector('.menu-auxiliar').previousElementSibling

    if (botaoAbrirMenuAuxiliar) {
      document.addEventListener('click', function (event) {

        if (! menuAuxiliar.classList.contains('hidden') && ! menuAuxiliar.contains(event.target) && ! botaoAbrirMenuAuxiliar.contains(event.target)) {
          menuAuxiliar.classList.add('hidden')
        }
      })
    }
  }

  if (notificacaoErro) {
    setTimeout(() => fecharNotificacao(notificacaoErro), 60000)
  }

  if (notificacaoSucesso) {
    setTimeout(() => fecharNotificacao(notificacaoSucesso), 60000)
  }

  if (notificacaoNeutra) {
    setTimeout(() => fecharNotificacao(notificacaoNeutra), 60000)
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

  if (btnMenuLateralFechar) {
    btnMenuLateralFechar.addEventListener('click', () => {
      menuLateralToggle(menuLateral)
    })
  }

  if (btnMenuTopoUsuario) {
    btnMenuTopoUsuario.addEventListener('click', () => {
      menuTopoUsuarioToggle(menuTopoUsuario, menuTopoUsuarioCima, menuTopoUsuarioBaixo)
    })
  }

  removerAutocomplete()
  setTimeout(() => window.scrollTo({top: 0, left: 0, behavior: 'smooth'}), 60)

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

const menuTopoUsuarioToggle = (menuTopoUsuario, menuTopoUsuarioCima, menuTopoUsuarioBaixo) => {

  if (menuTopoUsuario.classList.contains('hidden')) {
    menuTopoUsuario.classList.remove('hidden')
    document.addEventListener('click', menuTopoUsuarioFecharCliqueFora)
  }
  else {
    menuTopoUsuario.classList.add('hidden')
    document.removeEventListener('click', menuTopoUsuarioFecharCliqueFora)
  }

  alternarSetas(menuTopoUsuarioCima, menuTopoUsuarioBaixo)
}

const menuTopoUsuarioFecharCliqueFora = (e) => {
  const menuTopoUsuario = document.querySelector('.menu-topo-usuario')
  const btnMenuTopoUsuario = document.querySelector('.btn-menu-topo-usuario')
  const menuTopoUsuarioCima = document.querySelector('.perfil-usuario-cima')
  const menuTopoUsuarioBaixo = document.querySelector('.perfil-usuario-baixo')

  if (! menuTopoUsuario.contains(e.target) && ! btnMenuTopoUsuario.contains(e.target)) {
    menuTopoUsuario.classList.add('hidden')
    document.removeEventListener('click', menuTopoUsuarioFecharCliqueFora)

    alternarSetas(menuTopoUsuarioCima, menuTopoUsuarioBaixo)
  }
}

const alternarSetas = (menuTopoUsuarioCima, menuTopoUsuarioBaixo) => {

  if (menuTopoUsuarioCima.classList.contains('hidden')) {
    menuTopoUsuarioCima.classList.remove('hidden')
    menuTopoUsuarioBaixo.classList.add('hidden')
  }
  else {
    menuTopoUsuarioCima.classList.add('hidden')
    menuTopoUsuarioBaixo.classList.remove('hidden')
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

const converterInteiroParaDecimal = (valor = 0) => {
  const valorConvertido = valor / 100

  return valorConvertido.toFixed(2)
}

const converterParaReais = (valor = 0) => {
  return `R$ ${valor.replace('.', ',')}`
}

function editarImagemModal(event) {
  import('./artigos/conteudos/editar.js')
    .then(module => {
      const botaoAbrirModal = event.target
      module.editarImagem(botaoAbrirModal)
    })
    .catch(error => {
      console.error("Erro ao carregar o módulo:", error)
    })
}

function editarVideoModal(event) {
  import('./artigos/conteudos/editar.js')
    .then(module => {
      const botaoAbrirModal = event.target
      module.editarVideo(botaoAbrirModal)
    })
    .catch(error => {
      console.error("Erro ao carregar o módulo:", error)
    })
}

function editarTextoModal(event) {
  import('./artigos/conteudos/editar.js')
    .then(module => {
      const botaoAbrirModal = event.target
      module.editarTexto(botaoAbrirModal)
    })
    .catch(error => {
      console.error("Erro ao carregar o módulo:", error)
    })
}

function fecharTextoModal(event) {
  import('./artigos/conteudos/editar.js')
    .then(module => {
      const botaoCancelar = event.target
      module.fecharEditarTexto(botaoCancelar)
    })
    .catch(error => {
      console.error("Erro ao carregar o módulo:", error)
    })
}

function abrirModalAdicionar() {
  const modal = document.querySelector('.modal-conteudo-texto-adicionar')
  modal.classList.remove('hidden')

  setTimeout(() => {
    modal.scrollIntoView({ behavior: 'smooth' })
  }, 100)
}

function voltarAoTopo() {
  const modal = document.querySelector('.modal-conteudo-texto-adicionar')
  modal.classList.add('hidden')
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
