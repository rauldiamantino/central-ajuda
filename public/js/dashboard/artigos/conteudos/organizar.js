const buscarConteudos = () => {
  let artigoId = null
  const modalOrganizar = document.querySelector('.modal-conteudos-organizar')
  const modalOrganizarCancelar = document.querySelector('.modal-conteudos-organizar-btn-cancelar')
  const modalOrganizarConfirmar = document.querySelector('.modal-conteudos-organizar-btn-confirmar')
  const modalOrganizarBlocos = document.querySelector('.modal-conteudos-organizar-blocos')

  if (! modalOrganizar || ! modalOrganizarCancelar || ! modalOrganizarBlocos || ! modalOrganizarConfirmar) {
    return
  }

  if (! empresaId) {
    return
  }

  modalOrganizarCancelar.addEventListener('click', () => modalOrganizar.close())
  artigoId = modalOrganizar.dataset.artigoId

  fetch(`/d/conteudos/${empresaId}/${artigoId}`, { method: 'GET' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.length > 0) {
        modalOrganizarBlocos.innerHTML = ''

        resposta.forEach(conteudo => {
          const span = document.createElement('span')

          span.className = 'py-1 px-4 border border-slate-200 selected-none truncate rounded-md modal-conteudos-organizar-bloco'
          span.textContent = conteudo['Conteudo.titulo'] ? conteudo['Conteudo.titulo'] : '** Sem título **'
          span.setAttribute('data-conteudo-id', conteudo['Conteudo.id'])

          modalOrganizarBlocos.appendChild(span)
        })

        modalOrganizar.showModal()

        Sortable.create(modalOrganizarBlocos, {
          animation: 150,
          handle: '.handle',
          onEnd: function () {
            const ordem = []

            document.querySelectorAll('.modal-conteudos-organizar-bloco').forEach(function (item, index) {
              ordem.push({
                id: item.dataset.conteudoId,
                ordem: index
              })
            })

            if (! ordem) {
              return
            }

            modalOrganizarConfirmar.addEventListener('click', () => {
              fetch(`/d/conteudo/ordem/${empresaId}`, {
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
                    throw new Error('Erro ao reorganizar conteúdos')
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
        throw new Error('Erro ao buscar conteúdos')
      }
    })
    .catch(error => {
      console.error(error)
    })
}
