import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.13.0/firebase-app.js'
import { getStorage, ref, uploadBytes, getDownloadURL, deleteObject } from 'https://www.gstatic.com/firebasejs/10.13.0/firebase-storage.js'

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
    const storageRef = ref(storage, `images/${Date.now() % 100000}`)
    const snapshot = await uploadBytes(storageRef, file)
    const downloadURL = await getDownloadURL(snapshot.ref)
  
    return downloadURL
  } 
  catch (error) {
    console.error('Erro ao fazer upload da imagem para o Firebase:', error)
    throw error
  }
}

export async function handleImageUploadAndReplace(file, existingImagePath) {
  try {
    if (existingImagePath) {
      const oldImageRef = ref(storage, existingImagePath);
      await deleteObject(oldImageRef);
      console.log('Imagem antiga excluída com sucesso.');
    }

    const newImagePath = `images/${Date.now() % 100000}`;
    let newImageRef = ref(storage, newImagePath);

    await uploadBytes(newImageRef, file);
    console.log('Nova imagem enviada com sucesso.');

    const downloadURL = await getDownloadURL(newImageRef);
    console.log('URL da nova imagem:', downloadURL);

    return downloadURL;
  } 
  catch (error) {
    console.error('Erro ao processar a imagem:', error);
    throw error;
  }
}