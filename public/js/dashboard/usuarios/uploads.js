const alterarImagemUsuario = () => {
  const editarImagemEscolher = document.querySelector('.usuario-editar-foto-escolher')

  if (! editarImagemEscolher) {
    return
  }

  editarImagemEscolher.click()
}

const mostrarImagemUsuario = (event) => {
  console.log(event.target.files)
  const anexo = event.target.files[0]
  const imgElemento = document.querySelector('.usuario-alterar-foto')
  const msgErroImagem = document.querySelector('.erro-usuario-foto')

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

  const imagem = new Image()
  imagem.src = URL.createObjectURL(anexo)

  const objetoReader = new FileReader()
  objetoReader.onload = (e) => {
    imgElemento.src = e.target.result
    imgElemento.classList.remove('hidden')
    imgElemento.classList.remove('p-2')
  }

  objetoReader.readAsDataURL(anexo)
}

const removerFotoUsuario = (usuarioId) => {

  if (! usuarioId) {
    return
  }

  fetch(`/${empresa}/d/usuario/foto/${usuarioId}`, { method: 'DELETE' })
  .then(response => response.json())
  .then(data => {

    if (data.erro) {
      throw new Error(data.erro)
    }

    window.location.href = `/${empresa}/dashboard/usuario/editar/${usuarioId}`
  })
  .catch(error => {
    console.log(error)
    window.location.href = `/${empresa}/dashboard/usuario/editar/${usuarioId}`
  })
}