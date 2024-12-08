const imagem2Webp = async (file) => {
  try {
    const bitmap = await createImageBitmap(file)

    const canvas = document.createElement('canvas')
    canvas.width = bitmap.width
    canvas.height = bitmap.height

    const ctx = canvas.getContext('2d')
    ctx.drawImage(bitmap, 0, 0)

    return new Promise((resolve, reject) => {
      canvas.toBlob((blob) => {
    console.log('Blob gerado:', blob);
        if (blob) {
            resolve(blob)
        }
        else {
            reject(new Error('Falha ao gerar Blob em formato WebP.'))
        }
      }, 'image/webp', 0.8)
    })
  }
  catch (error) {
    console.error('Erro na conversão para WebP:', error)
    throw new Error('Erro ao processar a imagem.')
  }
}
