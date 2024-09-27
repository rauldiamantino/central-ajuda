let firebaseInicializado = false
let firebaseModulos = {}

async function inicializarFirebase() {

  if (! subdominio) {
    return
  }

  if (firebaseInicializado) {
    return firebaseModulos
  }

  try {
    const response = await fetch(`/${subdominio}/d/firebase`)
    const data = await response.json()
    const firebaseConfig = data.firebase

    if (firebaseConfig) {
      const { initializeApp } = await import('https://www.gstatic.com/firebasejs/10.13.0/firebase-app.js')
      const { getStorage, ref, uploadBytes, getDownloadURL, getMetadata, deleteObject, listAll } = await import('https://www.gstatic.com/firebasejs/10.13.0/firebase-storage.js')

      const app = initializeApp(firebaseConfig)
      const storage = getStorage()

      firebaseInicializado = true
      console.log('Firebase inicializado com sucesso.')

      return { app, storage, ref, uploadBytes, getDownloadURL, getMetadata, deleteObject, listAll }
    }

    throw data
  }
  catch (error) {
    console.error('Erro ao inicializar o Firebase:', error)
    throw error
  }
}

export { inicializarFirebase }