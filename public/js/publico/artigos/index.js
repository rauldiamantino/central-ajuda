const enviarFeedback = (artigoId, feedback) => {
  const btnFeedback1 = document.querySelector('.btn-feedback[data-feedback="1"]')
  const btnFeedback0 = document.querySelector('.btn-feedback[data-feedback="0"]')
  const containerInicio = document.querySelector('.container-feedback-inicio')
  const containerFinal = document.querySelector('.container-feedback-final')
  const efeitoLoader = document.querySelector('.efeito-loader')

  if (! btnFeedback1) {
    return
  }

  if (! btnFeedback0) {
    return
  }

  if (! containerInicio) {
    return
  }

  if (! containerFinal) {
    return
  }

  if (! efeitoLoader) {
    return
  }

  const mensagemSucesso = containerFinal.querySelector('.sucesso')
  const mensagemErro = containerFinal.querySelector('.erro')

  if (! mensagemSucesso) {
    return
  }

  if (! mensagemErro) {
    return
  }

  containerInicio.classList.add('hidden')
  containerFinal.classList.remove('hidden')
  efeitoLoader.classList.remove('hidden')
  mensagemErro.classList.add('hidden')
  mensagemSucesso.classList.add('hidden')

  fetch('/feedback', {
    method: 'POST',
    headers: {
      'X-Requested-With': 'fetch',
    },
    body: new URLSearchParams({
      artigo_id: artigoId,
      util: feedback
    })
  })
    .then(resposta => resposta.json())
    .then(async resposta => {
      containerInicio.classList.add('hidden')
      containerFinal.classList.remove('hidden')

      await delay(1000)

      if (resposta.erro) {
        mensagemErro.classList.remove('hidden')
        containerInicio.classList.remove('hidden')
      }
      else {
        mensagemSucesso.classList.remove('hidden')
      }

      efeitoLoader.classList.add('hidden')
    })
    .catch(error => {
      console.error('Erro ao enviar feedback:', error)
    })

    const delay = ms => new Promise(resolve => setTimeout(resolve, ms))

}