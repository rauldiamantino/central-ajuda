document.addEventListener('DOMContentLoaded', function() {
  const topoPublico = document.querySelector('.topo-publico')

  function checarScroll() {
    const posicaoScroll = window.scrollY

    if (posicaoScroll > 50) {
      topoPublico.classList.add('shadow')
    } 
    else {
      topoPublico.classList.remove('shadow')
    }
  }

  window.addEventListener('scroll', checarScroll)
})
