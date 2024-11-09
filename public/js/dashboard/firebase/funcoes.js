import { inicializarFirebase } from './inicializar.js'
import { uploadImagemLocal, uploadMultiplasImagensLocal, substituirImagemLocal, apagarImagemLocal, apagarImgsArtigoLocal } from './upload-local.js'

async function uploadImagem(empresaId, artigoId, file) {
  try {
    const hostLocal = window.location.hostname === 'localhost'

    if (hostLocal) {
      return await uploadImagemLocal(empresaId, artigoId, file)
    }

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
    const hostLocal = window.location.hostname === 'localhost'

    if (hostLocal) {
      return await uploadMultiplasImagensLocal(empresaId, artigoId, imagensParaUpload)
    }

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

      const snapshot = await uploadBytes(storageRef, file)
      const downloadURL = await getDownloadURL(snapshot.ref)
      downloadURLs.push(downloadURL)
    }

    return downloadURLs
  }
  catch (error) {
    console.error('Erro ao fazer upload das imagens:', error)
    throw new Error('Erro no upload das imagens')
  }
}

async function substituirImagem(empresaId, artigoId, file, $caminhoImagemAtual) {
  try {
    const hostLocal = window.location.hostname === 'localhost'

    if (hostLocal) {
      return await substituirImagemLocal(empresaId, artigoId, file, $caminhoImagemAtual)
    }

    const { storage, ref, uploadBytes, getDownloadURL, deleteObject } = await inicializarFirebase()

    if ($caminhoImagemAtual) {
      const oldImageRef = ref(storage, $caminhoImagemAtual)
      await deleteObject(oldImageRef)
    }

    let newImagePath = `imagens/empresa-${empresaId}/logo`

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
    const hostLocal = window.location.hostname === 'localhost'

    if (hostLocal) {
      return await apagarImagemLocal(caminhoImagem)
    }

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
    const hostLocal = window.location.hostname === 'localhost'

    if (hostLocal) {
      return await apagarImgsArtigoLocal(caminhoPasta)
    }

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