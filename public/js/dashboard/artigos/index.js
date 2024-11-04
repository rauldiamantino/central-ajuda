import { apagarImgsArtigo } from '../firebase/funcoes.js'

let artigoId = null
let empresaId = null

const btnsArtigoEditar = document.querySelectorAll('.js-dashboard-artigos-editar')
const btnsArtigoRemover = document.querySelectorAll('.js-dashboard-artigos-remover')
const modalRemover = document.querySelector('.modal-artigo-remover')
const btnModalRemover = document.querySelector('.modal-artigo-btn-remover')
const btnModalCancelar = document.querySelector('.modal-artigo-btn-cancelar')

if (btnsArtigoEditar) {
  btnsArtigoEditar.forEach(artigo => {
    artigo.addEventListener('click', () => {

    })
  })
}

if (btnsArtigoRemover) {
  btnsArtigoRemover.forEach(artigo => {
    artigo.addEventListener('click', () => {
      artigoId = artigo.dataset.artigoId
      empresaId = artigo.dataset.empresaId

      abrirModalRemover()
    })
  })
}

if (btnModalRemover) {
  btnModalRemover.addEventListener('click', () => {
    requisicaoRemover(artigoId)
    fecharModalRemover()
  })
}

if (btnModalCancelar) {
    btnModalCancelar.addEventListener('click', () => {
    fecharModalRemover()
  })
}

const abrirModalRemover = () => {
  modalRemover.showModal()
}

const fecharModalRemover = () => {
  modalRemover.close()
}

const requisicaoRemover = async (artigoId) => {

  if (artigoId === undefined || empresaId === undefined || empresaId === undefined) {
    return
  }

  const apagar = await apagarImgsArtigo(`imagens/empresa-${empresaId}/artigo-${artigoId}`)

  if (apagar == false) {
    return
  }

  fetch(baseUrl(`/${empresa}/d/artigo/${artigoId}`), { method: 'DELETE' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.linhasAfetadas == 1) {
        window.location.href = window.location.href;
      }
      else if (resposta.erro) {
        throw new Error(resposta.erro)
      }
      else {
        throw new Error('Erro ao remover artigo')
      }
    })
    .catch(error => {
      window.location.href = window.location.href;
      console.log(error)
    })
}

const inputNumeroPagina = document.querySelector('.artigo-numero-pagina')

if (inputNumeroPagina) {
  inputNumeroPagina.addEventListener('input', function() {

    if (this.value < 0) {
      this.value = ''
    }
  })

  inputNumeroPagina.addEventListener('keypress', function(event) {

    if (event.key === '-' || event.key === 'e') {
      event.preventDefault()
    }
  })
}