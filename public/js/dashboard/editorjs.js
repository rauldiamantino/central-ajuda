import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';

let editor

function iniciarEditor(holderConteudo, dataConteudo = '') {

  if (editor) {
    editor.destroy()
  }

  if (verificaHtml(dataConteudo)) {
    dataConteudo = htmlParaBlocosEditorjs(dataConteudo)

    dataConteudo = {
      'blocks': [
        dataConteudo
      ]
    }
  }
console.log(dataConteudo)
  if (typeof dataConteudo === 'string') {
    dataConteudo = JSON.parse(dataConteudo)
  }

  editor = new EditorJS({
    holder: holderConteudo,

    tools: {
      header: {
        class: Header,
        inlineToolbar: true,
        config: {
          placeholder: 'Título'
        }
      },
    },

    onReady: () => {
      console.log('Editor.js está pronto!')
    },

    data: dataConteudo
  })
}

function verificaHtml(elemento) {
  const padrao = /<\/?[a-z][\s\S]*>/i

  return padrao.test(elemento)
}

function htmlParaBlocosEditorjs(html) {
  const tempDiv = document.createElement('div');
  tempDiv.innerHTML = html;

  const blocos = [];

  tempDiv.childNodes.forEach(node => {
    if (node.nodeType === Node.ELEMENT_NODE) {
      switch (node.tagName.toLowerCase()) {
        case 'p':
          blocos.push({
            type: 'paragraph',
            data: {
              text: processText(node)
            }
          });
          break;
        case 'h1':
        case 'h2':
        case 'h3':
          blocos.push({
            type: 'header',
            data: {
              text: processText(node),
              level: parseInt(node.tagName.substring(1), 10)
            }
          });
          break;
        case 'ul':
        case 'ol':
          const items = Array.from(node.querySelectorAll('li')).map(li => processText(li));

          blocos.push({
            type: 'list',
            data: {
              style: node.tagName.toLowerCase() === 'ul' ? 'unordered' : 'ordered',
              items: items
            }
          });
          break;
        default:
          console.warn(`Elemento ${node.tagName} não suportado`);
      }
    }
  });

  return blocos;
}

function processText(node) {
  // Processa o texto para lidar com formatações como negrito, itálico e links
  const childNodes = Array.from(node.childNodes);
  const result = childNodes.map(child => {
    if (child.nodeType === Node.TEXT_NODE) {
      return child.textContent; // Texto simples
    } else if (child.nodeType === Node.ELEMENT_NODE) {
      switch (child.tagName.toLowerCase()) {
        case 'strong':
          return `**${child.textContent}**`; // Negrito com markdown
        case 'em':
          return `*${child.textContent}*`; // Itálico com markdown
        case 'a':
          return `<a href="${child.href}" target="_blank">${child.textContent}</a>`; // Link
        default:
          return child.innerHTML; // Outros elementos, se necessário
      }
    }
    return '';
  });

  return result.join(''); // Juntar os elementos processados
}


export { iniciarEditor, editor}
