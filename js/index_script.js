// Функция для обработки загрузки файлов
document.getElementById('file-input').addEventListener('change', function(event) {
  const files = event.target.files;
  console.log('Файлы загружены:', files);
  // Здесь можно добавить логику для обработки файлов
});




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