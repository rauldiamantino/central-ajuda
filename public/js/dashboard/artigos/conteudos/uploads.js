const alterarImagemConteudo = (event) => {
  const elementoPai = event.target.closest('.modal-conteudo-imagem-adicionar');

  if (!elementoPai) {
    return;
  }

  const editarImagemEscolher = elementoPai.querySelector('.conteudo-adicionar-imagem-escolher');

  if (! editarImagemEscolher) {
    return;
  }

  editarImagemEscolher.click();
}

const alterarImagemConteudoEditar = (event) => {
  const elementoPai = event.target.closest('.container-conteudo-imagem-editar');

  if (! elementoPai) {
    return;
  }

  const editarImagemEscolher = elementoPai.querySelector('.conteudo-editar-imagem-escolher');

  if (! editarImagemEscolher) {
    return;
  }

  editarImagemEscolher.click();
}

const mostrarImagemConteudo = async (event) => {
  const elementoPai = event.target.closest('.modal-conteudo-imagem-adicionar');
  const anexo = event.target.files[0];

  if (! elementoPai || ! anexo) {
    return;
  }

  const editarTextoImagemEscolher = elementoPai.querySelector('.conteudo-txt-imagem-adicionar-escolher');
  const imgElemento = elementoPai.querySelector('.bloco-imagem-elemento');

  if (! imgElemento) {
    return;
  }

  try {
    const webpBlob = await imagem2Webp(anexo);
    const webpFile = new File([webpBlob], 'nova-imagem.webp', { type: 'image/webp' });
    const dataTransfer = new DataTransfer();
    const webpURL = URL.createObjectURL(webpBlob);

    dataTransfer.items.add(webpFile);
    event.target.files = dataTransfer.files;
    imgElemento.src = webpURL;
    imgElemento.classList.remove('hidden');
    editarTextoImagemEscolher.textContent = 'Imagem escolhida';
  }
  catch (error) {
    msgErroImagem.textContent = error.message;
    msgErroImagem.dataset.sucesso = 'false';
    msgErroImagem.classList.remove('hidden');
  }
}

const mostrarImagemConteudoEditar = async (event) => {
  const elementoPai = event.target.closest('.container-conteudo-imagem-editar');
  const anexo = event.target.files[0];

  if (! elementoPai || ! anexo) {
    return;
  }

  const editarTextoImagemEscolher = elementoPai.querySelector('.conteudo-txt-imagem-editar-escolher');
  const imgElemento = elementoPai.querySelector('.bloco-imagem-elemento');

  if (! imgElemento) {
    return;
  }

  try {
    const webpBlob = await imagem2Webp(anexo);
    const webpFile = new File([webpBlob], 'nova-imagem.webp', { type: 'image/webp' });
    const dataTransfer = new DataTransfer();
    const webpURL = URL.createObjectURL(webpBlob);

    dataTransfer.items.add(webpFile);
    event.target.files = dataTransfer.files;
    imgElemento.src = webpURL;
    imgElemento.classList.remove('hidden');
    editarTextoImagemEscolher.textContent = 'Imagem escolhida';
  }
  catch (error) {
    msgErroImagem.textContent = error.message;
    msgErroImagem.dataset.sucesso = 'false';
    msgErroImagem.classList.remove('hidden');
  }
}