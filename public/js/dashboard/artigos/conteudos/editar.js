import { substituirImagem } from '../../firebase/funcoes.js'
import { editorInstances } from '../../ckeditor.js'

const efeitoLoader = document.querySelector('#efeito-loader')
const editarFundo = document.querySelector('.editar-fundo')

const formularioEditarTexto = document.querySelector('.form-conteudo-texto-editar')
const formularioEditarImagem = document.querySelector('.modal-conteudo-imagem-editar > form')
const formularioEditarVideo = document.querySelector('.modal-conteudo-video-editar > form')

if (formularioEditarTexto) {
  editarTexto()
}

if (formularioEditarImagem) {
  editarImagem()
}

if (formularioEditarVideo) {
  editarVideo()
}

// Funções
function editarTexto() {
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

function editarImagem() {
  let imagemParaUpload = null
  let imagemAtual = null

  efeitoLoader.classList.add('hidden')
  editarFundo.classList.remove('hidden')

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

function editarVideo() {
  efeitoLoader.classList.add('hidden')
  editarFundo.classList.remove('hidden')
}