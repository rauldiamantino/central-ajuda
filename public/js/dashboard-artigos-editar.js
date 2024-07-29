const btnTextoAdicionar = document.querySelector('.conteudo-btn-texto-adicionar')
const btnImagemAdicionar = document.querySelector('.conteudo-btn-imagem-adicionar')
const btnVideoAdicionar = document.querySelector('.conteudo-btn-video-adicionar')

const textoAdicionar = document.querySelector('.conteudo-texto-adicionar')
const imagemAdicionar = document.querySelector('.conteudo-imagem-adicionar')
const videoAdicionar = document.querySelector('.conteudo-video-adicionar')

if (btnTextoAdicionar) {
  btnTextoAdicionar.addEventListener('click', () => {
    
    if (textoAdicionar.classList.contains('hidden')) {
      textoAdicionar.classList.remove('hidden')
      imagemAdicionar.classList.add('hidden')
      videoAdicionar.classList.add('hidden')
    }
    else {
      textoAdicionar.classList.add('hidden')
    }
  })
}

if (btnImagemAdicionar) {
  btnImagemAdicionar.addEventListener('click', () => {

    if (imagemAdicionar.classList.contains('hidden')) {
      imagemAdicionar.classList.remove('hidden')
      textoAdicionar.classList.add('hidden')
      videoAdicionar.classList.add('hidden')
    }
    else {
      imagemAdicionar.classList.add('hidden')
    }
  })
}

if (btnVideoAdicionar) {
  btnVideoAdicionar.addEventListener('click', () => {
    
    if (videoAdicionar.classList.contains('hidden')) {
      videoAdicionar.classList.remove('hidden')
      imagemAdicionar.classList.add('hidden')
      textoAdicionar.classList.add('hidden')
    }
    else {
      videoAdicionar.classList.add('hidden')
    }
  })
}