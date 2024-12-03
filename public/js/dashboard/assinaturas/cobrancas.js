document.addEventListener("DOMContentLoaded", function() {
  let divAssinatura = document.querySelector('.tabela-assinatura')
  let divBuscando = document.querySelector('.div-buscando')

  if (! divAssinatura) {
    return
  }

  let assinaturaId = divAssinatura.dataset.assinaturaId
  let cobrancaOk = false

  if (! divBuscando) {
    return
  }

  if (! assinaturaId) {
    return
  }

  const processarDados = (empresa) => {
    fetch(`/${empresa}/d/buscar_cobrancas?asaas_id=${assinaturaId}`)
      .then(response => response.json())
      .then(data => {

        if (! data.erro) {
          data.forEach(cobranca => {
            let valor = cobranca.value ? cobranca.value : ''
            let status = cobranca.status ? cobranca.status : ''
            let vencimento = cobranca.dueDate ? cobranca.dueDate : ''
            let pagamentoLink = cobranca.invoiceUrl ? cobranca.invoiceUrl : ''
            let descricaoPlano = cobranca.description ? cobranca.description : ''
            let botao = ''

            if (valor == '' || status == '' || vencimento == '' || pagamentoLink == '' || descricaoPlano == '') {
              return
            }

            valor = valor.toFixed(2).replace('.', ',')

            botao = 'Pagar'
            status = 'Pendente'
            classeStatus = 'bg-orange-50 text-orange-60'

            if (status == 'CONFIRMED') {
              botao = ''
              status = 'Pago'
              classeStatus = 'bg-green-50 text-green-60'
            }
            else if (status == 'OVERDUE') {
              botao = 'Pagar'
              status = 'Vencido'
              classeStatus = 'bg-red-50 text-red-60'
            }

            criarLinhaTabela(vencimento, descricaoPlano, classeStatus, status, valor, pagamentoLink, botao)

            cobrancaOk = true
            divBuscando.classList.add('hidden')
          })
        }

        if (cobrancaOk == false) {
          divBuscando.textContent = 'Nenhuma cobrança encontrada'
          divBuscando.classList.remove('opacity-50')
          divBuscando.classList.add('opacity-95')
        }
      })
      .catch(error => console.error('Erro ao obter dados:', error))
  }

  const criarLinhaTabela = (vencimento, descricao, classeStatus, status, valor, pagamentoLink, botao) => {
    const tr = document.createElement('tr')
    tr.classList.add('hover:bg-gray-100')

    tr.innerHTML = `
      <td class="py-6 px-6">${vencimento}</td>
      <td class="py-6 px-4 font-semibold">${descricao}</td>
      <td class="py-6 px-4">
        <div class="flex items-center gap-2">
          <span class="px-3 py-1 text-xs rounded-full ${classeStatus}">${status}</span>
        </div>
      </td>
      <td class="py-6 px-4 text-green-700 whitespace-nowrap">R$ ${valor}</td>
      <td class="py-6 px-4 font-semibold text-blue-800">
        <a href="${pagamentoLink}" target="_blank" class="font-semibold hover:underline">${botao}</a>
      </td>
    `

    divAssinatura.appendChild(tr)
  }

  processarDados(empresa)
})