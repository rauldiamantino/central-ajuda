import { substituirImagem } from '../../firebase/funcoes.js'
import { iniciarEditor, editor } from '../../editorjs.js'

const btnsConteudoEditar = document.querySelectorAll('.js-dashboard-conteudo-editar')
const modalConteudoTextoEditar = document.querySelector('.modal-conteudo-texto-editar')
const editarTextoTitulo = document.querySelector('#conteudo-editar-texto-titulo')
const editarTextoTituloOcultar = document.querySelector('.conteudo-editar-texto-titulo-ocultar')
const btnCancelarModalEditarTexto = document.querySelector('.modal-texto-editar-btn-cancelar')
const formularioEditarTexto = document.querySelector('.modal-conteudo-texto-editar > form')

const modalConteudoImagemEditar = document.querySelector('.modal-conteudo-imagem-editar')
const editarImagemTitulo = document.querySelector('#conteudo-editar-imagem-titulo')
const editarImagemTituloOcultar = document.querySelector('.conteudo-editar-imagem-titulo-ocultar')
const editarImagemEscolher = document.querySelector('.conteudo-editar-imagem-escolher')
const editarTextoImagemEscolher = document.querySelector('.conteudo-txt-imagem-editar-escolher')
const btnEditarImagemEscolher = document.querySelector('.conteudo-btn-imagem-editar-escolher')
const btnCancelarModalEditarImagem = document.querySelector('.modal-conteudo-imagem-btn-cancelar')
const formularioEditarImagem = document.querySelector('.modal-conteudo-imagem-editar > form')

const modalConteudoVideoEditar = document.querySelector('.modal-conteudo-video-editar')
const editarVideoTitulo = document.querySelector('#conteudo-editar-video-titulo')
const editarVideoTituloOcultar = document.querySelector('.conteudo-editar-video-titulo-ocultar')
const editarVideoUrl = document.querySelector('#conteudo-editar-video-url')
const btnCancelarModalEditarVideo = document.querySelector('.modal-conteudo-video-btn-cancelar')
const formularioEditarVideo = document.querySelector('.modal-conteudo-video-editar > form')

let imagemParaUpload = null
let imagemAtual = null

if (btnsConteudoEditar) {
  btnsConteudoEditar.forEach(conteudo => {
    conteudo.addEventListener('click', () => {

      if (! subdominio) {
        return
      }

      if (conteudo.dataset.conteudoTipo == 1) {
        editarTextoTitulo.value = conteudo.dataset.conteudoTitulo
        editarTextoTituloOcultar.checked = conteudo.dataset.conteudoTituloOcultar == 1

        formularioEditarTexto.action = `/${subdominio}/d/conteudo/${conteudo.dataset.conteudoId}`
        modalConteudoTextoEditar.showModal()

        const dataConteudo = conteudo.dataset.conteudoConteudo
        const holderConteudo = 'editorjs-conteudo-editar'

        iniciarEditor(holderConteudo, dataConteudo)

        formularioEditarTexto.removeEventListener('submit', enviarFormulario)
        formularioEditarTexto.addEventListener('submit', enviarFormulario)

        function enviarFormulario(event) {
          event.preventDefault()

          editor
            .save()
            .then((outputData) => {
              console.log(outputData)
              const inputConteudo = formularioEditarTexto.querySelector('.input-conteudo-editar')
              inputConteudo.value = JSON.stringify(outputData)

              formularioEditarTexto.submit()
            })
            .catch((error) => {
              console.error('Erro ao salvar o conteúdo: ', error)
            })
        }
      }
      else if (conteudo.dataset.conteudoTipo == 2) {
        editarImagemTitulo.value = conteudo.dataset.conteudoTitulo
        conteudo.dataset.conteudoTituloOcultar == 1 ? editarImagemTituloOcultar.checked = true : editarImagemTituloOcultar.checked = false
        formularioEditarImagem.action = `/${subdominio}/d/conteudo/${conteudo.dataset.conteudoId}`

        const imgElemento = modalConteudoImagemEditar.querySelector('img')

        imgElemento.src = `${conteudo.dataset.conteudoUrl}`
        imgElemento.classList.add('opacity-100')
        imgElemento.classList.remove('opacity-0')
        modalConteudoImagemEditar.showModal()
        imagemAtual = conteudo.dataset.conteudoUrl

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
            imagemParaUpload = anexo
          }
        })

        editarTextoImagemEscolher.textContent = 'Alterar imagem'
      }
      else if (conteudo.dataset.conteudoTipo == 3) {
        editarVideoTitulo.value = conteudo.dataset.conteudoTitulo
        conteudo.dataset.conteudoTituloOcultar == 1 ? editarVideoTituloOcultar.checked = true : editarVideoTituloOcultar.checked = false
        editarVideoUrl.value = conteudo.dataset.conteudoUrl
        formularioEditarVideo.action = `/${subdominio}/d/conteudo/${conteudo.dataset.conteudoId}`

        modalConteudoVideoEditar.showModal()
      }
    })
  })
}

if (formularioEditarImagem) {
  formularioEditarImagem.addEventListener('submit', async (event) => {
    event.preventDefault()

    const empresaId = formularioEditarImagem.dataset.empresaId
    const artigoId = formularioEditarImagem.dataset.artigoId
    const btnEditar = formularioEditarImagem.querySelector('.modal-conteudo-imagem-btn-enviar')
    const inputUrlImagem = formularioEditarImagem.querySelector('.url-imagem')

    if (empresaId == undefined || artigoId == undefined || btnEditar == undefined || imagemAtual == undefined) {
      return
    }

    if (imagemParaUpload && imagemAtual) {
      btnEditar.disabled = true

      const downloadURL = await substituirImagem(empresaId, artigoId, imagemParaUpload, imagemAtual)

      if (downloadURL) {
        inputUrlImagem.value = downloadURL
        formularioEditarImagem.submit()
      }

      btnEditar.disabled = false
    }
    else {
      inputUrlImagem.value = imagemAtual
      formularioEditarImagem.submit()
    }
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
  if (!modalConteudoImagemEditar) {
    return
  }

  modalConteudoImagemEditar.querySelector('img').src = ''
  modalConteudoImagemEditar.close()
}