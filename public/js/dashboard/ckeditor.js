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
} from 'ckeditor5'

import translations from 'ckeditor5/translations/pt-br.js'

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
}

export let editorInstances = {}

document.addEventListener('DOMContentLoaded', () => {
  const textareas = document.querySelectorAll('textarea.ckeditor');

  textareas.forEach(textarea => {
    ClassicEditor.create(textarea, editorConfig)
      .then(editor => {
        editorInstances[textarea.name] = editor;

        const event = new CustomEvent('ckeditorInicializado', {
          detail: {
            name: textarea.name,
            editor: editor
          }
        });
        document.dispatchEvent(event);
        console.log('CKEditor 5 inicializado')
      })
      .catch(error => {
        console.error('There was a problem initializing CKEditor 5', error);
      });
  });
});
