const filtrarArtigos = () => {
  const modalFiltrar = document.querySelector('.modal-artigos-filtrar-cate')
  const modalFiltrarCancelar = modalFiltrar.querySelector('.modal-artigos-filtrar-cate-btn-cancelar')
  const modalFiltrarConfirmar = modalFiltrar.querySelector('.modal-artigos-filtrar-cate-btn-confirmar')
  const modalFiltrarLimpar = modalFiltrar.querySelector('.modal-artigos-filtrar-cate-btn-limpar')
  const modalFiltrarBlocos = modalFiltrar.querySelector('.modal-artigos-filtrar-cate-blocos')

  if (! modalFiltrar) {
    return
  }

  const urlParams = new URLSearchParams(window.location.search)
  const categoriaSelecionadaId = urlParams.get('categoria_id')
  const inputCodigo = modalFiltrarBlocos.querySelector('#filtrar-codigo')
  const inputTitulo = modalFiltrarBlocos.querySelector('#filtrar-titulo')
  const selectStatus = modalFiltrarBlocos.querySelector('#filtrar-status')
  const selectCategoria = modalFiltrarBlocos.querySelector('#filtrar-categoria')

  if (! empresa) {
    return
  }

  // Sempre limpa
  selectCategoria.innerHTML = ''
  let categoriaNome = ''
  let categorias = {}

  fetch('/d/categorias', {
    method: 'GET',
    headers: {
      'X-Requested-With': 'fetch' ,
    },
  })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.length > 0) {
        gerarOptionCategorias('Selecione', '')
        gerarOptionCategorias('*** Sem categoria ***', 0)

        resposta.forEach(categoria => {
          gerarOptionCategorias(categoria['Categoria']['nome'], categoria['Categoria']['id'])
        })

        inputCodigo.addEventListener('keypress', (event) => {

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

        // Funções
        function gerarOptionCategorias(nome, valor) {
          const option = document.createElement('option')

          option.value = valor
          option.textContent = nome

          if (valor == categoriaSelecionadaId) {
            option.selected = true
          }

          selectCategoria.appendChild(option)
          categorias[ valor ] = nome
        }

        function clicouCancelar() {
          modalFiltrarCancelar.addEventListener('click', () => modalFiltrar.close())
        }

        function clicouLimpar() {
          modalFiltrarLimpar.addEventListener('click', () => window.location.href = `/dashboard/artigos`)
        }

        function clicouConfirmar(cliqueEnter = false) {
          modalFiltrarConfirmar.addEventListener('click', () => {
            const params = paramsFiltro()
            window.location.href = `/dashboard/artigos${params ? '?' + params : ''}`
          })

          if (cliqueEnter) {
            const params = paramsFiltro()
            window.location.href = `/dashboard/artigos${params ? '?' + params : ''}`
          }
        }

        function paramsFiltro() {
          let params = ''

          if (selectCategoria.value !== undefined && selectCategoria.value != '') {
            params = `categoria_id=${selectCategoria.value}&categoria_nome=${encodeURI(categorias[ selectCategoria.value ])}`
          }

          if (inputCodigo.value !== undefined && inputCodigo.value != '') {
            params += `&codigo=${inputCodigo.value}`
          }

          if (inputTitulo.value !== undefined && inputTitulo.value != '') {
            params += `&titulo=${encodeURI(inputTitulo.value)}`
          }

          if (selectStatus.value !== undefined && selectStatus.value != 'null') {
            params += `&status=${selectStatus.value}`
          }

          return params
        }
      }
      else {
        throw new Error('Erro ao buscar categorias')
      }
    })
    .catch(error => {
      console.error(error)
    })
}
