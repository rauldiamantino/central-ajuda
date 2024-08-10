document.addEventListener('DOMContentLoaded', function () {
  const telefone = document.querySelector('#usuario-editar-telefone')

  if (! telefone) {
    return
  }

  const cleave = new Cleave(telefone, {
    phone: true,
    phoneRegionCode: 'BR'
  })

  removerAutocomplete()
})

const removerAutocomplete = () => {
  let inputs = document.querySelectorAll('input[autocomplete="off"]')

  if (! inputs) {
    return
  }

  inputs.forEach(input => {
    input.setAttribute('disabled', 'disabled')

    setTimeout(function(){
      input.removeAttribute('disabled')
    }, 1000)
  })
}