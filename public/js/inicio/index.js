
let indiceAtual = 0;

const moveSlide = (direcao) => {
  const imagens = document.querySelector('#carrossel-imagens');
  const totalImagens = document.querySelectorAll('#carrossel-imagens img').length;

  indiceAtual = (indiceAtual + direcao + totalImagens) % totalImagens;
  imagens.style.transform = `translateX(-${indiceAtual * 100}%)`;
}