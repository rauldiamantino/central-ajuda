document.addEventListener('DOMContentLoaded', function () {
  const bloco = document.querySelector('.artigos-mover')
  
  if (! bloco) {
    return
  }
  
  Sortable.create(bloco, {
    animation: 150,
    handle: '.handle',
    onEnd: function () {
      const ordem = []
  
      document.querySelectorAll('.artigo-mover').forEach(function (item, index) {
        ordem.push({
          id: item.dataset.artigoId,
          ordem: index
        })
      })
  
      if (! ordem) {
        return
      }

      fetch(`/artigo/ordem`, {
         method: 'PUT',
         body: JSON.stringify(ordem)
        })
        .then(resposta => resposta.json())
        .then(resposta => {
          
          if (resposta.linhasAfetadas > 0) {
            console.log(resposta.linhasAfetadas + ' artigos reorganizados')
          }
          else if (resposta.erro) {
            throw new Error(resposta.erro)
          }
          else {
            throw new Error('Erro ao reorganizar artigos')
          }
        })
        .catch(error => {
          location.reload()
        })
    }
  })
})