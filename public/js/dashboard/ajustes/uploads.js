const alterarImagemInicio = () => {
  const editarImagemEscolher = document.querySelector('.inicio-editar-foto-escolher')

  if (! editarImagemEscolher) {
    return
  }

  editarImagemEscolher.click()
}

const mostrarImagemInicio = async (event) => {
  const anexo = event.target.files[0]
  const imgElemento = document.querySelector('.inicio-alterar-foto')
  const msgErroImagem = document.querySelector('.erro-inicio-foto')

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
  }
  catch (error) {
    msgErroImagem.textContent = error.message;
    msgErroImagem.dataset.sucesso = 'false';
    msgErroImagem.classList.remove('hidden');
  }
}

const removerFotoInicio = () => {

  fetch(`/d/ajustes/foto_inicio`, { method: 'DELETE' })
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