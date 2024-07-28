let artigoId = null
const btnsArtigoEditar = document.querySelectorAll('.js-dashboard-artigos-editar')
const btnsArtigoApagar = document.querySelectorAll('.js-dashboard-artigos-apagar')
const modalApagar = document.querySelector('.modal-artigo-apagar')
const btnModalRemover = document.querySelector('.modal-artigo-btn-remover')
const btnModalCancelar = document.querySelector('.modal-artigo-btn-cancelar')

btnsArtigoEditar.forEach(artigo => {
  artigo.addEventListener('click', () => {
    console.log(artigo)
  })
})

btnsArtigoApagar.forEach(artigo => {
  artigo.addEventListener('click', () => {
    artigoId = artigo.dataset.artigoId
    abrirModalApagar()
  })
})

btnModalRemover.addEventListener('click', () => {
  reqApagar(artigoId)
  fecharModalApagar()
})

btnModalCancelar.addEventListener('click', () => {
  fecharModalApagar()
})

const abrirModalApagar = () => {
  modalApagar.showModal()
}

const fecharModalApagar = () => {
  modalApagar.close()
}

const reqApagar = (artigoId) => {

  if (! artigoId) {
    return
  }

  fetch(`/artigo/${artigoId}`, { method: 'DELETE' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.linhasAfetadas == 1) {
        location.reload()
      }
      else if (resposta.erro) {
        throw new Error(resposta.erro)
      }
      else {
        throw new Error('Erro ao remover artigo')
      }
    })
    .catch(error => {
      location.reload()
      console.log(error)
    })
}

const inputNumeroPagina = document.querySelector('.artigo-numero-pagina')

inputNumeroPagina.addEventListener('input', () => {

  if (this.value < 0) {
    this.value = '';
  }
});

inputNumeroPagina.addEventListener('keypress', function(event) {
  if (event.key === '-' || event.key === 'e') {
    event.preventDefault();
  }
});