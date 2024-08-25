import { inicializarFirebase } from './inicializar.js'

async function uploadImagem(empresaId, artigoId, file) {
  try {
    const { storage, ref, uploadBytes, getDownloadURL } = await inicializarFirebase()
    const storageRef = ref(storage, `imagens/empresa-${empresaId}/artigo-${artigoId}/${Date.now() % 100000}`)
    const snapshot = await uploadBytes(storageRef, file)

    return await getDownloadURL(snapshot.ref)
  } 
  catch (error) {
    console.error('Erro ao fazer upload da imagem para o Firebase:', error)
    throw error
  }
}

async function substituirImagem(empresaId, artigoId, file, existingImagePath) {
  try {
    const { storage, ref, uploadBytes, getDownloadURL, deleteObject } = await inicializarFirebase()

    if (existingImagePath) {
      const oldImageRef = ref(storage, existingImagePath)
      await deleteObject(oldImageRef)
    }

    const newImagePath = `imagens/empresa-${empresaId}/artigo-${artigoId}/${Date.now() % 100000}`
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

export { uploadImagem, substituirImagem, apagarImagem, apagarImgsArtigo }