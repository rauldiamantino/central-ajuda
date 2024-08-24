import { uploadImagem  } from '../../firebase.js'

const btnTextoAdicionar = document.querySelector('.conteudo-btn-texto-adicionar')
const btnImagemAdicionar = document.querySelector('.conteudo-btn-imagem-adicionar')
const btnVideoAdicionar = document.querySelector('.conteudo-btn-video-adicionar')

const modalConteudoTextoAdicionar = document.querySelector('.modal-conteudo-texto-adicionar')
const btnCancelarModalAdicionarTexto = document.querySelector('.modal-texto-adicionar-btn-cancelar')

const modalConteudoImagemAdicionar = document.querySelector('.modal-conteudo-imagem-adicionar')
const adicionarImagemEscolher = document.querySelector('.conteudo-adicionar-imagem-escolher')
const adicionarTextoImagemEscolher = document.querySelector('.conteudo-txt-imagem-adicionar-escolher')
const btnAdicionarImagemEscolher = document.querySelector('.conteudo-btn-imagem-adicionar-escolher')
const btnCancelarModalAdicionarImagem = document.querySelector('.modal-conteudo-imagem-btn-cancelar-adicionar')

const modalConteudoVideoAdicionar = document.querySelector('.modal-conteudo-video-adicionar')
const btnCancelarModalAdicionarVideo = document.querySelector('.modal-conteudo-video-btn-cancelar-adicionar')

if (btnTextoAdicionar) {
  btnTextoAdicionar.addEventListener('click', () => {
    modalConteudoTextoAdicionar.showModal()
  })
}

if (btnImagemAdicionar) {
  btnImagemAdicionar.addEventListener('click', () => {
    const imgElemento = modalConteudoImagemAdicionar.querySelector('img')
    const inputUrlImagem = modalConteudoImagemAdicionar.querySelector('.url-imagem')

    imgElemento.classList.add('opacity-100')
    imgElemento.classList.remove('opacity-0')
    modalConteudoImagemAdicionar.showModal()

    if (btnAdicionarImagemEscolher) {
      btnAdicionarImagemEscolher.addEventListener('click', () => {
        adicionarImagemEscolher.click()
      })
    }

    adicionarImagemEscolher.addEventListener('change', async (event) => {
      const anexo = event.target.files[0]
      const blocoImagem = modalConteudoImagemAdicionar.querySelector('.bloco-imagem')

      if (anexo) {
        const objetoReader = new FileReader()

        objetoReader.onload = (e) => {
          imgElemento.src = e.target.result
          blocoImagem.classList.remove('hidden')
        }

        adicionarTextoImagemEscolher.textContent = anexo.name
        objetoReader.readAsDataURL(anexo)

        try {
          const downloadURL = await uploadImagem(anexo)

          inputUrlImagem.value = downloadURL
          console.log('URL da imagem:', downloadURL)
        } 
        catch (error) {
          console.error('Erro ao obter a URL da imagem:', error)
        }
      }
    })

    adicionarTextoImagemEscolher.textContent = 'Escolher imagem'
  })
}

if (btnVideoAdicionar) {
  btnVideoAdicionar.addEventListener('click', () => {
    modalConteudoVideoAdicionar.showModal()
  })
}

if (btnCancelarModalAdicionarTexto) {
  btnCancelarModalAdicionarTexto.addEventListener('click', () => {
    modalConteudoTextoAdicionar.close()
  })
}

if (btnCancelarModalAdicionarVideo) {
  btnCancelarModalAdicionarVideo.addEventListener('click', () => {
    modalConteudoVideoAdicionar.close()
  })
}

if (btnCancelarModalAdicionarImagem) {
  btnCancelarModalAdicionarImagem.addEventListener('click', () => fecharModalAdicionarImagem())
}

document.addEventListener('keydown', (event) => {
  
  if (event.key === 'Escape' || event.keyCode === 27 && modalConteudoImagemAdicionar.open) {
    fecharModalAdicionarImagem()
  }
})

function fecharModalAdicionarImagem() {

  if (! modalConteudoImagemAdicionar) {
    return
  }
  
  modalConteudoImagemAdicionar.querySelector('img').src = ''
  modalConteudoImagemAdicionar.close()
}