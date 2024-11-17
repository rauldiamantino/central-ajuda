import { apagarImgsArtigo } from '../firebase/funcoes.js'

document.addEventListener('DOMContentLoaded', function() {
  // Redir início rápido
  const urlParams = new URLSearchParams(window.location.search)
  const modalAdicionar = document.querySelector('.menu-adicionar-artigo')

  if (urlParams.get('acao') === 'adicionar') {

    if (! modalAdicionar) {
      return
    }

    setTimeout(() => {
      modalAdicionar.showModal()
    }, 200)
  }
})

let artigoId = null
let empresaId = null

const btnsArtigoRemover = document.querySelectorAll('.js-dashboard-artigos-remover')
const modalRemover = document.querySelector('.modal-artigo-remover')
const btnModalRemover = document.querySelector('.modal-artigo-btn-remover')
const btnModalCancelar = document.querySelector('.modal-artigo-btn-cancelar')

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

  if (artigoId === undefined || empresaId === undefined) {
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
        window.location.href = baseUrl(`/${empresa}/dashboard/artigos`);
        return
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
      return
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

// Adicionar conteúdos
const menuAdicionarConteudo = document.querySelector('.menu-adicionar-conteudos')
const botaoAbrirMenuAdicionarConteudo = document.querySelector('.botao-abrir-menu-adicionar-conteudos')
const botaoFecharMenuAdicionarConteudo = document.querySelector('.botao-fechar-menu-adicionar-conteudos')

if (botaoAbrirMenuAdicionarConteudo) {
  botaoAbrirMenuAdicionarConteudo.addEventListener('click', () => {
    menuAdicionarConteudo.showModal()
  })
}

if (botaoFecharMenuAdicionarConteudo) {
  botaoFecharMenuAdicionarConteudo.addEventListener('click', () => {
    menuAdicionarConteudo.close()
  })
}