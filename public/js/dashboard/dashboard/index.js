const alternarPopulares = () => {
  const maisPopulares = document.querySelector('.dashboard-tabela-mais-populares')
  const menosPopulares = document.querySelector('.dashboard-tabela-menos-populares')

  maisPopulares.classList.toggle('hidden')
  menosPopulares.classList.toggle('hidden')
}