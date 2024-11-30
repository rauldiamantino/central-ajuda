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

  const editor = editorInstances[conteudoId]

  if (editor) {
    // Atribuindo o conteúdo ao editor
    editor.setData(conteudoEditarTexto.dataset.conteudo)
  } else {
    console.error('Editor não encontrado para o conteúdoId:', conteudoId)
  }

  setTimeout(() => {
    formularioEditarTexto.scrollIntoView({ behavior: 'smooth', block: 'center' })
  }, 50)
}

export const fecharEditarTexto = (botaoFecharModal) => {
  const conteudoId = botaoFecharModal.dataset.conteudoId
  const novaDivEditarTexto = document.querySelector('.container-conteudo-texto-editar[data-conteudo-id="' + conteudoId + '"]')

  if (! conteudoId || ! novaDivEditarTexto) {
    return Promise.resolve(false)
  }

  const divPrevisualizar = botaoFecharModal.closest('.div-pai-conteudo-editar').querySelector('.bloco-editar-conteudo-texto[data-conteudo-id="' + conteudoId + '"]')

  if (!divPrevisualizar) {
    return Promise.resolve(false)
  }

  if (novaDivEditarTexto.classList.contains('hidden')) {
    return Promise.resolve(true)
  }

  const modal = document.querySelector('.modal-conteudo-fechar')
  const btnContinuar = modal.querySelector('.modal-conteudo-btn-continuar')
  const btnFechar = modal.querySelector('.modal-conteudo-btn-fechar')

  if (! modal || ! btnContinuar || ! btnFechar) {
    console.error('Erro: Modal ou botões não encontrados.')
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
      novaDivEditarTexto.classList.add('hidden')
      divPrevisualizar.classList.remove('hidden')
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
    formularioEditarVideo.scrollIntoView({ behavior: 'smooth', block: 'center' })
  }, 50)
}

export const fecharEditarVideo = (botaoFecharModal) => {
  const conteudoId = botaoFecharModal.dataset.conteudoId
  const novaDivEditarVideo = document.querySelector('.container-conteudo-video-editar[data-conteudo-id="' + conteudoId + '"]')

  if (! conteudoId || ! novaDivEditarVideo) {
    return Promise.resolve(false)
  }

  const divPrevisualizar = botaoFecharModal.closest('.div-pai-conteudo-editar').querySelector('.bloco-editar-conteudo-video[data-conteudo-id="' + conteudoId + '"]')

  if (! divPrevisualizar) {
    return Promise.resolve(false)
  }

  if (novaDivEditarVideo.classList.contains('hidden')) {
    return Promise.resolve(true)
  }

  const modal = document.querySelector('.modal-conteudo-fechar')
  const btnContinuar = modal.querySelector('.modal-conteudo-btn-continuar')
  const btnFechar = modal.querySelector('.modal-conteudo-btn-fechar')

  if (! modal || ! btnContinuar || ! btnFechar) {
    console.error("Erro: Modal ou botões não encontrados.")
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
      novaDivEditarVideo.classList.add('hidden')
      divPrevisualizar.classList.remove('hidden')
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
    formularioEditarImagem.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }, 50)
}

export const fecharEditarImagem = (botaoFecharModal) => {
  const conteudoId = botaoFecharModal.dataset.conteudoId
  const novaDivEditarImagem = document.querySelector('.container-conteudo-imagem-editar[data-conteudo-id="' + conteudoId + '"]')

  if (! conteudoId || ! novaDivEditarImagem) {
    return Promise.resolve(false)
  }

  const divPrevisualizar = botaoFecharModal.closest('.div-pai-conteudo-editar').querySelector('.bloco-editar-conteudo-imagem[data-conteudo-id="' + conteudoId + '"]')

  if (!divPrevisualizar) {
    return Promise.resolve(false)
  }

  if (novaDivEditarImagem.classList.contains('hidden')) {
    return Promise.resolve(true)
  }

  const modal = document.querySelector('.modal-conteudo-fechar')
  const btnContinuar = modal.querySelector('.modal-conteudo-btn-continuar')
  const btnFechar = modal.querySelector('.modal-conteudo-btn-fechar')

  if (! modal || ! btnContinuar || ! btnFechar) {
    console.error('Erro: Modal ou botões não encontrados.')
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
      novaDivEditarImagem.classList.add('hidden')
      divPrevisualizar.classList.remove('hidden')
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