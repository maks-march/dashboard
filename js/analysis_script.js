// Функция для отображения данных по городу
// Пример построения графика с использованием Chart.js
const chart_place = document.getElementById('chart_place').getContext('2d');
const chart_years = document.getElementById('chart_years').getContext('2d');
const chart_payment = document.getElementById('chart_payment').getContext('2d');
const chart_male = document.getElementById('chart_male').getContext('2d');


let name = decodeURI(location.search.split('name=')[1].split(';')[0]);

const title = document.getElementById('title');
title.innerText = title.innerText + ' '+ name



writeChart(chart_place, 
    ["дошкольные","общеобразовательные","проф образования","высшего образования","доп образования детей"],
     [48178, 90418,17049,38193,52297], '#0000AA', undefined)
writeChart(chart_years, ['3-15 лет', '16-18 лет', '19-29 лет', '30-54 лет', '54-79 лет', '80+ лет'], [209, 30, 154, 183, 39, 1], undefined, 'тысяч человек')
writeChart(chart_payment, ['Платно', 'Бесплатно'], [174000, 618000-174000], ['#00AA00', '#AA0000'], undefined)
writeChart(chart_male, ['Мужчины', 'Женщины'], [618000-253000, 253000], ['#36A2EB', '#FF6384'], undefined)

function writeChart(ctx, list, _data, color, about ,type) {
    if (about == undefined) {
        about = 'кол-во'
    }
    if (type == undefined) {
        type = 'bar'
    }
    const chart = new Chart(ctx, {
        type: type,
        data: {
            labels: list,
            datasets: [{
                label: about,
                data: _data, // Примерные данные для визуализации
                backgroundColor: color
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

function writeLineChart(ctx) {
    const chart = new Chart
}
