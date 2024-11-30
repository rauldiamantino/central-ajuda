document.addEventListener('DOMContentLoaded', function () {
  const containerEmpresa = document.querySelector('.container-empresa')
  const efeitoLoader = document.querySelector('.efeito-loader')

  if (! containerEmpresa || ! efeitoLoader) {
    return
  }

  setTimeout(() => {
    efeitoLoader.classList.add('hidden')
    containerEmpresa.classList.remove('hidden')
  }, 300);

  mascararCnpj()
  mascararCelular()
})

const assinarPlano = (plano) => {
  const modal = document.querySelector('.modal-plano-assinar')
  const btnAssinar = modal.querySelector('.modal-plano-btn-assinar')
  const btnFechar = modal.querySelector('.modal-plano-btn-fechar')

  if (! modal || ! btnAssinar || ! btnFechar) {
    return false;
  }

  modal.showModal()

  const clicouAssinar = () => {
    window.location.href= `/${empresa}/d/assinaturas/gerar?plano=${plano}`;
  }

  const clicouFechar = () => {
    modal.close()
    removerListeners()
  }

  const removerListeners = () => {
    btnAssinar.removeEventListener('click', clicouAssinar)
    btnFechar.removeEventListener('click', clicouFechar)
  }

  btnAssinar.addEventListener('click', clicouAssinar)
  btnFechar.addEventListener('click', clicouFechar)
}

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