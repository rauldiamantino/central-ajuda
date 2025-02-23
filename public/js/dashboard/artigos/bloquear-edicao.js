const definirBloqueio = (artigoId) => {
  requisicaoAtualizar(artigoId, 0)
  bloquearEdicao()
}

const definirDesbloqueio = (artigoId, usuarioNivel) => {

  if (usuarioNivel == 2) {
    return
  }

  requisicaoAtualizar(artigoId, 1)
  desbloquearEdicao()
}

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
    conteudoConteudo?.classList.add('pointer-events-none', 'group-hover:block')
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
    conteudoConteudo?.classList.remove('pointer-events-none', 'group-hover:block')
    conteudoConteudo?.classList.add('select-text')
  })
}

const requisicaoAtualizar = (artigoId, artigoEditar) => {

  if (artigoId === undefined || empresaId === undefined) {
    return
  }

  fetch(`/d/artigo/${artigoId}`, {
    method: 'PUT',
    headers: {
      'X-Requested-With': 'fetch' ,
    },
    body: JSON.stringify({editar: parseInt(artigoEditar)})
    })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.linhasAfetadas != undefined) {
        return true
      }
      else if (resposta.erro) {
        throw new Error(resposta.erro)
      }
      else {
        throw new Error('Erro ao atualizar bloqueio de edição')
      }
    })
    .catch(error => {
      console.log(error)
    })

    return false;
}