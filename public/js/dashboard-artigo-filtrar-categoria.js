const filtrarArtigos = () => {
  const modalFiltrar = document.querySelector('.modal-artigos-filtrar-cate')
  const modalFiltrarCancelar = document.querySelector('.modal-artigos-filtrar-cate-btn-cancelar')
  const modalFiltrarConfirmar = document.querySelector('.modal-artigos-filtrar-cate-btn-confirmar')
  const modalFiltrarBlocos = document.querySelector('.modal-artigos-filtrar-cate-blocos')

  if (!modalFiltrar || !modalFiltrarCancelar || !modalFiltrarBlocos || !modalFiltrarConfirmar) {
    return
  }

  modalFiltrarCancelar.addEventListener('click', () => modalFiltrar.close())

  // Resgatar categoria_id da URL
  const urlParams = new URLSearchParams(window.location.search)
  const categoriaSelecionadaId = urlParams.get('categoria_id')

  fetch(`/categorias`, { method: 'GET' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.length > 0) {
        modalFiltrarBlocos.innerHTML = ''

        const select = document.createElement('select')
        select.className = 'w-full py-1 px-4 border border-slate-200 rounded-md modal-categorias-filtrar-select'

        const optionSemCategoria = document.createElement('option')
        optionSemCategoria.value = 0
        optionSemCategoria.textContent = '(Sem categoria)'

        if (categoriaSelecionadaId === '0') {
          optionSemCategoria.selected = true
        }

        select.appendChild(optionSemCategoria)

        const optionTodasCategorias = document.createElement('option')
        optionTodasCategorias.value = ''
        optionTodasCategorias.textContent = 'Todas as categorias'
        
        if (categoriaSelecionadaId === null || categoriaSelecionadaId === '') {
          optionTodasCategorias.selected = true
        }

        select.appendChild(optionTodasCategorias)

        resposta.forEach(categoria => {
          const option = document.createElement('option')
          option.value = categoria['Categoria.id']
          option.textContent = categoria['Categoria.nome']

          // Verifica se esta é a categoria selecionada
          if (categoria['Categoria.id'] == categoriaSelecionadaId) {
            option.selected = true
          }

          select.appendChild(option)
        })

        modalFiltrarBlocos.appendChild(select)

        modalFiltrar.showModal()

        modalFiltrarConfirmar.addEventListener('click', () => {
          const categoriaId = select.value

          if (categoriaId !== undefined) {
            window.location.href = `/dashboard/artigos?categoria_id=${categoriaId}`
          }
        })
      } 
      else {
        throw new Error('Erro ao buscar categorias')
      }
    })
    .catch(error => {
      console.error(error)
    })
}
