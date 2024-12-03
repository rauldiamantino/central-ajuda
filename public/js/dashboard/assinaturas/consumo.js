document.addEventListener("DOMContentLoaded", function() {
  const divArmazenamento = document.querySelector('.armazenamento-geral')
  const divNotificacaoEspaco = document.querySelector('.notificacao-espaco')

  if (! divArmazenamento || ! divNotificacaoEspaco) {
    return
  }

  processarDados(empresa)

  // Funções
  function formatarEspaco(espacoMb) {
    let espacoTexto

    if (espacoMb >= 1024) {
      let espacoGb = espacoMb / 1024
      espacoTexto = (espacoGb % 1 === 0) ? `${espacoGb}GB` : `${espacoGb.toFixed(2)}GB`
    }
    else {
      espacoTexto = `${espacoMb}MB`
    }

    return espacoTexto
  }

  function atualizarBarraProgresso(consumoTotalMb, maximoMb) {
    let consumoTotalPercentual

    if (maximoMb === 0) {
      consumoTotalPercentual = 100
    }
    else {
      consumoTotalPercentual = (consumoTotalMb / maximoMb) * 100
    }

    if (consumoTotalPercentual >= 100) {
      divNotificacaoEspaco.classList.remove('hidden')
    }
    else {
      divNotificacaoEspaco.classList.add('hidden')
    }

    let barraProgresso = document.querySelector(".barra-progresso")
    barraProgresso.classList.remove("bg-blue-600", "bg-yellow-600", "bg-red-600", "bg-red-700")

    if (consumoTotalMb > maximoMb) {
      barraProgresso.classList.add("bg-red-700")
    }
    else if (consumoTotalPercentual >= 80) {
      barraProgresso.classList.add("bg-red-600")
    }
    else if (consumoTotalPercentual >= 50) {
      barraProgresso.classList.add("bg-yellow-600")
    }
    else {
      barraProgresso.classList.add("bg-blue-600")
    }

    barraProgresso.style.width = '0%'

    setTimeout(() => {
      barraProgresso.style.width = consumoTotalPercentual + "%"
    }, 10)
  }

  function atualizarTextoEspaco(consumoTotalMb, maximoMb) {
    let espacoUtilizadoTexto = document.querySelector(".espaco-utilizado")

    let espacoUtilizadoTextoExibido = formatarEspaco(consumoTotalMb)
    let espacoTotalTextoExibido = formatarEspaco(maximoMb)

    espacoUtilizadoTexto.textContent = `${espacoUtilizadoTextoExibido} de ${espacoTotalTextoExibido}`
    espacoUtilizadoTexto.classList.remove('opacity-50')
  }

  function processarDados(empresa) {
    fetch(`/${empresa}/d/calcular_consumo`)
      .then(response => response.json())
      .then(data => {
        let maximoMb = data.maximo
        let consumoTotalMb = data.total

        atualizarTextoEspaco(consumoTotalMb, maximoMb)
        atualizarBarraProgresso(consumoTotalMb, maximoMb)
      })
      .catch(error => console.error('Erro ao obter dados:', error))
  }
})