// ----------- CKEditor -----------
import {
	ClassicEditor,
	AccessibilityHelp,
	Alignment,
	Autosave,
	BalloonToolbar,
	Bold,
	Essentials,
	FontColor,
	Heading,
	HorizontalLine,
	Indent,
	Italic,
  Link,
	List,
	ListProperties,
	Paragraph,
	RemoveFormat,
	SelectAll,
	Strikethrough,
	Table,
	TableCaption,
	TableCellProperties,
	TableColumnResize,
	TableProperties,
	TableToolbar,
	TodoList,
	Underline,
	Undo
} from 'ckeditor5';

import translations from 'ckeditor5/translations/pt-br.js';

const editorConfig = {
	toolbar: {
		items: [
			'undo',
			'redo',
			'|',
			'selectAll',
			'|',
			'heading',
			'|',
			'fontColor',
			'|',
			'bold',
			'italic',
			'underline',
			'strikethrough',
			'removeFormat',
			'|',
			'horizontalLine',
			'link',
			'insertTable',
			'|',
			'alignment',
			'|',
			'bulletedList',
			'numberedList',
			'todoList',
			'outdent',
			'indent',
			'|',
			'accessibilityHelp'
		],
		shouldNotGroupWhenFull: false
	},
	plugins: [
		AccessibilityHelp,
		Alignment,
		Autosave,
		BalloonToolbar,
		Bold,
		Essentials,
		FontColor,
		Heading,
		HorizontalLine,
		Indent,
		Italic,
		Link,
		List,
		ListProperties,
		Paragraph,
		RemoveFormat,
		SelectAll,
		Strikethrough,
		Table,
		TableCaption,
		TableCellProperties,
		TableColumnResize,
		TableProperties,
		TableToolbar,
		TodoList,
		Underline,
		Undo
	],
	balloonToolbar: ['bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList'],
	heading: {
		options: [
			{
				model: 'paragraph',
				title: 'Paragraph',
				class: 'ck-heading_paragraph'
			},
			{
				model: 'heading1',
				view: 'h1',
				title: 'Heading 1',
				class: 'ck-heading_heading1'
			},
			{
				model: 'heading2',
				view: 'h2',
				title: 'Heading 2',
				class: 'ck-heading_heading2'
			},
			{
				model: 'heading3',
				view: 'h3',
				title: 'Heading 3',
				class: 'ck-heading_heading3'
			},
		]
	},
	initialData: '',
	language: 'pt-br',
	link: {
    forceSimpleAmpersand: true,
		addTargetToExternalLinks: true,
		defaultProtocol: 'https://',
		decorators: {
			toggleDownloadable: {
				mode: 'manual',
				label: 'Downloadable',
				attributes: {
					download: 'file'
				}
			}
		}
	},
	list: {
		properties: {
			styles: true,
			startIndex: true,
			reversed: true
		}
	},
	placeholder: 'Digite ou cole seu conteúdo aqui!',
	table: {
		contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
	},
	translations: [translations]
};

let editorInstances = {}

document.addEventListener('DOMContentLoaded', () => {
  const textareas = document.querySelectorAll('textarea.ckeditor')
  
  textareas.forEach(textarea => {
    ClassicEditor.create(textarea, editorConfig)
      .then(editor => {
        editorInstances[textarea.name] = editor
        console.log('CKEditor 5 initialized', editor)
      })
      .catch(error => {
        console.error('There was a problem initializing CKEditor 5', error)
      })
  })
})

// ----------- Adicionar bloco de conteúdo -----------
const btnTextoAdicionar = document.querySelector('.conteudo-btn-texto-adicionar')
const btnImagemAdicionar = document.querySelector('.conteudo-btn-imagem-adicionar')
const btnVideoAdicionar = document.querySelector('.conteudo-btn-video-adicionar')

const modalConteudoTextoAdicionar = document.querySelector('.modal-conteudo-texto-adicionar')
const btnCancelarModalAdicionarTexto = document.querySelector('.modal-texto-adicionar-btn-cancelar')

const modalConteudoImagemAdicionar = document.querySelector('.modal-conteudo-imagem-adicionar')
const adicionarImagemEscolher = document.querySelector('.conteudo-adicionar-imagem-escolher')
const adicionarTextoImagemEscolher = document.querySelector('.conteudo-txt-imagem-adicionar-escolher')
const btnAdicionarImagemEscolher = document.querySelector('.conteudo-btn-imagem-adicionar-escolher')
const btnCancelarModalAdicionarImagem = document.querySelector('.modal-conteudo-imagem-btn-cancelar-adicionar')

const modalConteudoVideoAdicionar = document.querySelector('.modal-conteudo-video-adicionar')
const btnCancelarModalAdicionarVideo = document.querySelector('.modal-conteudo-video-btn-cancelar-adicionar')

