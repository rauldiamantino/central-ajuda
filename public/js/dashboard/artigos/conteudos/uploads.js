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

const mostrarImagemConteudo = (event) => {
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

  const imagem = new Image()
  imagem.src = URL.createObjectURL(anexo)

  const objetoReader = new FileReader();
  objetoReader.onload = (e) => {
    imgElemento.src = e.target.result;
    imgElemento.classList.remove('hidden');
  };

  objetoReader.readAsDataURL(anexo);
  editarTextoImagemEscolher.textContent = 'Imagem escolhida';
}

const mostrarImagemConteudoEditar = (event) => {
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

  const imagem = new Image()
  imagem.src = URL.createObjectURL(anexo)

  const objetoReader = new FileReader();
  objetoReader.onload = (e) => {
    imgElemento.src = e.target.result;
    imgElemento.classList.remove('hidden');
  };

  objetoReader.readAsDataURL(anexo);
  editarTextoImagemEscolher.textContent = 'Imagem escolhida';
}