const btnTextoAdicionar = document.querySelector('.conteudo-btn-texto-adicionar')
const btnImagemAdicionar = document.querySelector('.conteudo-btn-imagem-adicionar')
const btnVideoAdicionar = document.querySelector('.conteudo-btn-video-adicionar')
const btnImagemEscolher = document.querySelector('.conteudo-btn-imagem-escolher')

const textoAdicionar = document.querySelector('.conteudo-texto-adicionar')
const imagemAdicionar = document.querySelector('.conteudo-imagem-adicionar')
const videoAdicionar = document.querySelector('.conteudo-video-adicionar')
const imagemEscolher = document.querySelector('.conteudo-imagem-escolher')
const textoImagemEscolher = document.querySelector('.conteudo-txt-imagem-escolher')

if (btnTextoAdicionar) {
  btnTextoAdicionar.addEventListener('click', () => {
    
    if (textoAdicionar.classList.contains('hidden')) {
      textoAdicionar.classList.remove('hidden')
      imagemAdicionar.classList.add('hidden')
      videoAdicionar.classList.add('hidden')

      imagemAdicionar.querySelector('input').value = ''
      videoAdicionar.querySelector('input').value = ''
      
      textoImagemEscolher.textContent = 'Escolher Imagem'
      imagemEscolher.value = ''
    }
    else {
      textoAdicionar.classList.add('hidden')
      textoAdicionar.querySelector('textarea').value = ''
    }
  })
}

if (btnImagemAdicionar) {
  btnImagemAdicionar.addEventListener('click', () => {

    if (imagemAdicionar.classList.contains('hidden')) {
      imagemAdicionar.classList.remove('hidden')
      textoAdicionar.classList.add('hidden')
      videoAdicionar.classList.add('hidden')
      textoAdicionar.querySelector('textarea').value = ''
      videoAdicionar.querySelector('input').value = ''
    }
    else {
      imagemAdicionar.classList.add('hidden')
      imagemAdicionar.querySelector('input').value = ''

      textoImagemEscolher.textContent = 'Escolher Imagem'
      imagemEscolher.value = ''
    }
  })
}

if (btnVideoAdicionar) {
  btnVideoAdicionar.addEventListener('click', () => {
    
    if (videoAdicionar.classList.contains('hidden')) {
      videoAdicionar.classList.remove('hidden')
      imagemAdicionar.classList.add('hidden')
      textoAdicionar.classList.add('hidden')
      imagemAdicionar.querySelector('input').value = ''
      textoAdicionar.querySelector('textarea').value = ''
      
      textoImagemEscolher.textContent = 'Escolher Imagem'
      imagemEscolher.value = ''
    }
    else {
      videoAdicionar.classList.add('hidden')
      videoAdicionar.querySelector('input').value = ''
    }
  })
}

if (btnImagemEscolher) {
  btnImagemEscolher.addEventListener('click', () => {
    imagemEscolher.click()
  })
}

if (imagemEscolher) {
  imagemEscolher.addEventListener('change', (event) => {
    const anexo = event.target.files[0]
    const imgElemento = imagemAdicionar.querySelector('img')

    if (anexo) {
      const objetoReader = new FileReader()

      objetoReader.onload = (e) => {
        imgElemento.src = e.target.result
        imgElemento.classList.remove('opacity-0')
        imgElemento.classList.add('opacity-100')
      }

      textoImagemEscolher.textContent = anexo.name
      objetoReader.readAsDataURL(anexo)
    }
  })
}

// ----------- Adicionar bloco de conteúdo -----------
const formConteudo = document.querySelector('.form-conteudo')