if (btnTextoAdicionar) {
  btnTextoAdicionar.addEventListener('click', () => {
    modalConteudoTextoAdicionar.showModal()
  })
}

if (btnImagemAdicionar) {
  btnImagemAdicionar.addEventListener('click', () => {
    const imgElemento = modalConteudoImagemAdicionar.querySelector('img')

    imgElemento.classList.add('opacity-100')
    imgElemento.classList.remove('opacity-0')
    modalConteudoImagemAdicionar.showModal()

    if (btnAdicionarImagemEscolher) {
      btnAdicionarImagemEscolher.addEventListener('click', () => {
        adicionarImagemEscolher.click()
      })
    }

    adicionarImagemEscolher.addEventListener('change', (event) => {
      const anexo = event.target.files[0]
      const blocoImagem = modalConteudoImagemAdicionar.querySelector('.bloco-imagem')

      if (anexo) {
        const objetoReader = new FileReader()

        objetoReader.onload = (e) => {
          imgElemento.src = e.target.result
          blocoImagem.classList.remove('hidden')
        }

        adicionarTextoImagemEscolher.textContent = anexo.name
        objetoReader.readAsDataURL(anexo)
      }
    })

    adicionarTextoImagemEscolher.textContent = 'Escolher imagem'
  })
}

if (btnVideoAdicionar) {
  btnVideoAdicionar.addEventListener('click', () => {
    modalConteudoVideoAdicionar.showModal()
  })
}

if (btnCancelarModalAdicionarTexto) {
  btnCancelarModalAdicionarTexto.addEventListener('click', () => {
    modalConteudoTextoAdicionar.close()
  })
}

if (btnCancelarModalAdicionarVideo) {
  btnCancelarModalAdicionarVideo.addEventListener('click', () => {
    modalConteudoVideoAdicionar.close()
  })
}

if (btnCancelarModalAdicionarImagem) {
  btnCancelarModalAdicionarImagem.addEventListener('click', () => fecharModalAdicionarImagem())
}

document.addEventListener('keydown', (event) => {
  
  if (event.key === 'Escape' || event.keyCode === 27 && modalConteudoImagemAdicionar.open) {
    fecharModalAdicionarImagem()
  }
})

function fecharModalAdicionarImagem() {
  modalConteudoImagemAdicionar.querySelector('img').src = ''
  modalConteudoImagemAdicionar.close()
}

// ----------- Editar bloco de conteúdo -----------
const btnsConteudoEditar = document.querySelectorAll('.js-dashboard-conteudo-editar')

const modalConteudoTextoEditar = document.querySelector('.modal-conteudo-texto-editar')
const editarTextoTitulo = document.querySelector('#conteudo-editar-texto-titulo')
const btnCancelarModalEditarTexto = document.querySelector('.modal-texto-editar-btn-cancelar')
const formularioEditarTexto = document.querySelector('.modal-conteudo-texto-editar > form')

const modalConteudoImagemEditar = document.querySelector('.modal-conteudo-imagem-editar')
const editarImagemTitulo = document.querySelector('#conteudo-editar-imagem-titulo')
const editarImagemEscolher = document.querySelector('.conteudo-editar-imagem-escolher')
const editarTextoImagemEscolher = document.querySelector('.conteudo-txt-imagem-editar-escolher')
const btnEditarImagemEscolher = document.querySelector('.conteudo-btn-imagem-editar-escolher')
const btnCancelarModalEditarImagem = document.querySelector('.modal-conteudo-imagem-btn-cancelar')
const formularioEditarImagem = document.querySelector('.modal-conteudo-imagem-editar > form')

const modalConteudoVideoEditar = document.querySelector('.modal-conteudo-video-editar')
const editarVideoTitulo = document.querySelector('#conteudo-editar-video-titulo')
const editarVideoUrl = document.querySelector('#conteudo-editar-video-url')
const btnCancelarModalEditarVideo = document.querySelector('.modal-conteudo-video-btn-cancelar')
const formularioEditarVideo = document.querySelector('.modal-conteudo-video-editar > form')

