document.addEventListener('DOMContentLoaded', function () {
  const bloco = document.querySelector('.conteudo-blocos')
  
  if (! bloco) {
    return
  }
  
  Sortable.create(bloco, {
    animation: 150,
    handle: '.handle',
    onEnd: function () {
      const ordem = []
  
      document.querySelectorAll('.conteudo-bloco').forEach(function (item, index) {
        ordem.push({
          id: item.dataset.conteudoId,
          ordem: index
        })
      })
  
      if (! ordem) {
        return
      }

      fetch(`/conteudo/ordem`, {
         method: 'PUT',
         body: JSON.stringify(ordem)
        })
        .then(resposta => resposta.json())
        .then(resposta => {
          
          if (resposta.linhasAfetadas > 0) {
            console.log(resposta.linhasAfetadas + ' conteúdos reorganizados')
          }
          else if (resposta.erro) {
            throw new Error(resposta.erro)
          }
          else {
            throw new Error('Erro ao reorganizar conteudo')
          }
        })
        .catch(error => {
          location.reload()
        })
    }
  })
})