if (formConteudo) {
  formConteudo.addEventListener('submit', (event) => {
    event.preventDefault()
    fetchFormConteudo(formConteudo)
  })

  // funções
  const fetchFormConteudo = (formConteudo) => {
    const url = '/conteudo'
    const formConteudoData = new FormData(formConteudo)
    const conteudos = document.querySelectorAll('.conteudo-bloco')
    let ordemUltimoConteudo = 0

    if (conteudos != undefined && conteudos.length > 0) {
      const ultimoConteudo = conteudos[conteudos.length - 1]
      ordemUltimoConteudo = parseInt(ultimoConteudo.dataset.conteudoOrdem)
    }

    const formTexto = document.querySelector('.conteudo-texto-adicionar > textarea[name=conteudo').value
    const formImagem = document.querySelector('.conteudo-imagem-adicionar > input[name=url').files[0]
    const formVideo = document.querySelector('.conteudo-video-adicionar > input[name=url').value
    const formTextoTitulo = document.querySelector('#conteudo-texto-titulo').value
    const formImagemTitulo = document.querySelector('#conteudo-imagem-titulo').value
    const formVideoTitulo = document.querySelector('#conteudo-video-titulo').value

    if (formTexto && formTextoTitulo) {
      formConteudoData.append('conteudo', formTexto)
      formConteudoData.append('titulo', formTextoTitulo)
      formConteudoData.append('tipo', 1)
    }
    else if (formImagem && formImagemTitulo) {
      formConteudoData.append('url', formImagem)
      formConteudoData.append('titulo', formImagemTitulo)
      formConteudoData.append('tipo', 2)
    }
    else if (formVideo && formVideoTitulo) {
      formConteudoData.append('url', formVideo)
      formConteudoData.append('titulo', formVideoTitulo)
      formConteudoData.append('tipo', 3)
    }
    else {
      return
    }

    formConteudoData.append('ordem', ordemUltimoConteudo + 1)

    fetch(url, {
      method: 'POST',
      body: formConteudoData
    })
    .then(resposta => resposta.json())
    .then(resposta => location.reload())
    .catch(error => {
      console.error('Erro:', error)
    })
  }
}

// ----------- Editar bloco de conteúdo -----------
const btnsConteudoEditar = document.querySelectorAll('.js-dashboard-conteudo-editar')

const modalConteudoTextoEditar = document.querySelector('.modal-conteudo-texto-editar')
const editarTextoTitulo = document.querySelector('#conteudo-editar-texto-titulo')
const editarTextoConteudo = document.querySelector('#conteudo-editar-texto-conteudo')
const btnCancelarModalEditarTexto = document.querySelector('.modal-texto-editar-btn-cancelar')
const formularioEditarTexto = document.querySelector('.modal-conteudo-texto-editar > form')

const modalConteudoImagemEditar = document.querySelector('.modal-conteudo-imagem-editar')
const editarImagemTitulo = document.querySelector('#conteudo-editar-imagem-titulo')
const editarImagemEscolher = document.querySelector('.conteudo-editar-imagem-escolher')
const editarTextoImagemEscolher = document.querySelector('.conteudo-txt-imagem-editar-escolher')
const btnEditarImagemEscolher = document.querySelector('.conteudo-btn-imagem-editar-escolher')
const btnCancelarModalEditarImagem = document.querySelector('.modal-conteudo-imagem-btn-cancelar')
const formularioEditarImagem = document.querySelector('.modal-conteudo-imagem-editar > form')

const modalConteudoVideoEditar = document.querySelector('.modal-conteudo-video-editar')
const editarVideoTitulo = document.querySelector('#conteudo-editar-video-titulo')
const editarVideoUrl = document.querySelector('#conteudo-editar-video-url')
const btnCancelarModalEditarVideo = document.querySelector('.modal-conteudo-video-btn-cancelar')
const formularioEditarVideo = document.querySelector('.modal-conteudo-video-editar > form')

if (btnsConteudoEditar) {
  btnsConteudoEditar.forEach(conteudo => {
    conteudo.addEventListener('click', () => {
      
      if (conteudo.dataset.conteudoTipo == 1) {
        editarTextoTitulo.value = conteudo.dataset.conteudoTitulo
        editarTextoConteudo.textContent = conteudo.dataset.conteudoConteudo
        formularioEditarTexto.action = '/conteudo/' + conteudo.dataset.conteudoId
        modalConteudoTextoEditar.showModal()
      }
      else if (conteudo.dataset.conteudoTipo == 2) {
        editarImagemTitulo.value = conteudo.dataset.conteudoTitulo
        formularioEditarImagem.action = '/conteudo/' + conteudo.dataset.conteudoId
        
        const imgElemento = modalConteudoImagemEditar.querySelector('img')
        
        imgElemento.src = '/' + conteudo.dataset.conteudoUrl
        imgElemento.classList.add('opacity-100')
        imgElemento.classList.remove('opacity-0')
        modalConteudoImagemEditar.showModal()

        if (btnEditarImagemEscolher) {
          btnEditarImagemEscolher.addEventListener('click', () => {
            editarImagemEscolher.click()
          })
        }

        editarImagemEscolher.addEventListener('change', (event) => {
          const anexo = event.target.files[0]

          if (anexo) {
            const objetoReader = new FileReader()

            objetoReader.onload = (e) => {
              imgElemento.src = e.target.result
              imgElemento.classList.remove('opacity-0')
              imgElemento.classList.add('opacity-100')
            }

            editarTextoImagemEscolher.textContent = anexo.name
            objetoReader.readAsDataURL(anexo)
          }
        })

        editarTextoImagemEscolher.textContent = 'Alterar imagem'
      }
      else if (conteudo.dataset.conteudoTipo == 3) {
        editarVideoTitulo.value = conteudo.dataset.conteudoTitulo
        editarVideoUrl.value = conteudo.dataset.conteudoUrl
        formularioEditarVideo.action = '/conteudo/' + conteudo.dataset.conteudoId
        modalConteudoVideoEditar.showModal()
      }
    })
  })
}

