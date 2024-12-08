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

const mostrarImagemLogo = async (event) => {
  const anexo = event.target.files[0]
  const editarTextoImagemEscolher = document.querySelector('.empresa-txt-imagem-editar-escolher')
  const imgElemento = document.querySelector('.empresa-alterar-logo')
  const msgErroImagem = document.querySelector('.erro-empresa-imagem')

  if (! anexo) {
    return
  }

  const formatosPermitidos = ['image/jpeg', 'image/png', 'image/svg+xml']
  msgErroImagem.dataset.sucesso = 'true'
  msgErroImagem.textContent = ''

  if (! formatosPermitidos.includes(anexo.type)) {
    msgErroImagem.textContent = 'Escolha um arquivo .svg, .png ou .jpg.'
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

  try {
    const webpBlob = await imagem2Webp(anexo);
    const webpFile = new File([webpBlob], 'nova-imagem.webp', { type: 'image/webp' });
    const dataTransfer = new DataTransfer();
    const webpURL = URL.createObjectURL(webpBlob);

    dataTransfer.items.add(webpFile);
    event.target.files = dataTransfer.files;
    imgElemento.src = webpURL;
    imgElemento.classList.remove('hidden');
    editarTextoImagemEscolher.textContent = 'Imagem escolhida';
  }
  catch (error) {
    msgErroImagem.textContent = 'Erro ao processar a imagem: ' + error.message;
    msgErroImagem.dataset.sucesso = 'false';
    msgErroImagem.classList.remove('hidden');
  }
}

const mostrarImagemFavicon = async (event) => {
  const anexo = event.target.files[0]
  const editarTextoImagemEscolher = document.querySelector('.empresa-txt-favicon-editar-escolher')
  const imgElemento = document.querySelector('.empresa-alterar-favicon')
  const msgErroImagem = document.querySelector('.erro-empresa-favicon')

  if (! anexo) {
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

  try {
    const webpBlob = await imagem2Webp(anexo);
    const webpFile = new File([webpBlob], 'nova-imagem.webp', { type: 'image/webp' });
    const dataTransfer = new DataTransfer();
    const webpURL = URL.createObjectURL(webpBlob);

    dataTransfer.items.add(webpFile);
    event.target.files = dataTransfer.files;
    imgElemento.src = webpURL;
    imgElemento.classList.remove('hidden');
    editarTextoImagemEscolher.textContent = 'Imagem escolhida';
  }
  catch (error) {
    msgErroImagem.textContent = 'Erro ao processar a imagem: ' + error.message;
    msgErroImagem.dataset.sucesso = 'false';
    msgErroImagem.classList.remove('hidden');
  }
}
