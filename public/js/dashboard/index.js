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
  rolagemVerticalAutomatica()
})

const menuLateralToggle = (menuLateral) => {
  const body = document.body

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

const menuTopoUsuarioFecharCliqueFora = (event) => {
  const menuTopoUsuario = document.querySelector('.menu-topo-usuario')
  const btnMenuTopoUsuario = document.querySelector('.btn-menu-topo-usuario')
  const menuTopoUsuarioCima = document.querySelector('.perfil-usuario-cima')
  const menuTopoUsuarioBaixo = document.querySelector('.perfil-usuario-baixo')

  if (! menuTopoUsuario.contains(event.target) && ! btnMenuTopoUsuario.contains(event.target)) {
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

const rolagemVerticalAutomatica = () => {
  const urlParams = new URLSearchParams(window.location.search)
  const alvo = document.querySelector('.alvo-plano')

  if (urlParams.get('acao') === 'assinar' && alvo != undefined) {
    setTimeout(() => {
      alvo.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }, 800)
  }
  // else {
  //   setTimeout(() => window.scrollTo({top: 0, left: 0, behavior: 'smooth'}), 60)
  // }
}

const evitarDuploClique = (event) => {
  event.preventDefault()

  const form = event.target

  if (! form) {
    return
  }

  const botoes = form.querySelectorAll('.div-botoes > button')

  if (! botoes) {
    return
  }

  botoes.forEach(botao => {

    botao.disabled = true
    botao.classList.add('opacity-50')
  })

  form.submit()
}

const evitarDuploCliqueRedirect = (event, classeAlvo) => {
  event.preventDefault()

  const submitButton = event.target
  const formularioAlvo = document.querySelector(classeAlvo)

  if (submitButton.disabled) {
    return
  }

  if (! formularioAlvo) {
    return
  }

  submitButton.disabled = true
  submitButton.textContent = 'Gravando...'
  submitButton.classList.add('opacity-50')
  formularioAlvo.submit()
}

// Abrir adicionar e editar
const abrirModalAdicionar = async (tipoModal) => {
  const alvo = document.querySelector('.alvo-adicionar')
  const modal = document.querySelector(`.modal-conteudo-${tipoModal}-adicionar`)
  const demaisBlocosEditar = document.querySelectorAll(`.div-pai-conteudo-editar > .container-pre-visualizar`)
  let abrirAdicionar = true

  if (! alvo || ! modal || ! demaisBlocosEditar) {
    return
  }

  try {
    import('./artigos/conteudos/editar.js').then(async (module) => {
      for (const bloco of demaisBlocosEditar) {
        const tipo = bloco.dataset.conteudoTipo

        if (bloco.classList.contains('hidden')) {
          continue
        }

        if (tipo == 1 && module.fecharEditarTexto) {
          abrirAdicionar = await module.fecharEditarTexto(bloco)
        }
        else if (tipo == 2 && module.fecharEditarImagem) {
          abrirAdicionar = await module.fecharEditarImagem(bloco)
        }
        else if (tipo == 3 && module.fecharEditarVideo) {
          abrirAdicionar = await module.fecharEditarVideo(bloco)
        }

        if (! abrirAdicionar) {
          break
        }
      }

      const demaisBlocosAdicionar = document.querySelectorAll(`.container-pre-visualizar-adicionar`)
      const algumBlocoAdicionarAberto = Array.from(demaisBlocosAdicionar).some(bloco => ! bloco.classList.contains('hidden'))

      if (algumBlocoAdicionarAberto) {
        abrirAdicionar = await fecharDemaisAdicionar(demaisBlocosAdicionar)
      }

      if (abrirAdicionar) {
        modal.classList.remove('hidden')

        setTimeout(() => {
          alvo.scrollIntoView({ behavior: 'smooth', block: 'start' })
        }, 50)
      }
    })
  }
  catch (error) {
    console.error('Erro ao carregar o módulo:', error)
  }
}

const abrirModalEditar = async (event) => {
  const botaoAbrirModal = event.target
  const tipoBotao = botaoAbrirModal.dataset.conteudoTipo
  const demaisBlocosEditar = document.querySelectorAll(`.div-pai-conteudo-editar > .container-pre-visualizar`)
  let abrirEditar = true

  if (! botaoAbrirModal || ! tipoBotao || ! demaisBlocosEditar) {
    console.log('Dados do botão não informados')
    return
  }

  try {
    import('./artigos/conteudos/editar.js').then(async (module) => {
      for (const bloco of demaisBlocosEditar) {
        const tipoBloco = bloco.dataset.conteudoTipo

        if (bloco.classList.contains('hidden')) {
          continue
        }

        if (tipoBloco == 1 && module.fecharEditarTexto) {
          abrirEditar = await module.fecharEditarTexto(bloco)
        }
        else if (tipoBloco == 2 && module.fecharEditarImagem) {
          abrirEditar = await module.fecharEditarImagem(bloco)
        }
        else if (tipoBloco == 3 && module.fecharEditarVideo) {
          abrirEditar = await module.fecharEditarVideo(bloco)
        }

        if (! abrirEditar) {
          break
        }
      }

      const demaisBlocosAdicionar = document.querySelectorAll(`.container-pre-visualizar-adicionar`)
      const algumBlocoAdicionarAberto = Array.from(demaisBlocosAdicionar).some(bloco => ! bloco.classList.contains('hidden'))

      if (algumBlocoAdicionarAberto) {
        abrirEditar = await fecharDemaisAdicionar(demaisBlocosAdicionar)
      }

      if (! abrirEditar) {
        return
      }

      if (tipoBotao == 1) {
        module.editarTexto(botaoAbrirModal)
      }
      else if (tipoBotao == 2) {
        module.editarImagem(botaoAbrirModal)
      }
      else if (tipoBotao == 3) {
        module.editarVideo(botaoAbrirModal)
      }
    })
  }
  catch (error) {
    console.error('Erro ao carregar o módulo:', error)
  }
}

// Fechar adicionar e editar
const fecharModalAdicionar = async (tipoModal) => {
  const modal = document.querySelector(`.modal-conteudo-${tipoModal}-adicionar`)
  const containerConteudos = document.querySelectorAll('.div-pai-conteudo-editar')
  let fecharAdicionar = false

  if (! modal.classList.contains('hidden')) {
    fecharAdicionar = await fecharAdicionarAtual()
  }

  if (! fecharAdicionar) {
    return
  }

  containerConteudos.forEach(conteudo => {
    conteudo.classList.remove('hidden')
  })

  modal.classList.add('hidden')
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const fecharAdicionarAtual = () => {
  const modal = document.querySelector('.modal-conteudo-fechar')
  const btnContinuar = modal.querySelector('.modal-conteudo-btn-continuar')
  const btnFechar = modal.querySelector('.modal-conteudo-btn-fechar')

  if (! modal || ! btnContinuar || ! btnFechar) {
    return Promise.resolve(false)
  }

  modal.showModal()

  return new Promise((resolve) => {
    const clicouContinuar = () => {
      modal.close()
      removerListeners()
      resolve(false)
    }

    const clicouFechar = () => {
      modal.close()
      removerListeners()
      resolve(true)
    }

    const removerListeners = () => {
      btnContinuar.removeEventListener('click', clicouContinuar)
      btnFechar.removeEventListener('click', clicouFechar)
    }

    btnContinuar.addEventListener('click', clicouContinuar)
    btnFechar.addEventListener('click', clicouFechar)
  })
}

const fecharDemaisAdicionar = (demaisBlocosAdicionar) => {

  if (! demaisBlocosAdicionar) {
    return Promise.resolve(false)
  }

  const modal = document.querySelector('.modal-conteudo-fechar')
  const btnContinuar = modal.querySelector('.modal-conteudo-btn-continuar')
  const btnFechar = modal.querySelector('.modal-conteudo-btn-fechar')

  if (! modal || ! btnContinuar || ! btnFechar) {
    return Promise.resolve(false)
  }

  modal.showModal()

  return new Promise((resolve) => {
    const clicouContinuar = () => {
      modal.close()
      removerListeners()
      resolve(false)
    }

    const clicouFechar = () => {
      demaisBlocosAdicionar.forEach((blocoAdicionar) => {
        blocoAdicionar.classList.add('hidden')
      })

      modal.close()
      removerListeners()
      resolve(true)
    }

    const removerListeners = () => {
      btnContinuar.removeEventListener('click', clicouContinuar)
      btnFechar.removeEventListener('click', clicouFechar)
    }

    btnContinuar.addEventListener('click', clicouContinuar)
    btnFechar.addEventListener('click', clicouFechar)
  })
}

const fecharModalEditar = (event) => {
  import('./artigos/conteudos/editar.js')
    .then(module => {
      const botaoCancelar = event.target
      const tipoBotao = botaoCancelar.dataset.conteudoTipo

      if (tipoBotao == 1) {
        module.fecharEditarTexto(botaoCancelar)
      }
      else if (tipoBotao == 2) {
        module.fecharEditarImagem(botaoCancelar)
      }
      else if (tipoBotao == 3) {
        module.fecharEditarVideo(botaoCancelar)
      }
    })
    .catch(error => {
      console.error("Erro ao carregar o módulo:", error)
    })
}