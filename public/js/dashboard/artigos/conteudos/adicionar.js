import { uploadImagem } from '../../firebase/funcoes.js'

const formularioAdicionarImagem = document.querySelector('.modal-conteudo-imagem-adicionar > form')

if (formularioAdicionarImagem) {
  let imagemEscolhida = null
  const imgElemento = formularioAdicionarImagem.querySelector('img')
  const adicionarImagemEscolher = document.querySelector('.conteudo-adicionar-imagem-escolher')
  const adicionarTextoImagemEscolher = document.querySelector('.conteudo-txt-imagem-adicionar-escolher')
  const btnAdicionarImagemEscolher = document.querySelector('.conteudo-btn-imagem-adicionar-escolher')

  imgElemento.classList.add('opacity-100')
  imgElemento.classList.remove('opacity-0')

  if (btnAdicionarImagemEscolher) {
    btnAdicionarImagemEscolher.addEventListener('click', () => {
      adicionarImagemEscolher.click()
    })
  }

  adicionarImagemEscolher.addEventListener('change', (event) => {
    const anexo = event.target.files[0]
    const blocoImagem = formularioAdicionarImagem.querySelector('.bloco-imagem')

    if (anexo) {
      const objetoReader = new FileReader()

      objetoReader.onload = (e) => {
        imgElemento.src = e.target.result
        blocoImagem.classList.remove('hidden')
      }

      adicionarTextoImagemEscolher.textContent = anexo.name
      objetoReader.readAsDataURL(anexo)

      imagemEscolhida = anexo // Armazena a imagem escolhida
    }
  })

  adicionarTextoImagemEscolher.textContent = 'Escolher imagem'

  formularioAdicionarImagem.addEventListener('submit', async (event) => {
    event.preventDefault()

    const empresaId = formularioAdicionarImagem.dataset.empresaId
    const artigoId = formularioAdicionarImagem.dataset.artigoId
    const btnAdicionar = formularioAdicionarImagem.querySelector('.modal-conteudo-imagem-btn-enviar')

    if (empresaId == undefined || artigoId == undefined || imagemEscolhida == null || btnAdicionar == undefined) {
      return
    }

    btnAdicionar.disabled = true

    const downloadURL = await uploadImagem(empresaId, artigoId, imagemEscolhida)

    if (downloadURL !== false) {
      const inputUrlImagem = formularioAdicionarImagem.querySelector('.url-imagem')

      inputUrlImagem.value = downloadURL
      console.log('URL da imagem:', downloadURL)

      formularioAdicionarImagem.submit()
    }

    btnAdicionar.disabled = false
  })
}