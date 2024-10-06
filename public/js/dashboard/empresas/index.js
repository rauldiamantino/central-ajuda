document.addEventListener('DOMContentLoaded', function () {
  mascararCnpj()
  mascararCelular()
  buscarAssinatura()
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

const buscarAssinatura = () => {
  const assinaturaId = document.querySelector('.empresa-bloco-assinatura')
  const efeitoLoader = document.querySelector('#efeito-loader-assinatura')

  if (! empresaId) {
    return
  }

  if (! efeitoLoader) {
    return
  }

  if (! assinaturaId) {
    return
  }

  if (! assinaturaId.dataset.empresaAssinatura) {
    return
  }

  ativarLoader()

  fetch(`/d/${empresaId}/assinatura`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        assinatura_id: assinaturaId.dataset.empresaAssinatura
      })
    })
    .then(resposta => resposta.json())
    .then(resposta => {

      if (resposta.ok) {
        aplicarAssinatura(resposta.ok)
        desativarLoader()
      }
      else if (resposta.erro) {
        throw new Error(resposta.erro)
      }
      else {
        throw new Error('Erro ao buscar assinatura')
      }
    })
    .catch(error => {
      console.log(error)
      desativarLoader()
    })

    function aplicarAssinatura(assinatura) {
      const assinaturaId = document.querySelector('.empresa-assinatura-id')
      const assinaturaStatus = document.querySelector('.empresa-assinatura-status')
      const assinaturaDataInicio = document.querySelector('.empresa-assinatura-data-inicio')
      const assinaturaPeriodoAtualInicio = document.querySelector('.empresa-assinatura-atual-inicio')
      const assinaturaPeriodoAtualFim = document.querySelector('.empresa-assinatura-atual-fim')
      const assinaturaClienteId = document.querySelector('.empresa-assinatura-cliente-id')

      assinaturaId.innerText = assinatura.id ? assinatura.id : '** vazio **'
      assinaturaStatus.innerText = assinatura.status ? assinatura.status : '** vazio **'
      assinaturaDataInicio.innerText = assinatura.start_date ? assinatura.start_date : '** vazio **'
      assinaturaPeriodoAtualInicio.innerText = assinatura.current_period_start ? assinatura.current_period_start : '** vazio **'
      assinaturaPeriodoAtualFim.innerText = assinatura.current_period_end ? assinatura.current_period_end : '** vazio **'
      assinaturaClienteId.innerText = assinatura.customer ? assinatura.customer : '** vazio **'
    }

    function desativarLoader() {
      efeitoLoader.classList.add('hidden')
    }

    function ativarLoader() {
      efeitoLoader.classList.remove('hidden')
    }
}