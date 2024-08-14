document.addEventListener('DOMContentLoaded', function () {
  const bloco = document.querySelector('.categorias-mover')

  if (! bloco) {
    return
  }
  
  Sortable.create(bloco, {
    animation: 150,
    handle: '.handle',
    onEnd: function () {
      const ordem = []
  
      document.querySelectorAll('.categoria-mover').forEach(function (item, index) {
        ordem.push({
          id: item.dataset.categoriaId,
          ordem: index
        })
      })
  
      if (! ordem) {
        return
      }

      fetch(`/categoria/ordem`, {
         method: 'PUT',
         body: JSON.stringify(ordem)
        })
        .then(resposta => resposta.json())
        .then(resposta => {

          if (resposta.linhasAfetadas > 0) {
            console.log(resposta.linhasAfetadas + ' categorias reorganizados')
          }
          else if (resposta.erro) {
            throw new Error(resposta.erro)
          }
          else {
            throw new Error('Erro ao reorganizar categorias')
          }
        })
        .catch(error => {
          location.reload()
        })
    }
  })
})