// Функция для обработки загрузки файлов
document.getElementById('file-input').addEventListener('change', function(event) {
  const files = event.target.files;
  console.log('Файлы загружены:', files);
  // Здесь можно добавить логику для обработки файлов
});

// Функция для отображения данных по городу
function showCityData(city) {
  console.log('Показать данные для города:', city);
  // Пример построения графика с использованием Chart.js
  const ctx = document.getElementById('chart').getContext('2d');
  const chart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: ['Стадионы', 'Спорт залы'],
          datasets: [{
              label: city,
              data: [30, 50], // Примерные данные для визуализации
              backgroundColor: ['#FF6384', '#36A2EB']
          }]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true
              }
          }
      }
  });
}


function delStartWindow() {
    const window = document.getElementById('start_window');
    window.classList.add('window_unactive')
}

function move(block, next_block) {
    block.classList.add('unactive_block')
    next_block.classList.add('active_block')
    block.classList.remove('active_block')
    next_block.classList.remove('unactive_block')
}

function moveToAdd() {
    const active_block = document.querySelector('.active_block')
    const add_block = document.getElementById('add')
    move(active_block, add_block)
}

function moveToGraphs() {
    const active_block = document.querySelector('.active_block')
    const graphs = document.getElementById('graphs')
    move(active_block, graphs)
}

function moveToFiles() {
    const active_block = document.querySelector('.active_block')
    const files = document.getElementById('files')
    move(active_block, files)
}