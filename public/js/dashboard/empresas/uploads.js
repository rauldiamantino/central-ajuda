const alterarLogo = () => {
  const editarImagemEscolher = document.querySelector('.empresa-editar-imagem-escolher')

  if (! editarImagemEscolher) {
    return
  }

  editarImagemEscolher.click()
}

const alterarFavicon = () => {
  const editarFaviconEscolher = document.querySelector('.empresa-editar-favicon-escolher')

  if (! editarFaviconEscolher) {
    return
  }

  editarFaviconEscolher.click()
}

const mostrarImagemLogo = (event) => {
  const anexo = event.target.files[0]
  const editarTextoImagemEscolher = document.querySelector('.empresa-txt-imagem-editar-escolher')
  const imgElemento = document.querySelector('.empresa-alterar-logo')
  const msgErroImagem = document.querySelector('.erro-empresa-imagem')
  const campoUrlImagem = document.querySelector('.empresa-editar-imagem-escolher')

  if (!anexo) {
    return
  }

  const formatosPermitidos = ['image/jpeg', 'image/png', 'image/svg+xml']
  msgErroImagem.dataset.sucesso = 'true'
  msgErroImagem.textContent = ''

  if (!formatosPermitidos.includes(anexo.type)) {
    msgErroImagem.textContent = 'Escolha um arquivo .svg, .png ou .jpg.'
    msgErroImagem.dataset.sucesso = 'false'
    msgErroImagem.classList.remove('hidden')
    return
  } else {
    msgErroImagem.classList.add('hidden')
  }

  const tamanhoMaximoMB = 2
  const tamanhoMaximoBytes = tamanhoMaximoMB * 1024 * 1024

  if (anexo.size > tamanhoMaximoBytes) {
    msgErroImagem.textContent = 'Tamanho de imagem excede o limite de 2MB.'
    msgErroImagem.dataset.sucesso = 'false'
    msgErroImagem.classList.remove('hidden')
    return
  } else {
    msgErroImagem.classList.add('hidden')
  }

  // Exibe a imagem para o usuário
  const imagem = new Image()
  imagem.src = URL.createObjectURL(anexo)

  const objetoReader = new FileReader()
  objetoReader.onload = (e) => {
    imgElemento.src = e.target.result
    imgElemento.classList.remove('hidden')
  }

  // Usando FileReader para gerar a URL da imagem (não para o input)
  objetoReader.readAsDataURL(anexo)

  // Armazenando o arquivo, mas sem alterar o campo input
  imagemParaUpload = anexo

  // Atualize a interface, mas sem tentar mudar o valor do input file
  editarTextoImagemEscolher.textContent = 'Imagem escolhida'
}

const mostrarImagemFavicon = (event) => {
  const anexo = event.target.files[0]
  const editarTextoImagemEscolher = document.querySelector('.empresa-txt-favicon-editar-escolher')
  const imgElemento = document.querySelector('.empresa-alterar-favicon')
  const msgErroImagem = document.querySelector('.erro-empresa-favicon')
  const campoUrlImagem = document.querySelector('.empresa-editar-favicon-escolher')

  if (!anexo) {
    return
  }

  const formatosPermitidos = ['image/jpeg', 'image/png', 'image/svg+xml']
  msgErroImagem.dataset.sucesso = 'true'
  msgErroImagem.textContent = ''

  if (!formatosPermitidos.includes(anexo.type)) {
    msgErroImagem.textContent = 'Escolha um arquivo .svg, .png ou .jpg.'
    msgErroImagem.dataset.sucesso = 'false'
    msgErroImagem.classList.remove('hidden')
    return
  } else {
    msgErroImagem.classList.add('hidden')
  }

  const tamanhoMaximoMB = 2
  const tamanhoMaximoBytes = tamanhoMaximoMB * 1024 * 1024

  if (anexo.size > tamanhoMaximoBytes) {
    msgErroImagem.textContent = 'Tamanho de imagem excede o limite de 2MB.'
    msgErroImagem.dataset.sucesso = 'false'
    msgErroImagem.classList.remove('hidden')
    return
  } else {
    msgErroImagem.classList.add('hidden')
  }

  // Exibe a imagem para o usuário
  const imagem = new Image()
  imagem.src = URL.createObjectURL(anexo)

  const objetoReader = new FileReader()
  objetoReader.onload = (e) => {
    imgElemento.src = e.target.result
    imgElemento.classList.remove('hidden')
  }

  // Usando FileReader para gerar a URL da imagem (não para o input)
  objetoReader.readAsDataURL(anexo)

  // Armazenando o arquivo, mas sem alterar o campo input
  imagemParaUpload = anexo

  // Atualize a interface, mas sem tentar mudar o valor do input file
  editarTextoImagemEscolher.textContent = 'Imagem escolhida'
}
