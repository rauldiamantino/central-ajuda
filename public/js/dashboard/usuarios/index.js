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

  fetch(baseUrl(`/d/${empresaId}/usuario/${usuarioId}`), { method: 'DELETE' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.linhasAfetadas == 1) {
        location.reload()
      }
      else if (resposta.erro) {
        throw new Error(resposta.erro)
      }
      else {
        throw new Error('Erro ao remover usuario')
      }
    })
    .catch(error => {
      location.reload()
    })
}

const inputNumeroUsuarioPagina = document.querySelector('.usuario-numero-pagina')

if (inputNumeroUsuarioPagina) {
  inputNumeroUsuarioPagina.addEventListener('input', () => {

    if (this.value < 0) {
      this.value = '';
    }
  });
}

if (inputNumeroUsuarioPagina) {
  inputNumeroUsuarioPagina.addEventListener('keypress', function(event) {
    if (event.key === '-' || event.key === 'e') {
      event.preventDefault();
    }
  });
}