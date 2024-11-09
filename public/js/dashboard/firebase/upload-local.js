async function uploadImagemLocal(empresaId, artigoId, file, tipo = '') {
  try {

    if (! empresa) {
      throw new Error('Erro ao recuperar subdominio')
    }

    const formData = new FormData()
    formData.append('file', file)
    formData.append('empresaId', empresaId)
    formData.append('artigoId', artigoId)
    formData.append('tipo', tipo)

    const resposta = await fetch(baseUrl(`/${empresa}/d/upload-local`), {
      method: 'POST',
      body: formData
    })

    if (! resposta.ok) {
      throw new Error('Erro ao fazer upload do arquivo')
    }

    const resultado = await resposta.json()

    return resultado.ok
  }
  catch (error) {
    console.error('Erro ao fazer upload da imagem:', error)
    return false
  }
}

async function uploadMultiplasImagensLocal(empresaId, artigoId, imagensParaUpload) {
  try {

    if (! empresa) {
      throw new Error('Erro ao recuperar subdominio')
    }

    let urls = []
    for (const { file, type } of imagensParaUpload) {
      urls.push(await uploadImagemLocal(empresaId, artigoId, file, type))
    }

    return urls
  }
  catch (error) {
    console.error('Erro ao fazer upload da imagem:', error)
    return false
  }
}

async function substituirImagemLocal(empresaId, artigoId, file, caminhoImagemAtual) {
  try {
    let apagarImagem = await apagarImagemLocal(caminhoImagemAtual)

    if (! apagarImagem) {
      throw new Error('Erro ao apagar arquivo')
    }

    let uploadImagem = await uploadImagemLocal(empresaId, artigoId, file)

    if (! uploadImagem) {
      throw new Error('Erro ao fazer upload do arquivo')
    }

    return uploadImagem
  }
  catch (error) {
    console.error('Erro ao substituir a imagem:', error)
    return false
  }
}

async function apagarImagemLocal(caminhoImagem) {
  try {

    if (! empresa) {
      throw new Error('Erro ao recuperar subdominio')
    }

    const resposta = await fetch(baseUrl(`/${empresa}/d/apagar-local`), {
      method: 'POST',
      body: JSON.stringify({ caminhoImagem: caminhoImagem }),
      headers: {
        'Content-Type': 'application/json'
      }
    })

    if (! resposta.ok) {
      throw new Error('Erro ao apagar arquivo')
    }

    const resultado = await resposta.json()

    return resultado.ok
  }
  catch (error) {
    console.error('Erro ao apagar imagem:', error)
    return false
  }
}

async function apagarImgsArtigoLocal(caminhoPasta) {
  try {

    if (! empresa) {
      throw new Error('Erro ao recuperar subdominio')
    }

    const resposta = await fetch(baseUrl(`/${empresa}/d/apagar-artigos-local`), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ caminhoPasta })
    })

    if (! resposta.ok) {
      throw new Error('Erro ao tentar apagar os arquivos locais')
    }

    const resultado = await resposta.json()

    return resultado.ok
  }
  catch (error) {
    console.error('Erro ao apagar os arquivos locais:', error)
    return false
  }
}

export { uploadImagemLocal, uploadMultiplasImagensLocal, substituirImagemLocal, apagarImagemLocal, apagarImgsArtigoLocal }