// Хранилище существующих графиков
const activeCharts = {};

const title = document.getElementById('title');
title.innerText = title.innerText + ' '+ name

function writeChart(ctx, list, _data, color, about ,type) {
    if (about == undefined) {
        about = 'кол-во'
    }
    if (type == undefined) {
        type = 'bar'
    }
     // Уничтожаем старый график, если он существует
    if (activeCharts[ctx.canvas.id]) {
        activeCharts[ctx.canvas.id].destroy();
    }
    const chart = new Chart(ctx, {
        type: type,
        title: about,
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
    // Сохраняем ссылку на график
    activeCharts[ctx.canvas.id] = chart;
}

function writeLineChart(ctx) {
    const chart = new Chart
}
    
function processHiddenData() {
    const chartDataBlocks = document.querySelectorAll('.chart-data');
    let canvasIndex = 0;
    const type = document.querySelector('.type').innerText;
    chartDataBlocks.forEach((block) => {
        let title = block.querySelector('.title');
        const headers = Array.from(block.querySelectorAll('ul .header')).map(el => el.textContent.trim());
        const values = Array.from(block.querySelectorAll('ul .value')).map(el => parseInt(el.textContent.trim()));
        
        const canvasId = `chart_${canvasIndex}`;
        let canvas = document.getElementById(canvasId);

        if (!canvas) {
            // Создаем новый canvas, если он отсутствует
            const newBlock = document.createElement('div');
            document.querySelector('.visualization .graphs').appendChild(newBlock);
            const newCanvas = document.createElement('canvas');
            newCanvas.id = canvasId;
            const newTitle = document.createElement('h2');
            if (title != null) {
                newTitle.innerText = 'Диаграмма для "' + title.innerText +'"';
                title = title.innerText
            } else {
                newTitle.innerText = 'Сравнение параметров';
                title = 'Сравнение параметров';
            }
            newBlock.appendChild(newTitle);
            newBlock.appendChild(newCanvas);
            canvas = newCanvas;
        }

        const ctx = canvas.getContext('2d');
        writeChart(ctx, headers, values, ['red', 'blue', 'green'], title, type);
        canvasIndex++;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    processHiddenData();
});

