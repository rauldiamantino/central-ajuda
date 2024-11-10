let usuarioId = null

const btnsUsuarioEditar = document.querySelectorAll('.js-dashboard-usuarios-editar')
const btnsUsuarioRemover = document.querySelectorAll('.js-dashboard-usuarios-remover')
const modalUsuarioRemover = document.querySelector('.modal-usuario-remover')
const btnModalUsuarioRemover = document.querySelector('.modal-usuario-btn-remover')
const btnModalUsuarioCancelar = document.querySelector('.modal-usuario-btn-cancelar')

if (btnsUsuarioEditar) {
  btnsUsuarioEditar.forEach(usuario => {
    usuario.addEventListener('click', () => {

    })
  })
}

if (btnsUsuarioRemover) {
  btnsUsuarioRemover.forEach(usuario => {
    usuario.addEventListener('click', () => {
      usuarioId = usuario.dataset.usuarioId
      abrirModalUsuarioRemover()
    })
  })
}

if (btnModalUsuarioRemover) {
  btnModalUsuarioRemover.addEventListener('click', () => {
    requisicaoUsuarioRemover(usuarioId)
    fecharModalUsuarioRemover()
  })
}

if (btnModalUsuarioCancelar) {
    btnModalUsuarioCancelar.addEventListener('click', () => {
    fecharModalUsuarioRemover()
  })
}

const abrirModalUsuarioRemover = () => {
  modalUsuarioRemover.showModal()
}

const fecharModalUsuarioRemover = () => {
  modalUsuarioRemover.close()
}

const requisicaoUsuarioRemover = (usuarioId) => {

  if (! usuarioId) {
    return
  }

  if (! empresaId) {
    return
  }

  fetch(baseUrl(`/${empresa}/d/usuario/${usuarioId}`), { method: 'DELETE' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.linhasAfetadas == 1) {
        window.location.href = window.location.href
      }
      else if (resposta.erro) {
        throw new Error(resposta.erro)
      }
      else {
        throw new Error('Erro ao remover usuario')
      }
    })
    .catch(error => {
      window.location.href = window.location.href
    })
}

const inputNumeroUsuarioPagina = document.querySelector('.usuario-numero-pagina')

if (inputNumeroUsuarioPagina) {
  inputNumeroUsuarioPagina.addEventListener('input', () => {

    if (this.value < 0) {
      this.value = ''
    }
  })
}

if (inputNumeroUsuarioPagina) {
  inputNumeroUsuarioPagina.addEventListener('keypress', function(event) {
    if (event.key === '-' || event.key === 'e') {
      event.preventDefault()
    }
  })
}

document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('.menu-adicionar-usuario-form')

  if (! form) {
    return
  }

  form.addEventListener('submit', function (event) {
    const formButton = form.querySelector('button[type="submit"]')

    formButton.disabled = true
    event.preventDefault()

    const formData = new FormData(form)

    fetch(form.dataset.action, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: formData,
    })
      .then(response => response.json())
      .then(resposta => {

        if (resposta.erro) {
          limparErros()
          exibirErros(resposta.erro)
        }
        else {
          window.location.href = window.location.href
        }
      })
      .catch(error => {
        console.error('Erro na requisição:', error)
      })
      .finally(() => {
        formButton.disabled = false
      })
  })

  const mensagensAmigaveis = {
    " • Ter pelo menos 8 caracteres.": "A senha precisa ter pelo menos 8 caracteres.",
    " • Conter pelo menos uma letra maiúscula.": "A senha deve ter ao menos uma letra maiúscula.",
    " • Conter pelo menos uma letra minúscula.": "A senha deve ter ao menos uma letra minúscula.",
    " • Conter pelo menos um número.": "A senha deve incluir pelo menos um número.",
    " • Conter pelo menos um caractere especial (ex: !, @, #, $).": "A senha precisa ter pelo menos um caractere especial (ex: !, @, #, $)."
  }

  function exibirErros(erros) {
    if (erros.mensagem) {
      erros.mensagem.forEach(mensagem => {

        if (mensagem.includes('email')) {
          exibirErroCampo('usuario-editar-email', mensagem)
        }
        else if (mensagensAmigaveis[mensagem]) {
          exibirErroCampo('usuario-editar-senha', mensagensAmigaveis[mensagem])
        }
        else if (mensagem.includes('nome')) {
          exibirErroCampo('usuario-editar-nome', mensagem)
        }
      })
    }
  }

  function exibirErroCampo(campoId, mensagem) {
    const campo = document.getElementById(campoId)

    if (campo) {
      campo.classList.add('ring-red-500', 'focus:ring-red-500')

      let erroElement = document.createElement('span')
      erroElement.classList.add('text-red-500', 'text-sm', 'mt-2', 'block')
      erroElement.textContent = mensagem

      if (!campo.parentElement.querySelector('.text-red-500')) {
        campo.parentElement.appendChild(erroElement)
      }
    }
  }

  function limparErros() {
    const errorMessages = form.querySelectorAll('.text-red-500')
    errorMessages.forEach(msg => msg.remove())

    const inputs = form.querySelectorAll('input, select')

    inputs.forEach(input => {
      input.classList.remove('ring-red-500', 'focus:ring-red-500')
      input.removeAttribute('aria-invalid')
    })
  }
})