import { substituirImagem } from '../../firebase/funcoes.js'
import { editorInstances } from '../../ckeditor.js'

const efeitoLoader = document.querySelector('#efeito-loader')
const editarFundo = document.querySelector('.editar-fundo')

const formularioEditarTexto = document.querySelector('.form-conteudo-texto-editar')

if (formularioEditarTexto) {
  const conteudoEditarTexto = document.querySelector('.conteudo-texto-editar')
  const verificarCKEditor = setInterval(() => {
    const editor = editorInstances['conteudo']

    if (editor) {
      editor.setData(conteudoEditarTexto.dataset.conteudo)

      efeitoLoader.classList.add('hidden')
      editarFundo.classList.remove('hidden')

      clearInterval(verificarCKEditor)
    }
  }, 100)

  setTimeout(() => clearInterval(verificarCKEditor), 5000)
}

export const editarVideo = (botaoAbrirModal) => {
  const conteudoId = botaoAbrirModal.dataset.conteudoId
  const conteudoUrl = botaoAbrirModal.dataset.conteudoUrl
  const conteudoTitulo = botaoAbrirModal.dataset.conteudoTitulo
  const conteudoTituloOcultar = botaoAbrirModal.dataset.conteudoTituloOcultar

  const modalEditarVideo = document.querySelector('.modal-conteudo-video-editar')

  if (! modalEditarVideo) {
    return
  }

  modalEditarVideo.showModal()

  const formularioEditarVideo = modalEditarVideo.querySelector('form')
  const campoTitulo = formularioEditarVideo.querySelector('#conteudo-editar-video-titulo')
  const campoTituloOcultar = formularioEditarVideo.querySelector('.conteudo-editar-video-titulo-ocultar')
  const campoVideoUrl = formularioEditarVideo.querySelector('#conteudo-editar-video-url')

  formularioEditarVideo.action = `/${empresa}/d/conteudo/${conteudoId}`
  campoTitulo.value = conteudoTitulo
  campoTituloOcultar.checked = conteudoTituloOcultar == 1
  campoVideoUrl.value = conteudoUrl
  formularioEditarVideo.addEventListener('submit', async (event) => {
    formularioEditarVideo.submit()
  })
}

export const editarImagem = (botaoAbrirModal) => {
  const conteudoId = botaoAbrirModal.dataset.conteudoId
  const conteudoUrl = botaoAbrirModal.dataset.conteudoUrl
  const conteudoTituloOcultar = botaoAbrirModal.dataset.conteudoTituloOcultar
  const conteudoTitulo = botaoAbrirModal.dataset.conteudoTitulo

  const modalEditarImagem = document.querySelector('.modal-conteudo-imagem-editar')

  if (! modalEditarImagem) {
    return
  }

  modalEditarImagem.showModal()

  let imagemParaUpload = null
  let imagemAtual = null

  const formularioEditarImagem = modalEditarImagem.querySelector('form')
  const campoTitulo = formularioEditarImagem.querySelector('#conteudo-editar-imagem-titulo')
  const campoTituloOcultar = formularioEditarImagem.querySelector('.conteudo-editar-imagem-titulo-ocultar')
  const campoImagemSrc = formularioEditarImagem.querySelector('img')

  formularioEditarImagem.action = `/${empresa}/d/conteudo/${conteudoId}`
  campoTitulo.value = conteudoTitulo
  campoTituloOcultar.checked = conteudoTituloOcultar == 1
  campoImagemSrc.src = conteudoUrl

  const editarImagemEscolher = document.querySelector('.conteudo-editar-imagem-escolher')
  const editarTextoImagemEscolher = document.querySelector('.conteudo-txt-imagem-editar-escolher')
  const btnEditarImagemEscolher = document.querySelector('.conteudo-btn-imagem-editar-escolher')
  const imgElemento = formularioEditarImagem.querySelector('img')

  imgElemento.classList.add('opacity-100')
  imgElemento.classList.remove('opacity-0')
  imagemAtual = imgElemento.src

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
