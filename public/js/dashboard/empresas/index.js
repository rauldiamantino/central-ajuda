document.addEventListener('DOMContentLoaded', function () {
  mascararCnpj()
  mascararCelular()
})

const mascararCnpj = () => {
  const cnpj = document.querySelector('#empresa-editar-cnpj')

  if (! cnpj) {
    return
  }

  const cleaveCNPJ = new Cleave(cnpj, {
    delimiters: ['.', '.', '/', '-'],
    blocks: [2, 3, 3, 4, 2],
    numericOnly: true
  })
}

const mascararCelular = () => {
  const telefone = document.querySelector('#empresa-editar-telefone')

  if (! telefone) {
    return
  }

  const cleave = new Cleave(telefone, {
    phone: true,
    phoneRegionCode: 'BR'
  })
}