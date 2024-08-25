import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.13.0/firebase-app.js'
import { getStorage, ref, uploadBytes, getDownloadURL, getMetadata, deleteObject, listAll } from 'https://www.gstatic.com/firebasejs/10.13.0/firebase-storage.js'

const firebaseConfig = {
  apiKey: "AIzaSyBXAg4u_hFmkaEaqifkknJaD4Lnx42EvHE",
  authDomain: "central-ajuda-5f40a.firebaseapp.com",
  projectId: "central-ajuda-5f40a",
  storageBucket: "central-ajuda-5f40a.appspot.com",
  messagingSenderId: "83629854813",
  appId: "1:83629854813:web:3c99764aef3aba36a27db4"
}

const app = initializeApp(firebaseConfig)
const storage = getStorage()

export async function uploadImagem(empresaId, artigoId, file) {
  try {
    const storageRef = ref(storage, `imagens/empresa-${empresaId}/artigo-${artigoId}/${Date.now() % 100000}`)
    const snapshot = await uploadBytes(storageRef, file)

    return await getDownloadURL(snapshot.ref)
  } 
  catch (error) {
    console.error('Erro ao fazer upload da imagem para o Firebase:', error)
    throw error
  }
}

export async function substituirImagem(empresaId, artigoId, file, existingImagePath) {
  try {
    if (existingImagePath) {
      const oldImageRef = ref(storage, existingImagePath)
      await deleteObject(oldImageRef)
      console.log('Imagem antiga excluída com sucesso.')
    }

    const newImagePath = `imagens/empresa-${empresaId}/artigo-${artigoId}/${Date.now() % 100000}`
    let newImageRef = ref(storage, newImagePath)

    await uploadBytes(newImageRef, file)
    console.log('Nova imagem enviada com sucesso.')

    return await getDownloadURL(newImageRef)
  } 
  catch (error) {
    console.error('Erro ao processar a imagem:', error)
    throw error
  }
}

export async function apagarImagem(caminhoImagem) {
  const imagemRef = ref(storage, caminhoImagem)
  
  try {
    await getMetadata(imagemRef)
    await deleteObject(imagemRef)

    return true
  } 
  catch (error) {
    console.error('Erro ao tentar apagar o arquivo:', error)
    return false
  }
}

export async function apagarImgsArtigo(caminhoPasta) {
  const pastaRef = ref(storage, caminhoPasta)
  
  try {
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
    const todasDeletadas = resultados.every(result => result === true)

    return todasDeletadas
  } 
  catch (error) {
    console.error('Erro ao remover pasta no Firebase:', error)
    return false
  }
}