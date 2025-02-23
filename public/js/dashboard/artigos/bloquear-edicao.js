document.addEventListener('DOMContentLoaded', function() {
  const preVisualizacao = document.querySelector('.dashboard-pre-visualizacao')

  if (! preVisualizacao) {
    return
  }

  const dataUsuarioNivel = preVisualizacao.dataset.usuarioNivel
  const btnBloqueado = document.querySelector('.pre-visualizacao-bloqueado')

  if (! dataUsuarioNivel || ! btnBloqueado) {
    return
  }

  bloquearEdicao()

  if (dataUsuarioNivel == 2) {
    btnBloqueado.disabled = true
  }
})

const desbloquearEdicao = () => {
  const btnBloquear = document.querySelector('.pre-visualizacao-bloquear')
  const btnBloqueado = document.querySelector('.pre-visualizacao-bloqueado')

  btnBloquear?.classList.remove('hidden')
  btnBloqueado?.classList.add('hidden')

  const botoesEditarConteudo = document.querySelectorAll('.div-pai-conteudo-editar')

  if (! botoesEditarConteudo) {
    return
  }

  botoesEditarConteudo.forEach(botao => {
    const btnEditar = botao.querySelector('button')
    const conteudoTitulo = botao.querySelector('.pre-visualizacao-conteudo-titulo')
    const conteudoConteudo = botao.querySelector('.pre-visualizacao-conteudo-conteudo')
    const divPaiBtnEditar = btnEditar?.closest('.div-pai-conteudo-editar')

    if (! btnEditar) {
      return
    }

    btnEditar.disabled = false
    btnEditar.setAttribute('onclick', 'abrirModalEditar(event)')
    divPaiBtnEditar.classList.add('hover:bg-gray-600/10')
    conteudoTitulo?.classList.add('pointer-events-none')
    conteudoTitulo?.classList.remove('select-text')
    conteudoConteudo?.classList.add('pointer-events-none')
    conteudoConteudo?.classList.remove('select-text')
  })
}

const bloquearEdicao = () => {
  const btnBloquear = document.querySelector('.pre-visualizacao-bloquear')
  const btnBloqueado = document.querySelector('.pre-visualizacao-bloqueado')

  btnBloquear?.classList.add('hidden')
  btnBloqueado?.classList.remove('hidden')

  const botoesEditarConteudo = document.querySelectorAll('.div-pai-conteudo-editar')

  if (! botoesEditarConteudo) {
    return
  }

  botoesEditarConteudo.forEach(botao => {
    const btnEditar = botao.querySelector('button')
    const conteudoTitulo = botao.querySelector('.pre-visualizacao-conteudo-titulo')
    const conteudoConteudo = botao.querySelector('.pre-visualizacao-conteudo-conteudo')
    const divPaiBtnEditar = btnEditar?.closest('.div-pai-conteudo-editar')

    if (! btnEditar) {
      return
    }

    btnEditar.disabled = true
    btnEditar.removeAttribute('onclick')
    divPaiBtnEditar.classList.remove('hover:bg-gray-600/10')
    conteudoTitulo?.classList.remove('pointer-events-none')
    conteudoTitulo?.classList.add('select-text')
    conteudoConteudo?.classList.remove('pointer-events-none')
    conteudoConteudo?.classList.add('select-text')
  })
}