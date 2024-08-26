document.addEventListener('DOMContentLoaded', function() {
  const topoPublico = document.querySelector('.topo-publico')
  const topoPublicoInverter = document.querySelector('.inverter')
  const menuLateral = document.querySelector('.publico-menu-lateral')
  const btnMenuLateral = document.querySelector('.btn-publico-menu-lateral')

  function checarScroll() {
    const posicaoScroll = window.scrollY

    if (posicaoScroll > 50) {
      // topoPublico.classList.remove('bg-slate-800')
      // topoPublico.classList.add('bg-white')
      // topoPublicoInverter.classList.remove('invert')
    } 
    else {
      // topoPublico.classList.remove('bg-white')
      // topoPublico.classList.add('bg-slate-800')
      // topoPublicoInverter.classList.add('invert')
    }
  }

  if (btnMenuLateral) {
    btnMenuLateral.addEventListener('click', () => {
      menuLateralToggle(menuLateral)
    })
  }

  window.addEventListener('scroll', checarScroll)
})

const menuLateralToggle = (menuLateral) => {

  if (menuLateral.classList.contains('-translate-x-full') && ! menuLateral.classList.contains('translate-x-0')) {
    menuLateral.classList.add('translate-x-0')
    menuLateral.classList.remove('-translate-x-full')
  }
  else if (! menuLateral.classList.contains('-translate-x-full') && menuLateral.classList.contains('translate-x-0')) {
    menuLateral.classList.remove('translate-x-0')
    menuLateral.classList.add('-translate-x-full')
  }
}