const buscarArtigos = () => {
  const modalOrganizar = document.querySelector('.modal-artigos-organizar')
  const modalOrganizarCancelar = document.querySelector('.modal-artigos-organizar-btn-cancelar')
  const modalOrganizarConfirmar = document.querySelector('.modal-artigos-organizar-btn-confirmar')
  const modalOrganizarBlocos = document.querySelector('.modal-artigos-organizar-blocos')
  const modalAlertaFiltro = document.querySelector('.modal-artigos-alerta-filtro')
  const modalAlertaFiltroOk = modalAlertaFiltro.querySelector('.modal-artigo-btn-alerta-ok')

  if (! empresaId) {
    return
  }

  const urlParams = new URLSearchParams(window.location.search)
  const categoriaSelecionadaId = urlParams.get('categoria_id')
  const urlBuscar = baseUrl(`/d/${empresaId}/artigos?categoria_id=${categoriaSelecionadaId}`)

  if (! categoriaSelecionadaId) {
    modalAlertaFiltroOk.addEventListener('click', () => modalAlertaFiltro.close())

    return modalAlertaFiltro.showModal()
  }

  if (! modalOrganizar || ! modalOrganizarCancelar || ! modalOrganizarBlocos || ! modalOrganizarConfirmar) {
    return
  }

  modalOrganizarCancelar.addEventListener('click', () => modalOrganizar.close())

  fetch(urlBuscar, { method: 'GET' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.length > 0) {
        modalOrganizarBlocos.innerHTML = ''

        resposta.forEach(artigo => {
          const span = document.createElement('span')
          span.className = 'py-1 px-4 border border-slate-200 selected-none truncate rounded-md modal-artigos-organizar-bloco'
          span.textContent = artigo['Artigo']['titulo']
          span.setAttribute('data-artigo-id', artigo['Artigo']['id'])

          modalOrganizarBlocos.appendChild(span)
        })

        modalOrganizar.showModal()

        Sortable.create(modalOrganizarBlocos, {
          animation: 150,
          handle: '.handle',
          onEnd: function () {
            const ordem = []

            document.querySelectorAll('.modal-artigos-organizar-bloco').forEach(function (item, index) {
              ordem.push({
                id: item.dataset.artigoId,
                ordem: index
              })
            })

            if (! ordem) {
              return
            }

            modalOrganizarConfirmar.addEventListener('click', () => {
              fetch(baseUrl(`/d/${empresaId}/artigo/ordem`), {
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
                    throw new Error('Erro ao reorganizar artigos')
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
        throw new Error('Erro ao buscar artigos')
      }
    })
    .catch(error => {
      console.error(error)
    })
}
