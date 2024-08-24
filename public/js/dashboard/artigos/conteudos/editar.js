import { editorInstances } from '../../ckeditor.js'
import { uploadImageToFirebase  } from '../../firebase.js'

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

if (btnsConteudoEditar) {
  btnsConteudoEditar.forEach(conteudo => {
    conteudo.addEventListener('click', () => {
      
      if (conteudo.dataset.conteudoTipo == 1) {
        editarTextoTitulo.value = conteudo.dataset.conteudoTitulo
        conteudo.dataset.conteudoTituloOcultar == 1 ? editarTextoTituloOcultar.checked = true : editarTextoTituloOcultar.checked = false

        const editor = editorInstances['conteudo']

        if (editor) {
          editor.setData(conteudo.dataset.conteudoConteudo)
        }
        else {
          console.error('CKEditor instance not found for the specified textarea.')
        }
        
        formularioEditarTexto.action = `/conteudo/${conteudo.dataset.conteudoId}`
        modalConteudoTextoEditar.showModal()
      }
      else if (conteudo.dataset.conteudoTipo == 2) {
        editarImagemTitulo.value = conteudo.dataset.conteudoTitulo
        conteudo.dataset.conteudoTituloOcultar == 1 ? editarImagemTituloOcultar.checked = true : editarImagemTituloOcultar.checked = false
        formularioEditarImagem.action = `/conteudo/${conteudo.dataset.conteudoId}`
        
        const imgElemento = modalConteudoImagemEditar.querySelector('img')
        const inputUrlImagem = modalConteudoImagemEditar.querySelector('.url-imagem')

        imgElemento.src = `${conteudo.dataset.conteudoUrl}`
        imgElemento.classList.add('opacity-100')
        imgElemento.classList.remove('opacity-0')
        modalConteudoImagemEditar.showModal()

        if (btnEditarImagemEscolher) {
          btnEditarImagemEscolher.addEventListener('click', () => {
            editarImagemEscolher.click()
          })
        }

        editarImagemEscolher.addEventListener('change', async (event) => {
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

            try {
              const downloadURL = await uploadImageToFirebase(anexo)

              inputUrlImagem.value = downloadURL
              console.log('URL da imagem:', downloadURL)
            } 
            catch (error) {
              console.error('Erro ao obter a URL da imagem:', error)
            }
          }
        })

        editarTextoImagemEscolher.textContent = 'Alterar imagem'
      }
      else if (conteudo.dataset.conteudoTipo == 3) {
        editarVideoTitulo.value = conteudo.dataset.conteudoTitulo
        conteudo.dataset.conteudoTituloOcultar == 1 ? editarVideoTituloOcultar.checked = true : editarVideoTituloOcultar.checked = false
        editarVideoUrl.value = conteudo.dataset.conteudoUrl
        formularioEditarVideo.action = `/conteudo/${conteudo.dataset.conteudoId}`
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

  if (! modalConteudoImagemEditar) {
    return
  }
  
  modalConteudoImagemEditar.querySelector('img').src = ''
  modalConteudoImagemEditar.close()
}