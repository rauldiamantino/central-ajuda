import edjsHTML from "editorjs-html"

document.addEventListener('DOMContentLoaded', async () => {
  try {
    const edjsParser = edjsHTML()
    const conteudoDivs = document.querySelectorAll('.publico-editorjs')

    if (! conteudoDivs) {
      return
    }

    conteudoDivs.forEach(conteudoDiv => {
      let dataConteudo = conteudoDiv.dataset.conteudo

      if (dataConteudo && ! dataConteudo.startsWith('{') && verificaHtml(dataConteudo)) {
        dataConteudo = {
          blocks: htmlParaBlocosEditorjs(dataConteudo)
        }
      }

      if (typeof dataConteudo === 'string') {
        dataConteudo = dataConteudo ? JSON.parse(dataConteudo) : {}
      }

      const htmlConteudo = edjsParser.parse(dataConteudo)

      htmlConteudo.forEach(elemento => {
        const novoElemento = document.createElement('div')

        novoElemento.innerHTML = elemento
        conteudoDiv.appendChild(novoElemento)
      })
    })
  }
  catch (error) {
    console.error('Erro ao carregar o editorjs-html:', error)
  }

  function verificaHtml(elemento) {
    const padrao = /<\/?[a-z][\s\S]*>/i

    return padrao.test(elemento)
  }

  function htmlParaBlocosEditorjs(html) {
    const tempDiv = document.createElement('div')
    tempDiv.innerHTML = html

    const blocos = []

    tempDiv.childNodes.forEach(node => {
      if (node.nodeType === Node.ELEMENT_NODE) {
        switch (node.tagName.toLowerCase()) {
          case 'p':
            blocos.push({
              type: 'paragraph',
              data: {
                text: processText(node)
              }
            })
            break
          case 'h1':
          case 'h2':
          case 'h3':
            blocos.push({
              type: 'header',
              data: {
                text: processText(node),
                level: parseInt(node.tagName.substring(1), 10)
              }
            })
            break
          case 'ul':
          case 'ol':
            const items = Array.from(node.querySelectorAll('li')).map(li => processText(li))

            blocos.push({
              type: 'list',
              data: {
                style: node.tagName.toLowerCase() === 'ul' ? 'unordered' : 'ordered',
                items: items
              }
            })
            break
          default:
            console.warn(`Elemento ${node.tagName} não suportado`)
        }
      }
    })

    return blocos
  }

  function processText(node) {
    // Processa o texto para lidar com formatações como negrito, itálico e links
    const childNodes = Array.from(node.childNodes)
    const result = childNodes.map(child => {
      if (child.nodeType === Node.TEXT_NODE) {
        return child.textContent // Texto simples
      } else if (child.nodeType === Node.ELEMENT_NODE) {
        switch (child.tagName.toLowerCase()) {
          case 'strong':
            return `**${child.textContent}**` // Negrito com markdown
          case 'em':
            return `*${child.textContent}*` // Itálico com markdown
          case 'a':
            return `<a href="${child.href}" target="_blank">${child.textContent}</a>` // Link
          default:
            return child.innerHTML // Outros elementos, se necessário
        }
      }
      return ''
    })

    return result.join('') // Juntar os elementos processados
  }
})
