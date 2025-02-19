const mostrarLoader = () => {
  const efeitoLoader = document.querySelector('.efeito-loader-min');
  efeitoLoader.classList.remove('hidden');
}

const ocultarLoader = () => {
  const efeitoLoader = document.querySelector('.efeito-loader-min');
  efeitoLoader.classList.add('hidden');
}

const alterarImagemMobileInicio = () => {
  const editarImagemMobileEscolher = document.querySelector('.inicio-editar-foto-mobile-escolher')

  if (! editarImagemMobileEscolher) {
    return
  }

  editarImagemMobileEscolher.click()
}

const mostrarImagemMobileInicio = async (event) => {
  const anexo = event.target.files[0]
  const imgElemento = document.querySelector('.inicio-alterar-foto-mobile')
  const msgErroImagem = document.querySelector('.erro-inicio-foto-mobile')
  const inputFile = event.target;

  if (! anexo) {
    return
  }

  mostrarLoader()

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
    inputFile.disabled = true;
    const webpBlob = await imagem2Webp(anexo);
    const webpFile = new File([webpBlob], 'nova-imagem.webp', { type: 'image/webp' });
    const dataTransfer = new DataTransfer();
    const webpURL = URL.createObjectURL(webpBlob);

    dataTransfer.items.add(webpFile);
    event.target.files = dataTransfer.files;
    imgElemento.src = webpURL;
    imgElemento.classList.remove('hidden');
  }
  catch (error) {
    msgErroImagem.textContent = error.message;
    msgErroImagem.dataset.sucesso = 'false';
    msgErroImagem.classList.remove('hidden');
  }

  inputFile.disabled = false
  ocultarLoader()
}

const removerFotoMobileInicio = () => {

  fetch(`/d/ajustes/foto_inicio?tipo=mobile`, { method: 'DELETE' })
  .then(response => response.json())
  .then(data => {

    if (data.erro) {
      throw new Error(data.erro)
    }

    window.location.href = `/dashboard/ajustes`
  })
  .catch(error => {
    console.log(error)
    window.location.href = `/dashboard/ajustes`
  })
}

const alterarImagemDesktopInicio = () => {
  const editarImagemDesktopEscolher = document.querySelector('.inicio-editar-foto-desktop-escolher')

  if (! editarImagemDesktopEscolher) {
    return
  }

  editarImagemDesktopEscolher.click()
}

const mostrarImagemDesktopInicio = async (event) => {
  const anexo = event.target.files[0]
  const imgElemento = document.querySelector('.inicio-alterar-foto-desktop')
  const msgErroImagem = document.querySelector('.erro-inicio-foto-desktop')
  const inputFile = event.target;

  if (! anexo) {
    return
  }

  mostrarLoader()

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
    inputFile.disabled = true;
    const webpBlob = await imagem2Webp(anexo);
    const webpFile = new File([webpBlob], 'nova-imagem.webp', { type: 'image/webp' });
    const dataTransfer = new DataTransfer();
    const webpURL = URL.createObjectURL(webpBlob);

    dataTransfer.items.add(webpFile);
    event.target.files = dataTransfer.files;
    imgElemento.src = webpURL;
    imgElemento.classList.remove('hidden');
  }
  catch (error) {
    msgErroImagem.textContent = error.message;
    msgErroImagem.dataset.sucesso = 'false';
    msgErroImagem.classList.remove('hidden');
  }

  inputFile.disabled = false
  ocultarLoader()
}

const removerFotoDesktopInicio = () => {

  fetch(`/d/ajustes/foto_inicio?tipo=desktop`, { method: 'DELETE' })
  .then(response => response.json())
  .then(data => {

    if (data.erro) {
      throw new Error(data.erro)
    }

    window.location.href = `/dashboard/ajustes`
  })
  .catch(error => {
    console.log(error)
    window.location.href = `/dashboard/ajustes`
  })
}