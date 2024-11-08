import { inicializarFirebase } from './inicializar.js'

async function uploadImagem(empresaId, artigoId, file) {
  try {
    const { storage, ref, uploadBytes, getDownloadURL } = await inicializarFirebase()

    let storageRef

    if (artigoId) {
      storageRef = ref(storage, `imagens/empresa-${empresaId}/artigo-${artigoId}/${Date.now() % 100000}`)
    }
    else {
      storageRef = ref(storage, `imagens/empresa-${empresaId}/logo`)
    }

    const snapshot = await uploadBytes(storageRef, file)
    const downloadURL = await getDownloadURL(snapshot.ref)

    return downloadURL
  }
  catch (error) {
    console.error('Erro ao fazer upload da imagem para o Firebase: ', error)
    return false
  }
}

async function uploadMultiplasImagens(empresaId, artigoId, imagensParaUpload) {
  try {
    const { storage, ref, uploadBytes, getDownloadURL } = await inicializarFirebase()

    const downloadURLs = []

    for (const { file, type } of imagensParaUpload) {
      let storageRef

      if (type === 'favicon') {
        storageRef = ref(storage, `imagens/empresa-${empresaId}/favicon`)
      }
      else if (type === 'logo') {
        storageRef = ref(storage, `imagens/empresa-${empresaId}/logo`)
      }
      else if (artigoId) {
        storageRef = ref(storage, `imagens/empresa-${empresaId}/artigo-${artigoId}/${Date.now() % 100000}`)
      }

      // Faz o upload do arquivo
      const snapshot = await uploadBytes(storageRef, file)
      const downloadURL = await getDownloadURL(snapshot.ref)
      downloadURLs.push(downloadURL) // Adiciona a URL do arquivo à lista
    }

    return downloadURLs // Retorna as URLs dos arquivos carregados
  } catch (error) {
    console.error('Erro ao fazer upload das imagens:', error)
    throw new Error('Erro no upload das imagens') // Lança o erro se ocorrer algum problema
  }
}


async function substituirImagem(empresaId, artigoId, file, existingImagePath, favicon = false) {
  try {
    const { storage, ref, uploadBytes, getDownloadURL, deleteObject } = await inicializarFirebase()

    if (existingImagePath) {
      const oldImageRef = ref(storage, existingImagePath)
      await deleteObject(oldImageRef)
    }

    let newImagePath = `imagens/empresa-${empresaId}/logo`

    if (favicon) {
      newImagePath = `imagens/empresa-${empresaId}/favicon`
    }

    if (artigoId) {
      newImagePath = `imagens/empresa-${empresaId}/artigo-${artigoId}/${Date.now() % 100000}`
    }

    const newImageRef = ref(storage, newImagePath)

    await uploadBytes(newImageRef, file)
    console.log('Nova imagem enviada com sucesso.')

    return await getDownloadURL(newImageRef)
  }
  catch (error) {
    console.error('Erro ao processar a imagem:', error)
  }
}

async function apagarImagem(caminhoImagem) {
  try {
    const { storage, ref, getMetadata, deleteObject } = await inicializarFirebase()
    const imagemRef = ref(storage, caminhoImagem)

    await getMetadata(imagemRef)
    await deleteObject(imagemRef)

    return true
  }
  catch (error) {

    if (error.code == 'storage/object-not-found') {
      return true
    }

    console.error('Erro ao tentar apagar o arquivo:', error)
    return false
  }
}

async function apagarImgsArtigo(caminhoPasta) {
  try {
    const { storage, ref, listAll, deleteObject } = await inicializarFirebase()
    const pastaRef = ref(storage, caminhoPasta)
    const listaDeArquivos = await listAll(pastaRef)

    if (listaDeArquivos.items.length === 0) {
      return true
    }

    const promessasDeDelecao = listaDeArquivos.items.map(async (arquivoRef) => {
      try {
        await deleteObject(arquivoRef)
        return true
      }
      catch (error) {
        console.error('Erro ao remover arquivo:', arquivoRef.fullPath, error)
        return false
      }
    })

    const resultados = await Promise.all(promessasDeDelecao)
    return resultados.every(result => result === true)
  }
  catch (error) {
    console.error('Erro ao remover pasta no Firebase:', error)
    return false
  }
}

export { uploadImagem, uploadMultiplasImagens, substituirImagem, apagarImagem, apagarImgsArtigo }