import { substituirImagem, uploadImagem } from '../firebase/funcoes.js'

document.addEventListener('DOMContentLoaded', function () {
  const editarFaviconEscolher = document.querySelector('.empresa-editar-favicon-escolher')
  const editarTextoFaviconEscolher = document.querySelector('.empresa-txt-favicon-editar-escolher')
  const faviconElemento = document.querySelector('.empresa-alterar-favicon')
  const formularioEditarEmpresa = document.querySelector('.form-editar-empresa')
  const btnAlterarFavicon = document.querySelector('.empresa-btn-favicon-editar-escolher')
  const msgErro = document.querySelector('.erro-empresa-favicon')

  if (! formularioEditarEmpresa) {
    return
  }

  let faviconParaUpload = null
  let faviconAtual = formularioEditarEmpresa.dataset.faviconAtual

  if (! btnAlterarFavicon || ! editarFaviconEscolher) {
    return
  }

  btnAlterarFavicon.addEventListener('click', () => {
    editarFaviconEscolher.click()
  })

  editarFaviconEscolher.addEventListener('change', (event) => {
    const anexo = event.target.files[0]

    if (anexo) {
      const formatosPermitidos = ['image/jpeg', 'image/png']
      msgErro.dataset.sucesso = 'true'
      msgErro.textContent = ''

      if (! formatosPermitidos.includes(anexo.type)) {
        msgErro.textContent = 'Por favor, escolha um arquivo PNG ou JPEG.'
        msgErro.dataset.sucesso = 'false'
        return
      }

      const tamanhoMaximoMB = 2
      const tamanhoMaximoBytes = tamanhoMaximoMB * 1024 * 1024

      if (anexo.size > tamanhoMaximoBytes) {
        msgErro.textContent = 'Tamanho de imagem excede o limite de 2MB.'
        msgErro.dataset.sucesso = 'false'
        return
      }

      const imagem = new Image()
      imagem.src = URL.createObjectURL(anexo)

      imagem.onload = () => {
        const larguraMaxima = 48
        const alturaMaxima = 48

        if (imagem.width > larguraMaxima || imagem.height > alturaMaxima) {
          msgErro.textContent = `Por favor, envie uma imagem com até ${larguraMaxima}px de largura e ${alturaMaxima}px de altura.`
          msgErro.dataset.sucesso = 'false'
          return
        }
      }

      const objetoReader = new FileReader()

      objetoReader.onload = (e) => {
        faviconElemento.src = e.target.result
        faviconElemento.classList.remove('hidden')
      }

      editarTextoFaviconEscolher.textContent = anexo.name
      objetoReader.readAsDataURL(anexo)
      faviconParaUpload = anexo
    }
  })

  editarTextoFaviconEscolher.textContent = 'Alterar favicon'

  if (formularioEditarEmpresa) {
    formularioEditarEmpresa.addEventListener('submit', async (event) => {
      event.preventDefault()

      const empresaId = formularioEditarEmpresa.dataset.empresaId
      const btnEditar = formularioEditarEmpresa.querySelector('.btn-gravar-empresa')
      const inputUrlFavicon = formularioEditarEmpresa.querySelector('.url-favicon')
      const msgErroData = msgErro.dataset.sucesso

      if (empresaId == undefined || btnEditar == undefined || msgErroData == 'false') {
        return
      }

      if (faviconParaUpload && faviconAtual) {
        btnEditar.disabled = true

        const downloadURL = await substituirImagem(empresaId, 0, faviconParaUpload, faviconAtual, true)

        if (downloadURL) {
          inputUrlFavicon.value = downloadURL
          formularioEditarEmpresa.submit()
        }

        btnEditar.disabled = false
      }
      else if (faviconParaUpload) {
        btnEditar.disabled = true

        const downloadURL = await uploadImagem(empresaId, 0, faviconParaUpload, true)

        if (downloadURL) {
          inputUrlFavicon.value = downloadURL
          formularioEditarEmpresa.submit()
        }

        btnEditar.disabled = false
      }
      else {
        inputUrlFavicon.value = faviconAtual
        formularioEditarEmpresa.submit()
      }
    })
  }
})