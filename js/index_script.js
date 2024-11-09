<<<<<<< Updated upstream
=======
// Функция для обработки загрузки файлов
document.getElementById('file_input').addEventListener('change', function(event) {
  const files = event.target.files;
  console.log('Файлы загружены:', files);
  // Здесь можно добавить логику для обработки файлов
});
>>>>>>> Stashed changes


var result = location.search.substring(1,9);
if (result == "no_start") {
    const window = document.getElementById('start_window');
    window.classList.add('unactive_block')
}

function delStartWindow() {
    const window = document.getElementById('start_window');
    window.classList.add('window_unactive')
}