if (btnsConteudoEditar) {
  btnsConteudoEditar.forEach(conteudo => {
    conteudo.addEventListener('click', () => {
      
      if (conteudo.dataset.conteudoTipo == 1) {
        editarTextoTitulo.value = conteudo.dataset.conteudoTitulo

        const editor = editorInstances['conteudo']

        if (editor) {
          editor.setData(conteudo.dataset.conteudoConteudo)
        }
        else {
          console.error('CKEditor instance not found for the specified textarea.')
        }
        
        formularioEditarTexto.action = '/conteudo/' + conteudo.dataset.conteudoId
        modalConteudoTextoEditar.showModal()
      }
      else if (conteudo.dataset.conteudoTipo == 2) {
        editarImagemTitulo.value = conteudo.dataset.conteudoTitulo
        formularioEditarImagem.action = '/conteudo/' + conteudo.dataset.conteudoId
        
        const imgElemento = modalConteudoImagemEditar.querySelector('img')
        
        imgElemento.src = '/' + conteudo.dataset.conteudoUrl
        imgElemento.classList.add('opacity-100')
        imgElemento.classList.remove('opacity-0')
        modalConteudoImagemEditar.showModal()

        if (btnEditarImagemEscolher) {
          btnEditarImagemEscolher.addEventListener('click', () => {
            editarImagemEscolher.click()
          })
        }

        editarImagemEscolher.addEventListener('change', (event) => {
          const anexo = event.target.files[0]

          if (anexo) {
            const objetoReader = new FileReader()

            objetoReader.onload = (e) => {
              imgElemento.src = e.target.result
              imgElemento.classList.remove('opacity-0')
              imgElemento.classList.add('opacity-100')
            }

            editarTextoImagemEscolher.textContent = anexo.name
            objetoReader.readAsDataURL(anexo)
          }
        })

        editarTextoImagemEscolher.textContent = 'Alterar imagem'
      }
      else if (conteudo.dataset.conteudoTipo == 3) {
        editarVideoTitulo.value = conteudo.dataset.conteudoTitulo
        editarVideoUrl.value = conteudo.dataset.conteudoUrl
        formularioEditarVideo.action = '/conteudo/' + conteudo.dataset.conteudoId
        modalConteudoVideoEditar.showModal()
      }
    })
  })
}

if (btnCancelarModalEditarTexto) {
  btnCancelarModalEditarTexto.addEventListener('click', () => {
    modalConteudoTextoEditar.close()
  })
}

if (btnCancelarModalEditarVideo) {
  btnCancelarModalEditarVideo.addEventListener('click', () => {
    modalConteudoVideoEditar.close()
  })
}

if (btnCancelarModalEditarImagem) {
  btnCancelarModalEditarImagem.addEventListener('click', () => fecharModalImagem())
}

document.addEventListener('keydown', (event) => {
  
  if (event.key === 'Escape' || event.keyCode === 27 && modalConteudoImagemEditar.open) {
    fecharModalImagem()
  }
})

function fecharModalImagem() {
  modalConteudoImagemEditar.querySelector('img').src = ''
  modalConteudoImagemEditar.close()
}

// ----------- Remover bloco de conteúdo -----------
let conteudoId = null
const btnsConteudoRemover = document.querySelectorAll('.js-dashboard-conteudo-remover')
const modalConteudoRemover = document.querySelector('.modal-conteudo-remover')
const btnModalConteudoRemover = document.querySelector('.modal-conteudo-btn-remover')
const btnModalConteudoCancelar = document.querySelector('.modal-conteudo-btn-cancelar')

if (btnsConteudoRemover) {
  btnsConteudoRemover.forEach(conteudo => {
    conteudo.addEventListener('click', () => {
      conteudoId = conteudo.dataset.conteudoId
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

  fetch(`/conteudo/${conteudoId}`, { method: 'DELETE' })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.linhasAfetadas == 1) {
        location.reload()
      }
      else if (resposta.erro) {
        throw new Error(resposta.erro)
      }
      else {
        throw new Error('Erro ao remover conteudo')
      }
    })
    .catch(error => {
      location.reload()
    })
}

// ----------- Reoganizar blocos de conteúdo -----------
document.addEventListener('DOMContentLoaded', function () {
  const bloco = document.querySelector('.conteudo-blocos')
  
  if (! bloco) {
    return
  }
  
  Sortable.create(bloco, {
    animation: 150,
    handle: '.handle',
    onEnd: function () {
      const ordem = []
  
      document.querySelectorAll('.conteudo-bloco').forEach(function (item, index) {
        ordem.push({
          id: item.dataset.conteudoId,
          ordem: index
        })
      })
  
      if (! ordem) {
        return
      }

      fetch(`/conteudo/ordem`, {
         method: 'PUT',
         body: JSON.stringify(ordem)
        })
        .then(resposta => resposta.json())
        .then(resposta => {
          
          if (resposta.linhasAfetadas > 0) {
            console.log(resposta.linhasAfetadas + ' conteúdos reorganizados')
          }
          else if (resposta.erro) {
            throw new Error(resposta.erro)
          }
          else {
            throw new Error('Erro ao reorganizar conteudo')
          }
        })
        .catch(error => {
          location.reload()
        })
    }
  })
})