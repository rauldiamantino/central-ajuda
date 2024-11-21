import { substituirImagem } from '../../firebase/funcoes.js'
import { editorInstances } from '../../ckeditor.js'

export const editarTexto = (botaoAbrirModal) => {
  const conteudoId = botaoAbrirModal.dataset.conteudoId
  const conteudoTipo = botaoAbrirModal.dataset.conteudoTipo

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
  const botaoRemover = formularioEditarTexto.querySelector('.js-dashboard-conteudo-remover')

  // Necessário para remover
  botaoRemover.dataset.conteudoId = conteudoId
  botaoRemover.dataset.conteudoTipo = conteudoTipo

  const editor = editorInstances[conteudoId];

  if (editor) {
    // Atribuindo o conteúdo ao editor
    editor.setData(conteudoEditarTexto.dataset.conteudo);
  } else {
    console.error('Editor não encontrado para o conteúdoId:', conteudoId);
  }

  setTimeout(() => {
    formularioEditarTexto.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
  const novaDivEditarVideo = document.querySelector('.container-conteudo-video-editar[data-conteudo-id="' + conteudoId + '"]')

  if (! conteudoId) {
    return
  }

  if (! novaDivEditarVideo) {
    return
  }

  const divPrevisualizar = botaoAbrirModal.closest('.div-pai-conteudo-editar').querySelector('.bloco-editar-conteudo-video[data-conteudo-id="' + conteudoId + '"]')

  if (! divPrevisualizar) {
    return
  }

  divPrevisualizar.classList.toggle('hidden')
  novaDivEditarVideo.classList.toggle('hidden')

  const formularioEditarVideo = novaDivEditarVideo.querySelector('form')

  setTimeout(() => {
    formularioEditarVideo.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }, 50);
}

export const fecharEditarVideo = (botaoFecharModal) => {
  const conteudoId = botaoFecharModal.dataset.conteudoId
  const novaDivEditarVideo = document.querySelector('.container-conteudo-video-editar[data-conteudo-id="' + conteudoId + '"]')

  if (! conteudoId) {
    return
  }

  if (! novaDivEditarVideo) {
    return
  }

  const divPrevisualizar = botaoFecharModal.closest('.div-pai-conteudo-editar').querySelector('.bloco-editar-conteudo-video[data-conteudo-id="' + conteudoId + '"]')

  if (! divPrevisualizar) {
    return
  }

  divPrevisualizar.classList.toggle('hidden')
  novaDivEditarVideo.classList.toggle('hidden')
}

export const editarImagem = (botaoAbrirModal) => {
  const conteudoId = botaoAbrirModal.dataset.conteudoId
  const novaDivEditarImagem = document.querySelector('.container-conteudo-imagem-editar[data-conteudo-id="' + conteudoId + '"]')

  if (! conteudoId) {
    return
  }

  if (! novaDivEditarImagem) {
    return
  }

  const divPrevisualizar = botaoAbrirModal.closest('.div-pai-conteudo-editar').querySelector('.bloco-editar-conteudo-imagem[data-conteudo-id="' + conteudoId + '"]')

  if (! divPrevisualizar) {
    return
  }

  divPrevisualizar.classList.toggle('hidden')
  novaDivEditarImagem.classList.toggle('hidden')

  const formularioEditarImagem = novaDivEditarImagem.querySelector('form')

  setTimeout(() => {
    formularioEditarImagem.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }, 50);

  alterarImagem(novaDivEditarImagem)
}

export const fecharEditarImagem = (botaoFecharModal) => {
  const conteudoId = botaoFecharModal.dataset.conteudoId
  const novaDivEditarImagem = document.querySelector('.container-conteudo-imagem-editar[data-conteudo-id="' + conteudoId + '"]')

  if (! conteudoId) {
    return
  }

  if (! novaDivEditarImagem) {
    return
  }

  const divPrevisualizar = botaoFecharModal.closest('.div-pai-conteudo-editar').querySelector('.bloco-editar-conteudo-imagem[data-conteudo-id="' + conteudoId + '"]')

  if (! divPrevisualizar) {
    return
  }

  divPrevisualizar.classList.toggle('hidden')
  novaDivEditarImagem.classList.toggle('hidden')
}

export const alterarImagem = (containerImagem) => {
  let imagemParaUpload = null
  let imagemAtual = null

  const formularioEditarImagem = containerImagem.querySelector('form')
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