if (btnCancelarModalEditarTexto) {
  btnCancelarModalEditarTexto.addEventListener('click', () => {
    modalConteudoTextoEditar.close()
  })
}

if (btnCancelarModalEditarVideo) {
  btnCancelarModalEditarVideo.addEventListener('click', () => {
    modalConteudoVideoEditar.close()
  })
}

if (btnCancelarModalEditarImagem) {
  btnCancelarModalEditarImagem.addEventListener('click', () => fecharModalImagem())
}

document.addEventListener('keydown', (event) => {
  
  if (event.key === 'Escape' || event.keyCode === 27 && modalConteudoImagemEditar.open) {
    fecharModalImagem()
  }
})

function fecharModalImagem() {
  modalConteudoImagemEditar.querySelector('img').src = '';
  modalConteudoImagemEditar.close();
}

// ----------- Remover bloco de conteúdo -----------
let conteudoId = null
const btnsConteudoRemover = document.querySelectorAll('.js-dashboard-conteudo-remover')
const modalConteudoRemover = document.querySelector('.modal-conteudo-remover')
const btnModalConteudoRemover = document.querySelector('.modal-conteudo-btn-remover')
const btnModalConteudoCancelar = document.querySelector('.modal-conteudo-btn-cancelar')

if (btnsConteudoRemover) {
  btnsConteudoRemover.forEach(conteudo => {
    conteudo.addEventListener('click', () => {
      conteudoId = conteudo.dataset.conteudoId
      abrirModalConteudoRemover()
    })
  })
}

if (btnModalConteudoRemover) {
  btnModalConteudoRemover.addEventListener('click', () => {
    requisicaoConteudoRemover(conteudoId)
    fecharModalConteudoRemover()
  })
}

if (btnModalConteudoCancelar) {
  btnModalConteudoCancelar.addEventListener('click', () => {
    fecharModalConteudoRemover()
  })
}

const abrirModalConteudoRemover = () => {
  modalConteudoRemover.showModal()
}

const fecharModalConteudoRemover = () => {
  modalConteudoRemover.close()
}

const requisicaoConteudoRemover = (conteudoId) => {

  if (! conteudoId) {
    return
  }

  fetch(`/conteudo/${conteudoId}`, { method: 'DELETE' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.linhasAfetadas == 1) {
        location.reload()
      }
      else if (resposta.erro) {
        throw new Error(resposta.erro)
      }
      else {
        throw new Error('Erro ao remover conteudo')
      }
    })
    .catch(error => {
      location.reload()
    })
}

// ----------- Reoganizar blocos de conteúdo -----------
document.addEventListener('DOMContentLoaded', function () {
  const bloco = document.querySelector('.conteudo-blocos')
  
  Sortable.create(bloco, {
    animation: 150,
    handle: '.handle',
    onEnd: function () {
      const ordem = []
  
      document.querySelectorAll('.conteudo-bloco').forEach(function (item, index) {
        ordem.push({
          id: item.dataset.conteudoId,
          ordem: index
        })
      })
  
      if (! ordem) {
        return
      }

      fetch(`/conteudo/ordem`, {
         method: 'PUT',
         body: JSON.stringify(ordem)
        })
        .then(resposta => resposta.json())
        .then(resposta => {
          
          if (resposta.linhasAfetadas > 0) {
            console.log(resposta.linhasAfetadas + ' conteúdos reorganizados')
          }
          else if (resposta.erro) {
            throw new Error(resposta.erro)
          }
          else {
            throw new Error('Erro ao reorganizar conteudo')
          }
        })
        .catch(error => {
          location.reload()
        })
    }
  })
})