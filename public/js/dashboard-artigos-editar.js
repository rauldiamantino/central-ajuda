const btnTextoAdicionar = document.querySelector('.conteudo-btn-texto-adicionar')
const btnImagemAdicionar = document.querySelector('.conteudo-btn-imagem-adicionar')
const btnVideoAdicionar = document.querySelector('.conteudo-btn-video-adicionar')
const btnImagemEscolher = document.querySelector('.conteudo-btn-imagem-escolher')

const textoAdicionar = document.querySelector('.conteudo-texto-adicionar')
const imagemAdicionar = document.querySelector('.conteudo-imagem-adicionar')
const videoAdicionar = document.querySelector('.conteudo-video-adicionar')
const imagemEscolher = document.querySelector('.conteudo-imagem-escolher')

if (btnTextoAdicionar) {
  btnTextoAdicionar.addEventListener('click', () => {
    
    if (textoAdicionar.classList.contains('hidden')) {
      textoAdicionar.classList.remove('hidden')
      imagemAdicionar.classList.add('hidden')
      videoAdicionar.classList.add('hidden')

      imagemAdicionar.querySelector('input').value = ''
      videoAdicionar.querySelector('input').value = ''
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

// ----------- Adicionar bloco de conteúdo -----------
const formConteudo = document.querySelector('.form-conteudo')

formConteudo.addEventListener('submit', (event) => {
  event.preventDefault()
  fetchFormConteudo(formConteudo)
})

const fetchFormConteudo = (formConteudo) => {
  const url = '/conteudo'
  const formConteudoData = new FormData(formConteudo)

  const formTexto = document.querySelector('.conteudo-texto-adicionar > textarea[name=conteudo').value
  const formImagem = document.querySelector('.conteudo-imagem-adicionar > input[name=url').files[0]
  const formVideo = document.querySelector('.conteudo-video-adicionar > input[name=url').value
  const formOrdem = document.querySelector('.form-conteudo > input[name=ordem').value

  if (formTexto) {
    formConteudoData.append('conteudo', formTexto)
    formConteudoData.append('tipo', 1)
  }
  else if (formImagem) {
    formConteudoData.append('url', formImagem)
    formConteudoData.append('tipo', 2)
  }
  else if (formVideo) {
    formConteudoData.append('url', formVideo)
    formConteudoData.append('tipo', 3)
  }

  if (formOrdem) {
    formConteudoData.append('ordem', parseInt(formOrdem) + 1)
  }

  fetch(url, {
    method: 'POST',
    body: formConteudoData
  })
  .then(resposta => resposta.json())
  .then(resposta => {
    
    if (resposta.erro) {
      console.log(resposta)
    }
    else {
      console.log(resposta)
    }
    
    location.reload()
  })
  .catch(error => {
    console.error('Erro:', error)
  })
}