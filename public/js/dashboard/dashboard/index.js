document.querySelectorAll('details summary').forEach((summary) => {
  summary.addEventListener('click', () => {
    const icone = summary.querySelector('.icon');
    icone.classList.toggle('rotate-180');
  });
});