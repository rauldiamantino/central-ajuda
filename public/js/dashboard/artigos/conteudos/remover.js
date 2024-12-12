let conteudoId = null
let conteudoTipo = null
let conteudoUrl = null

const btnsConteudoRemover = document.querySelectorAll('.js-dashboard-conteudo-remover')
const modalConteudoRemover = document.querySelector('.modal-conteudo-remover')
const btnModalConteudoRemover = document.querySelector('.modal-conteudo-btn-remover')
const btnModalConteudoCancelar = document.querySelector('.modal-conteudo-btn-cancelar')

if (btnsConteudoRemover) {
  btnsConteudoRemover.forEach(conteudo => {
    conteudo.addEventListener('click', () => {
      conteudoId = conteudo.dataset.conteudoId
      conteudoTipo = conteudo.dataset.conteudoTipo
      conteudoUrl = conteudo.dataset.conteudoUrl

      abrirModalConteudoRemover()
    })
  })
}

if (btnModalConteudoRemover) {
  btnModalConteudoRemover.addEventListener('click', () => {
    requisicaoConteudoRemover(conteudoId)
    fecharModalConteudoRemover()
  })
}

if (btnModalConteudoCancelar) {
  btnModalConteudoCancelar.addEventListener('click', () => {
    fecharModalConteudoRemover()
  })
}

const abrirModalConteudoRemover = () => {
  modalConteudoRemover.showModal()
}

const fecharModalConteudoRemover = () => {
  modalConteudoRemover.close()
}

const requisicaoConteudoRemover = (conteudoId) => {

  if (! conteudoId) {
    return
  }

  if (! empresaId) {
    return
  }

  const elementosFiltrados = document.querySelector(`.js-dashboard-conteudo-remover[data-conteudo-id="${conteudoId}"]`)

  if (! elementosFiltrados) {
    return
  }

  const elementoPai = elementosFiltrados.closest('form')

  if (! elementoPai) {
    return
  }

  const botoes = elementoPai.querySelectorAll('button')
    botoes.forEach(botao => {
      botao.disabled = true
      botao.classList.add('opacity-50')
  })

  fetch(`/d/conteudo/${conteudoId}`, { method: 'DELETE' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.linhasAfetadas == 1) {
        window.location.href = window.location.href
      }
      else if (resposta.erro) {
        throw new Error(resposta.erro)
      }
      else {
        throw new Error('Erro ao remover conteudo')
      }
    })
    .catch(error => {
      console.error(error)
      window.location.href = window.location.href
    })
    .finally(() => {

      setTimeout(() => {

        if (! botoes) {
          return
        }

        botoes.forEach(botao => {
          botao.disabled = false
          botao.classList.remove('opacity-50')
        })
      }, 500);
    })
}