import { substituirImagem, uploadImagem } from '../firebase/funcoes.js'

document.addEventListener('DOMContentLoaded', function () {
  mascararCnpj()
  mascararCelular()

  const editarImagemEscolher = document.querySelector('.empresa-editar-imagem-escolher')
  const editarTextoImagemEscolher = document.querySelector('.empresa-txt-imagem-editar-escolher')
  const imgElemento = document.querySelector('.empresa-alterar-logo')
  const formularioEditarEmpresa = document.querySelector('.form-editar-empresa')
  const btnAlterarIMagem = document.querySelector('.empresa-btn-imagem-editar-escolher')

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
      const objetoReader = new FileReader()

      objetoReader.onload = (e) => {
        imagemAtual = formularioEditarEmpresa.dataset.imagemAtual
        imgElemento.src = e.target.result
        imgElemento.classList.remove('opacity-0')
        imgElemento.classList.add('opacity-100')
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

      if (empresaId == undefined || btnEditar == undefined) {
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

const mascararCnpj = () => {
  const cnpj = document.querySelector('#empresa-editar-cnpj')

  if (! cnpj) {
    return
  }
  
  const cleaveCNPJ = new Cleave(cnpj, {
    delimiters: ['.', '.', '/', '-'],
    blocks: [2, 3, 3, 4, 2],
    numericOnly: true
  })
}

const mascararCelular = () => {
  const telefone = document.querySelector('#empresa-editar-telefone')

  if (! telefone) {
    return
  }

  const cleave = new Cleave(telefone, {
    phone: true,
    phoneRegionCode: 'BR'
  })
}