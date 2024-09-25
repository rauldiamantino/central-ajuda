import edjsHTML from "editorjs-html"

document.addEventListener('DOMContentLoaded', async () => {
  try {
    const edjsParser = edjsHTML()
    const conteudoDiv = document.querySelector('#publico-editorjs')

    if (! conteudoDiv) {
      return
    }

    let dataConteudo = conteudoDiv.dataset.conteudo
    dataConteudo = dataConteudo ? JSON.parse(dataConteudo) : {}

    const htmlConteudo = edjsParser.parse(dataConteudo)

    htmlConteudo.forEach(elemento => {
      const novoElemento = document.createElement('div')

      novoElemento.innerHTML = elemento
      conteudoDiv.appendChild(novoElemento)
    })

  }
  catch (error) {
    console.error('Erro ao carregar o editorjs-html:', error)
  }
})
