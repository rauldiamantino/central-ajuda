import { substituirImagem } from '../../firebase/funcoes.js'
import { editorInstances } from '../../ckeditor.js'

export const editarTexto = (botaoAbrirModal) => {
  const conteudoId = botaoAbrirModal.dataset.conteudoId
  const novaDivEditarTexto = document.querySelector('.container-conteudo-texto-editar[data-conteudo-id="' + conteudoId + '"]')

  if (! conteudoId) {
    return
  }

  if (! novaDivEditarTexto) {
    return
  }

  const divPrevisualizar = botaoAbrirModal.closest('.div-pai-conteudo-editar').querySelector('.bloco-editar-conteudo-texto[data-conteudo-id="' + conteudoId + '"]')

  if (! divPrevisualizar) {
    return
  }

  divPrevisualizar.classList.toggle('hidden')
  novaDivEditarTexto.classList.toggle('hidden')

  const formularioEditarTexto = novaDivEditarTexto.querySelector('form')
  const conteudoEditarTexto = formularioEditarTexto.querySelector('.conteudo-texto-editar')
  const editor = editorInstances[conteudoId];

  if (editor) {
    // Atribuindo o conteúdo ao editor
    editor.setData(conteudoEditarTexto.dataset.conteudo);
  } else {
    console.error('Editor não encontrado para o conteúdoId:', conteudoId);
  }

  setTimeout(() => {
    formularioEditarTexto.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }, 50);
}

export const fecharEditarTexto = (botaoFecharModal) => {
  const conteudoId = botaoFecharModal.dataset.conteudoId
  const novaDivEditarTexto = document.querySelector('.container-conteudo-texto-editar[data-conteudo-id="' + conteudoId + '"]')

  if (! conteudoId) {
    return
  }

  if (! novaDivEditarTexto) {
    return
  }

  const divPrevisualizar = botaoFecharModal.closest('.div-pai-conteudo-editar').querySelector('.bloco-editar-conteudo-texto[data-conteudo-id="' + conteudoId + '"]')

  if (! divPrevisualizar) {
    return
  }

  divPrevisualizar.classList.toggle('hidden')
  novaDivEditarTexto.classList.toggle('hidden')
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
