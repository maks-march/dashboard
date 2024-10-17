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