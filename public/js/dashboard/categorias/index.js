document.addEventListener('DOMContentLoaded', function() {
  // Redir início rápido
  const urlParams = new URLSearchParams(window.location.search)
  const modalAdicionar = document.querySelector('.menu-adicionar-categoria')

  if (urlParams.get('acao') === 'adicionar') {

    if (! modalAdicionar) {
      return
    }

    setTimeout(() => {
      modalAdicionar.showModal()
    }, 200)
  }

  let categoriaId = null
  const btnsCategoriaEditar = document.querySelectorAll('.js-dashboard-categorias-editar')
  const btnsCategoriaRemover = document.querySelectorAll('.js-dashboard-categorias-remover')
  const modalCateRemover = document.querySelector('.modal-categoria-remover')
  const btnModalCateRemover = document.querySelector('.modal-categoria-btn-remover')
  const btnModalCateCancelar = document.querySelector('.modal-categoria-btn-cancelar')

  if (btnsCategoriaEditar) {
    btnsCategoriaEditar.forEach(categoria => {
      categoria.addEventListener('click', () => {
        console.log(categoria)
      })
    })
  }

  if (btnsCategoriaRemover) {
    btnsCategoriaRemover.forEach(categoria => {
      categoria.addEventListener('click', () => {
        categoriaId = categoria.dataset.categoriaId
        abrirModalCateRemover()
      })
    })
  }

  if (btnModalCateRemover) {
    btnModalCateRemover.addEventListener('click', () => {
      requisicaoCateRemover(categoriaId)
      fecharModalCateRemover()
    })
  }

  if (btnModalCateCancelar) {
      btnModalCateCancelar.addEventListener('click', () => {
      fecharModalCateRemover()
    })
  }

  const abrirModalCateRemover = () => {
    modalCateRemover.showModal()
  }

  const fecharModalCateRemover = () => {
    modalCateRemover.close()
  }

  const requisicaoCateRemover = (categoriaId) => {

    if (! categoriaId) {
      return
    }

    if (! empresaId) {
      return
    }

    fetch(baseUrl(`/${empresa}/d/categoria/${categoriaId}`), { method: 'DELETE' })
      .then(resposta => resposta.json())
      .then(resposta => {

        if (resposta.linhasAfetadas == 1) {
          window.location.href = window.location.href
        }
        else if (resposta.erro) {
          throw new Error(resposta.erro)
        }
        else {
          throw new Error('Erro ao remover categoria')
        }
      })
      .catch(error => {
        window.location.href = window.location.href
      })
  }

  const inputNumeroCatePagina = document.querySelector('.categoria-numero-pagina')

  if (inputNumeroCatePagina) {
    inputNumeroCatePagina.addEventListener('input', () => {

      if (this.value < 0) {
        this.value = ''
      }
    })
  }

  if (inputNumeroCatePagina) {
    inputNumeroCatePagina.addEventListener('keypress', function(event) {
      if (event.key === '-' || event.key === 'e') {
        event.preventDefault()
      }
    })
  }
})