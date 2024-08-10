document.addEventListener('DOMContentLoaded', function () {
  const cnpj = document.querySelector('#empresa-editar-cnpj')

  if (! cnpj) {
    return
  }
  
  const cleaveCNPJ = new Cleave(cnpj, {
    delimiters: ['.', '.', '/', '-'],
    blocks: [2, 3, 3, 4, 2],
    numericOnly: true
  })
})
