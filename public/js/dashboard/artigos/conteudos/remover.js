import { apagarImagem } from '../../firebase/funcoes.js'

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

const requisicaoConteudoRemover = async (conteudoId) => {

  if (! conteudoId) {
    return
  }

  if (! empresaId) {
    return
  }

  if (conteudoUrl && conteudoTipo == 2) {
    const apagar = await apagarImagem(conteudoUrl);

    if (apagar == false) {
      return;
    }
  }

  fetch(baseUrl(`/${empresa}/d/conteudo/${conteudoId}`), { method: 'DELETE' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.linhasAfetadas == 1) {
        window.location.href = window.location.href;
      }
      else if (resposta.erro) {
        throw new Error(resposta.erro)
      }
      else {
        throw new Error('Erro ao remover conteudo')
      }
    })
    .catch(error => {
      window.location.href = window.location.href;
    })
}