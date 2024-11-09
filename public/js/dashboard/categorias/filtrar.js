const filtrarCategorias = () => {
  const modalFiltrar = document.querySelector('.modal-filtrar-cate')
  const modalFiltrarCancelar = modalFiltrar.querySelector('.modal-filtrar-cate-btn-cancelar')
  const modalFiltrarConfirmar = modalFiltrar.querySelector('.modal-filtrar-cate-btn-confirmar')
  const modalFiltrarLimpar = modalFiltrar.querySelector('.modal-filtrar-cate-btn-limpar')
  const modalFiltrarBlocos = modalFiltrar.querySelector('.modal-filtrar-cate-blocos')

  if (! modalFiltrar) {
    return
  }

  const inputId = modalFiltrarBlocos.querySelector('#filtrar-id')
  const inputTitulo = modalFiltrarBlocos.querySelector('#filtrar-titulo')
  const selectStatus = modalFiltrarBlocos.querySelector('#filtrar-status')

  if (! empresa) {
    return
  }

  inputId.addEventListener('keypress', (event) => {

    if (event.key === 'Enter') {
      clicouConfirmar(true)
    }
  })

  inputTitulo.addEventListener('keypress', (event) => {

    if (event.key === 'Enter') {
      clicouConfirmar(true)
    }
  })

  clicouLimpar()
  clicouCancelar()
  clicouConfirmar()
  modalFiltrar.showModal()

  function clicouCancelar() {
    modalFiltrarCancelar.addEventListener('click', () => modalFiltrar.close())
  }

  function clicouLimpar() {
    modalFiltrarLimpar.addEventListener('click', () => window.location.href = `/${empresa}/dashboard/categorias`)
  }

  function clicouConfirmar(cliqueEnter = false) {
    modalFiltrarConfirmar.addEventListener('click', () => {
      const params = paramsFiltro()
      window.location.href = `/${empresa}/dashboard/categorias${params ? '?' + params : ''}`
    })

    if (cliqueEnter) {
      const params = paramsFiltro()
      window.location.href = `/${empresa}/dashboard/categorias${params ? '?' + params : ''}`
    }
  }

  function paramsFiltro() {
    let params = ''

    if (inputId.value !== undefined && inputId.value != '') {
      params += `&id=${inputId.value}`
    }

    if (inputTitulo.value !== undefined && inputTitulo.value != '') {
      params += `&nome=${encodeURI(inputTitulo.value)}`
    }

    if (selectStatus.value !== undefined && selectStatus.value != 'null') {
      params += `&status=${selectStatus.value}`
    }

    return params
  }
}