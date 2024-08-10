document.addEventListener('DOMContentLoaded', function () {
  var cleave = new Cleave('#usuario-editar-telefone', {
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