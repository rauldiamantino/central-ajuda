const buscarCategorias = () => {
  const modalOrganizar = document.querySelector('.modal-categorias-organizar')
  const modalOrganizarCancelar = document.querySelector('.modal-cate-organizar-btn-cancelar')
  const modalOrganizarConfirmar = document.querySelector('.modal-cate-organizar-btn-confirmar')
  const modalOrganizarBlocos = document.querySelector('.modal-categorias-organizar-blocos')

  if (! modalOrganizar || ! modalOrganizarCancelar || ! modalOrganizarBlocos || ! modalOrganizarConfirmar) {
    return
  }

  if (! empresaId) {
    return
  }

  modalOrganizarCancelar.addEventListener('click', () => modalOrganizar.close())

  fetch(`/d/categorias/${empresaId}`, { method: 'GET' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.length > 0) {
        modalOrganizarBlocos.innerHTML = ''

        resposta.forEach(categoria => {
          const span = document.createElement('span')

          span.className = 'py-1 px-4 border border-slate-200 selected-none truncate rounded-md modal-categorias-organizar-bloco'
          span.textContent = categoria['Categoria.nome']
          span.setAttribute('data-categoria-id', categoria['Categoria.id'])

          modalOrganizarBlocos.appendChild(span)
        })

        modalOrganizar.showModal()

        Sortable.create(modalOrganizarBlocos, {
          animation: 150,
          handle: '.handle',
          onEnd: function () {
            const ordem = []

            document.querySelectorAll('.modal-categorias-organizar-bloco').forEach(function (item, index) {
              ordem.push({
                id: item.dataset.categoriaId,
                ordem: index
              })
            })

            if (! ordem) {
              return
            }

            modalOrganizarConfirmar.addEventListener('click', () => {
              fetch(`/d/categoria/ordem/${empresaId}`, {
                method: 'PUT',
                body: JSON.stringify(ordem)
                })
                .then(resposta => resposta.json())
                .then(resposta => {

                  if (resposta.linhasAfetadas > 0) {
                    location.reload()
                  }
                  else if (resposta.erro) {
                    throw new Error(resposta.erro)
                  }
                  else {
                    throw new Error('Erro ao reorganizar categorias')
                  }
                })
                .catch(error => {
                  alert('Não foi possível reorganizar')
                  location.reload()
                })
            })

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
