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
let urlVoltar = null

const btnsArtigoRemover = document.querySelectorAll('.js-dashboard-artigos-remover')
const modalRemover = document.querySelector('.modal-artigo-remover')
const btnModalRemover = document.querySelector('.modal-artigo-btn-remover')
const btnModalCancelar = document.querySelector('.modal-artigo-btn-cancelar')

if (btnsArtigoRemover) {
  btnsArtigoRemover.forEach(artigo => {
    artigo.addEventListener('click', () => {
      artigoId = artigo.dataset.artigoId
      empresaId = artigo.dataset.empresaId
      urlVoltar = artigo.dataset.botaoVoltar ? validarReferer(artigo.dataset.botaoVoltar) : ''

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

function validarReferer(referer) {
  let refererDecodificado = decodeURIComponent(referer)
  const padraoEsperado = /^\/([^/]+)\/([^/]+)\/([^/]+)\/([^/]+)\/([0-9]+)$/
  const resultado = refererDecodificado.match(padraoEsperado)

  if (resultado) {
    return resultado[0]
  }
  else {
    console.error('Referer inválido ou estrutura incorreta')
    return null
  }
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

  fetch(`/d/artigo/${artigoId}`, { method: 'DELETE' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.linhasAfetadas == 1) {

        if (urlVoltar) {
          window.location.href = urlVoltar
        }
        else {
          window.location.href = `/dashboard/artigos`
        }
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
      window.location.href = window.location.href
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