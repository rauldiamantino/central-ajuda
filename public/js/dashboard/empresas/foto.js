import { substituirImagem, uploadImagem } from '../firebase/funcoes.js'

document.addEventListener('DOMContentLoaded', function () {
  const editarImagemEscolher = document.querySelector('.empresa-editar-imagem-escolher')
  const editarTextoImagemEscolher = document.querySelector('.empresa-txt-imagem-editar-escolher')
  const imgElemento = document.querySelector('.empresa-alterar-logo')
  const formularioEditarEmpresa = document.querySelector('.form-editar-empresa')
  const btnAlterarIMagem = document.querySelector('.empresa-btn-imagem-editar-escolher')
  const msgErro = document.querySelector('.erro-empresa-imagem')

  let imagemParaUpload = null
  let imagemAtual = null

  if (! btnAlterarIMagem || ! editarImagemEscolher) {
    return
  }

  btnAlterarIMagem.addEventListener('click', () => {
    editarImagemEscolher.click()
  })

  editarImagemEscolher.addEventListener('change', (event) => {
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
        const larguraMaxima = 200
        const alturaMaxima = 70

        if (imagem.width > larguraMaxima || imagem.height > alturaMaxima) {
          msgErro.textContent = `Por favor, envie uma imagem com até ${larguraMaxima}px de largura e ${alturaMaxima}px de altura.`
          msgErro.dataset.sucesso = 'false'
          return
        }
      }
      
      const objetoReader = new FileReader()

      objetoReader.onload = (e) => {
        imagemAtual = formularioEditarEmpresa.dataset.imagemAtual
        imgElemento.src = e.target.result
        imgElemento.classList.remove('hidden')
      }

      editarTextoImagemEscolher.textContent = anexo.name
      objetoReader.readAsDataURL(anexo)
      imagemParaUpload = anexo
    }
  })

  editarTextoImagemEscolher.textContent = 'Alterar imagem'

  if (formularioEditarEmpresa) {
    formularioEditarEmpresa.addEventListener('submit', async (event) => {
      event.preventDefault()

      const empresaId = formularioEditarEmpresa.dataset.empresaId
      const btnEditar = formularioEditarEmpresa.querySelector('.btn-gravar-empresa')
      const inputUrlImagem = formularioEditarEmpresa.querySelector('.url-imagem')
      const msgErroData = msgErro.dataset.sucesso

      if (empresaId == undefined || btnEditar == undefined || msgErroData == 'false') {
        return
      }

      if (imagemParaUpload && imagemAtual) {
        btnEditar.disabled = true

        const downloadURL = await substituirImagem(empresaId, 0, imagemParaUpload, imagemAtual)

        if (downloadURL) {
          inputUrlImagem.value = downloadURL
          formularioEditarEmpresa.submit()
        }

        btnEditar.disabled = false
      }
      else if (imagemParaUpload) {
        btnEditar.disabled = true

        const downloadURL = await uploadImagem(empresaId, 0, imagemParaUpload)

        if (downloadURL) {
          inputUrlImagem.value = downloadURL
          formularioEditarEmpresa.submit()
        }

        btnEditar.disabled = false
      }
      else {
        inputUrlImagem.value = imagemAtual
        formularioEditarEmpresa.submit()
      }
    })
  }
})