import {
	ClassicEditor,
	AccessibilityHelp,
	Alignment,
	Autoformat,
	Autosave,
	BlockQuote,
	BlockToolbar,
	Bold,
	Code,
	CodeBlock,
	Essentials,
	FindAndReplace,
	FontBackgroundColor,
	FontColor,
	FontFamily,
	FontSize,
	Heading,
	Highlight,
	HorizontalLine,
	Indent,
	IndentBlock,
	Italic,
	Link,
	List,
	ListProperties,
	// MediaEmbed,
	Paragraph,
	PasteFromOffice,
	RemoveFormat,
	SelectAll,
	Strikethrough,
	Table,
	TableCaption,
	TableCellProperties,
	TableColumnResize,
	TableProperties,
	TableToolbar,
	TextTransformation,
	TodoList,
	Underline,
	Undo
} from 'ckeditor5';

import translations from '/ckeditor/ckeditor5/translations/pt-br.js';

const editorConfig = {
  ui: {
    poweredBy: {
      position: 'inside',
      side: 'left',
      label: '',
    },
  },
  enterMode: 'ENTER_P',
  shiftEnterMode: 'ENTER_BR',
	toolbar: {
		items: [
			'undo',
			'redo',
			'|',
			'findAndReplace',
			'|',
			'heading',
			'|',
			'fontSize',
			'fontFamily',
			'fontColor',
			'fontBackgroundColor',
			'|',
			'bold',
			'italic',
			'underline',
			'strikethrough',
			'code',
			'removeFormat',
			'|',
			'horizontalLine',
			'link',
			// 'mediaEmbed',
			'insertTable',
			'highlight',
			'blockQuote',
			'codeBlock',
			'|',
			'alignment',
			'|',
			'bulletedList',
			'numberedList',
			'todoList',
			'outdent',
			'indent'
		],
		shouldNotGroupWhenFull: false
	},
	plugins: [
		AccessibilityHelp,
		Alignment,
		Autoformat,
		Autosave,
		BlockQuote,
		BlockToolbar,
		Bold,
		Code,
		CodeBlock,
		Essentials,
		FindAndReplace,
		FontBackgroundColor,
		FontColor,
		FontFamily,
		FontSize,
		Heading,
		Highlight,
		HorizontalLine,
		Indent,
		IndentBlock,
		Italic,
		Link,
		List,
		ListProperties,
		// MediaEmbed,
		Paragraph,
		PasteFromOffice,
		RemoveFormat,
		SelectAll,
		Strikethrough,
		Table,
		TableCaption,
		TableCellProperties,
		TableColumnResize,
		TableProperties,
		TableToolbar,
		TextTransformation,
		TodoList,
		Underline,
		Undo
	],
	blockToolbar: [
		'fontSize',
		'fontColor',
		'fontBackgroundColor',
		'|',
		'bold',
		'italic',
		'|',
		'link',
		'insertTable',
		'|',
		'bulletedList',
		'numberedList',
		'outdent',
		'indent'
	],
	fontFamily: {
		supportAllValues: true
	},
	fontSize: {
		options: [10, 12, 14, 'default', 18, 20, 22],
		supportAllValues: true
	},
	heading: {
		options: [
			{
				model: 'paragraph',
				title: 'Paragraph',
				class: 'ck-heading_paragraph'
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
			{
				model: 'heading4',
				view: 'h4',
				title: 'Heading 4',
				class: 'ck-heading_heading4'
			},
		]
	},
	initialData: '',
	language: 'pt-br',
	link: {
		defaultProtocol: 'https://',
		decorators: {
			toggleDownloadable: {
				mode: 'manual',
				label: 'Abrir em uma nova aba',
				attributes: {
          target: '_blank',
          rel: 'noopener noreferrer',
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

export let editorInstances = {}

document.addEventListener('DOMContentLoaded', () => {
  const textareas = document.querySelectorAll('textarea.ckeditor')

  textareas.forEach(textarea => {
    const conteudoId = textarea.dataset.conteudoId

    ClassicEditor.create(textarea, editorConfig)
      .then(editor => {
        editorInstances[ conteudoId ] = editor

        const event = new CustomEvent('ckeditorInicializado', {
          detail: {
            conteudoId: conteudoId,
            editor: editor
          }
        })

        document.dispatchEvent(event)

        if (conteudoId) {
          console.log('CKEditor 5 inicializado para o conteúdoId:', conteudoId)
        }
        else {
          console.log('CKEditor 5 inicializado')
        }
      })
      .catch(error => {
        console.error('There was a problem initializing CKEditor 5', error)
      })
  })
})
