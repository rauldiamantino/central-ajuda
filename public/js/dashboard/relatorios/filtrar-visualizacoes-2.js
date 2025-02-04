const filtrarVisualizacoes = () => {
  const modalFiltrar = document.querySelector('.modal-filtrar-visualizacoes-2')
  const modalFiltrarCancelar = modalFiltrar.querySelector('.modal-filtrar-visualizacoes-2-btn-cancelar')
  const modalFiltrarConfirmar = modalFiltrar.querySelector('.modal-filtrar-visualizacoes-2-btn-confirmar')
  const modalFiltrarLimpar = modalFiltrar.querySelector('.modal-filtrar-visualizacoes-2-btn-limpar')
  const modalFiltrarBlocos = modalFiltrar.querySelector('.modal-filtrar-visualizacoes-2-blocos')

  if (! modalFiltrar) {
    return
  }

  const urlParams = new URLSearchParams(window.location.search)
  const categoriaSelecionadaId = urlParams.get('categoria_id')
  const inputCodigo = modalFiltrarBlocos.querySelector('#filtrar-visualizacoes-2-codigo')
  const inputDataInicio = modalFiltrarBlocos.querySelector('#filtrar-visualizacoes-2-data-inicio')
  const inputDataFim = modalFiltrarBlocos.querySelector('#filtrar-visualizacoes-2-data-fim')
  const selectCategoria = modalFiltrarBlocos.querySelector('#filtrar-visualizacoes-2-categoria')

  if (! empresa) {
    return
  }

  // Sempre limpa
  selectCategoria.innerHTML = ''
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

        inputDataInicio.addEventListener('keypress', (event) => {

          if (event.key === 'Enter') {
            clicouConfirmar(true)
          }
        })

        inputDataFim.addEventListener('keypress', (event) => {

          if (event.key === 'Enter') {
            clicouConfirmar(true)
          }
        })

        clicouLimpar()
        clicouCancelar()
        clicouConfirmar()
        mascararData()
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
          modalFiltrarLimpar.addEventListener('click', () => window.location.href = `/dashboard/relatorios/visualizacoes`)
        }

        function clicouConfirmar(cliqueEnter = false) {
          modalFiltrarConfirmar.addEventListener('click', () => {
            const params = paramsFiltro()
            window.location.href = `/dashboard/relatorios/visualizacoes${params ? '?' + params : ''}`
          })

          if (cliqueEnter) {
            const params = paramsFiltro()
            window.location.href = `/dashboard/relatorios/visualizacoes${params ? '?' + params : ''}`
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

          if (inputDataInicio.value !== undefined && inputDataInicio.value != '') {
            params += `&data_inicio=${inputDataInicio.value}`
          }

          if (inputDataFim.value !== undefined && inputDataFim.value != '') {
            params += `&data_fim=${inputDataFim.value}`
          }

          return params
        }

        function mascararData() {
          const $datas = document.querySelectorAll('.data-visualizacao')

          if (! $datas) {
            return
          }

          $datas.forEach(valor => {
            new Cleave(valor, {
              date: true,
              delimiter: '/',
              datePattern: ['d', 'm', 'Y']
            })
          })
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