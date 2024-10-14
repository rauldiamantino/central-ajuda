const filtrarArtigos = () => {
  const modalFiltrar = document.querySelector('.modal-artigos-filtrar-cate')
  const modalFiltrarCancelar = modalFiltrar.querySelector('.modal-artigos-filtrar-cate-btn-cancelar')
  const modalFiltrarConfirmar = modalFiltrar.querySelector('.modal-artigos-filtrar-cate-btn-confirmar')
  const modalFiltrarBlocos = modalFiltrar.querySelector('.modal-artigos-filtrar-cate-blocos')

  if (! modalFiltrar) {
    return
  }

  const urlParams = new URLSearchParams(window.location.search)
  const categoriaSelecionadaId = urlParams.get('categoria_id')
  const select = modalFiltrarBlocos.querySelector('select')

  if (! empresaId) {
    return
  }

  // Sempre limpa
  select.innerHTML = ''

  fetch(`/d/${empresaId}/categorias`, { method: 'GET' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.length > 0) {
        gerarOption('Todas as categorias', '')
        gerarOption('*** Sem categoria ***', 0)

        resposta.forEach(categoria => gerarOption(categoria['Categoria']['nome'], categoria['Categoria']['id']))

        clicouCancelar()
        clicouConfirmar()
        modalFiltrar.showModal()

        // Funções
        function gerarOption(nome, valor) {
          const option = document.createElement('option')

          option.value = valor
          option.textContent = nome

          if (typeof valor == 'number' && valor == parseInt(categoriaSelecionadaId)) {
            option.selected = true
          }
          else if (valor === categoriaSelecionadaId) {
            option.selected = true
          }

          select.appendChild(option)
        }

        function clicouConfirmar() {
          modalFiltrarConfirmar.addEventListener('click', () => {
            const categoriaId = select.value

            if (categoriaId !== undefined) {
              window.location.href = `/dashboard/${empresaId}/artigos?categoria_id=${categoriaId}`
            }
          })
        }

        function clicouCancelar() {
          modalFiltrarCancelar.addEventListener('click', () => modalFiltrar.close())
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
