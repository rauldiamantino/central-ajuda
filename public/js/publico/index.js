document.addEventListener('DOMContentLoaded', function() {
  const topoPublico = document.querySelector('.topo-publico')
  const topoPublicoInverter = document.querySelector('.inverter')
console.log(topoPublicoInverter)
  function checarScroll() {
    const posicaoScroll = window.scrollY

    if (posicaoScroll > 50) {
      topoPublico.classList.add('shadow')
      topoPublico.classList.remove('bg-slate-800')
      topoPublico.classList.add('bg-white')
      topoPublicoInverter.classList.remove('invert')
    } 
    else {
      topoPublico.classList.remove('shadow')
      topoPublico.classList.remove('bg-white')
      topoPublico.classList.add('bg-slate-800')
      topoPublicoInverter.classList.add('invert')
    }
  }

  window.addEventListener('scroll', checarScroll)
})
