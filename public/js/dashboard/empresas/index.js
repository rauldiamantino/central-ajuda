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

const buscarAssinatura = async () => {
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

  try {
    const resposta = await fetch(baseUrl(`/${empresa}/d/assinatura`), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        assinatura_id: assinaturaId.dataset.empresaAssinatura
      }),
    })

    const dados = await resposta.json()

    if (dados.ok) {
      aplicarAssinatura(dados.ok)
    }
    else if (dados.erro) {
      throw new Error(dados.erro);
    }
    else {
      throw new Error('Erro ao buscar assinatura');
    }
  }
  catch(error) {
    console.log(error)
  }
  finally {
    desativarLoader()
  }


  // Funções
  function aplicarAssinatura(assinatura) {
    const assinaturaStatus = document.querySelector('.empresa-assinatura-status')
    const assinaturaDataInicio = document.querySelector('.empresa-assinatura-data-inicio')
    const assinaturaPeriodoCorrente = document.querySelector('.empresa-assinatura-periodo-corrente')
    const assinaturaPlanoNome = document.querySelector('.empresa-assinatura-plano-nome')
    const assinaturaPlanoValor = document.querySelector('.empresa-assinatura-plano-valor')

    if (! assinatura.current_period_start) {
      return
    }

    if (! assinatura.current_period_end) {
      return
    }

    let dataInicio = traduzirDataTimestamp(assinatura.current_period_start)
    let dataFim = traduzirDataTimestamp(assinatura.current_period_end)

    if (dataInicio && dataFim) {
      assinaturaPeriodoCorrente.innerText = `${dataInicio} a ${dataFim}`
    }
    else {
      assinaturaPeriodoCorrente.innerText = ''
    }

    let status = assinatura.status ? assinatura.status : ''

    if (status == 'active') {
      status = 'Ativa'
    }

    if (status == 'canceled') {
      status = 'Cancelada'
    }

    assinaturaStatus.innerText = status ? status : ''
    assinaturaDataInicio.innerText = assinatura.start_date ? traduzirDataTimestamp(assinatura.start_date) : ''
    assinaturaPlanoNome.innerText = assinatura.plano_nome ? assinatura.plano_nome : ''

    if (! assinatura.plan.amount) {
      assinaturaPlanoValor = ''
    }
    else {
      valor = converterInteiroParaDecimal(assinatura.plan.amount)
      assinaturaPlanoValor.innerText = converterParaReais(valor)
    }
  }

  function desativarLoader() {
    efeitoLoader.classList.add('hidden')
  }

  function ativarLoader() {
    efeitoLoader.classList.remove('hidden')
  }

  function traduzirDataTimestamp(timestamp) {
    const data = new Date(timestamp * 1000)

    const dataFormatada = data.toLocaleDateString('pt-BR', {
      day: 'numeric',
      month: 'short',
    })

    return dataFormatada
  }
}