import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.13.0/firebase-app.js'
import { getStorage, ref, uploadBytes, getDownloadURL } from 'https://www.gstatic.com/firebasejs/10.13.0/firebase-storage.js'

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

export async function uploadImageToFirebase(file) {
  try {
    const storageRef = ref(storage, `images/${file.name}`)
    const snapshot = await uploadBytes(storageRef, file)
    const downloadURL = await getDownloadURL(snapshot.ref)
  
    return downloadURL
  } 
  catch (error) {
    console.error('Erro ao fazer upload da imagem para o Firebase:', error)
    throw error
  }
}