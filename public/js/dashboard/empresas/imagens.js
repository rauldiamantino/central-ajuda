import { uploadMultiplasImagens } from '../firebase/funcoes.js'

document.addEventListener('DOMContentLoaded', function () {
  const formularioEditarEmpresa = document.querySelector('.form-editar-empresa')

  if (! formularioEditarEmpresa) {
    return
  }

  const btnEditar = document.querySelector('.btn-gravar-empresa')

  // Favicon
  const editarFaviconEscolher = document.querySelector('.empresa-editar-favicon-escolher')
  const editarTextoFaviconEscolher = document.querySelector('.empresa-txt-favicon-editar-escolher')
  const faviconElemento = document.querySelector('.empresa-alterar-favicon')
  const btnAlterarFavicon = document.querySelector('.empresa-btn-favicon-editar-escolher')
  const msgErroFavicon = document.querySelector('.erro-empresa-favicon')
  let faviconParaUpload = null
  let faviconAtual = formularioEditarEmpresa.dataset.faviconAtual

  // Logo
  const editarImagemEscolher = document.querySelector('.empresa-editar-imagem-escolher')
  const editarTextoImagemEscolher = document.querySelector('.empresa-txt-imagem-editar-escolher')
  const imgElemento = document.querySelector('.empresa-alterar-logo')
  const btnAlterarImagem = document.querySelector('.empresa-btn-imagem-editar-escolher')
  const msgErroImagem = document.querySelector('.erro-empresa-imagem')
  let imagemParaUpload = null
  let imagemAtual = formularioEditarEmpresa.dataset.imagemAtual

  // Abrir os inputs de escolha de arquivo
  btnAlterarFavicon.addEventListener('click', () => {
    editarFaviconEscolher.click()
  })

  btnAlterarImagem.addEventListener('click', () => {
    editarImagemEscolher.click()
  })

  // Lógica para editar o favicon
  editarFaviconEscolher.addEventListener('change', (event) => {
    const anexo = event.target.files[0]

    if (anexo) {
      const formatosPermitidos = ['image/jpeg', 'image/png']
      msgErroFavicon.dataset.sucesso = 'true'
      msgErroFavicon.textContent = ''

      if (!formatosPermitidos.includes(anexo.type)) {
        msgErroFavicon.textContent = 'Escolha um arquivo .png ou .jpg.'
        msgErroFavicon.dataset.sucesso = 'false'
        msgErroFavicon.classList.remove('hidden')
        return
      }
      else {
        msgErroFavicon.classList.add('hidden')
      }

      const tamanhoMaximoMB = 2
      const tamanhoMaximoBytes = tamanhoMaximoMB * 1024 * 1024

      if (anexo.size > tamanhoMaximoBytes) {
        msgErroFavicon.textContent = 'Tamanho de imagem excede o limite de 2MB.'
        msgErroFavicon.dataset.sucesso = 'false'
        msgErroFavicon.classList.remove('hidden')
        return
      }
      else {
        msgErroFavicon.classList.add('hidden')
      }

      const imagem = new Image()
      imagem.src = URL.createObjectURL(anexo)

      imagem.onload = () => {
        const larguraMaxima = 48
        const alturaMaxima = 48

        if (imagem.width > larguraMaxima || imagem.height > alturaMaxima) {
          msgErroFavicon.textContent = `Envie uma imagem ${larguraMaxima}px por ${alturaMaxima}px.`
          msgErroFavicon.dataset.sucesso = 'false'
          msgErroFavicon.classList.remove('hidden')
          return
        }
        else {
          msgErroFavicon.classList.add('hidden')
        }
      }

      const objetoReader = new FileReader()

      objetoReader.onload = (e) => {
        faviconElemento.src = e.target.result
        faviconElemento.classList.remove('hidden')
      }

      editarTextoFaviconEscolher.textContent = ''
      objetoReader.readAsDataURL(anexo)
      faviconParaUpload = anexo
    }
  })

  // Lógica para editar o logo
  editarImagemEscolher.addEventListener('change', (event) => {
    const anexo = event.target.files[0]
    if (anexo) {
      const formatosPermitidos = ['image/jpeg', 'image/png']
      msgErroImagem.dataset.sucesso = 'true'
      msgErroImagem.textContent = ''

      if (! formatosPermitidos.includes(anexo.type)) {
        msgErroImagem.textContent = 'Escolha um arquivo .pnh ou .jpg.'
        msgErroImagem.dataset.sucesso = 'false'
        msgErroImagem.classList.remove('hidden')
        return
      }
      else {
        msgErroImagem.classList.add('hidden')
      }

      const tamanhoMaximoMB = 2
      const tamanhoMaximoBytes = tamanhoMaximoMB * 1024 * 1024

      if (anexo.size > tamanhoMaximoBytes) {
        msgErroImagem.textContent = 'Tamanho de imagem excede o limite de 2MB.'
        msgErroImagem.dataset.sucesso = 'false'
        msgErroImagem.classList.remove('hidden')
        return
      }
      else {
        msgErroImagem.classList.add('hidden')
      }

      const imagem = new Image()
      imagem.src = URL.createObjectURL(anexo)
      imagem.onload = () => {
        const larguraMaxima = 200
        const alturaMaxima = 70
        if (imagem.width > larguraMaxima || imagem.height > alturaMaxima) {
          msgErroImagem.textContent = `Envie uma imagem ${larguraMaxima}px por ${alturaMaxima}px.`
          msgErroImagem.dataset.sucesso = 'false'
          msgErroImagem.classList.remove('hidden')
          return
        }
        else {
          msgErroImagem.classList.add('hidden')
        }
      }

      const objetoReader = new FileReader()
      objetoReader.onload = (e) => {
        imgElemento.src = e.target.result
        imgElemento.classList.remove('hidden')
      }

      editarTextoImagemEscolher.textContent = ''
      objetoReader.readAsDataURL(anexo)
      imagemParaUpload = anexo
    }
  })

  let botaoDesativado = false

  // Envio do formulário
  if (formularioEditarEmpresa) {
    formularioEditarEmpresa.addEventListener('submit', async (event) => {
      event.preventDefault()

      const empresaId = formularioEditarEmpresa.dataset.empresaId

      if (empresaId == undefined || btnEditar == undefined || msgErroFavicon.dataset.sucesso == 'false' || msgErroImagem.dataset.sucesso == 'false') {
        return
      }

      if (botaoDesativado) {
        return
      }

      botaoDesativado = true
      btnEditar.disabled = true
      btnEditar.textContent = 'Gravando...'
      btnEditar.classList.add('opacity-50', 'cursor-not-allowed')

      let erroNoUpload = false

      try {
        const imagensParaUpload = []

        if (faviconParaUpload) {
          imagensParaUpload.push({ file: faviconParaUpload, type: 'favicon' })
        }
        else if (faviconAtual) {
          formularioEditarEmpresa.querySelector('.url-favicon').value = faviconAtual
        }

        if (imagemParaUpload) {
          imagensParaUpload.push({ file: imagemParaUpload, type: 'logo' })
        }
        else if (imagemAtual) {
          formularioEditarEmpresa.querySelector('.url-imagem').value = imagemAtual
        }

        if (imagensParaUpload.length > 0) {
          const downloadURLs = await uploadMultiplasImagens(empresaId, 0, imagensParaUpload)

          downloadURLs.forEach((url, index) => {

            if (imagensParaUpload[ index ].type === 'favicon') {
              formularioEditarEmpresa.querySelector('.url-favicon').value = url
            }
            else if (imagensParaUpload[ index ].type === 'logo') {
              formularioEditarEmpresa.querySelector('.url-imagem').value = url
            }
          })
        }
      }
      catch (error) {
        console.error('Erro no upload:', error)
        erroNoUpload = true
      }

      if (erroNoUpload) {
        btnEditar.disabled = false
        console.log("Erro no upload. Formulário não será enviado.")
        return
      }

      btnEditar.disabled = false
      formularioEditarEmpresa.submit()
    })
  }
})