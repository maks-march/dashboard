// Функция для обработки загрузки файлов
document.getElementById('file_input').addEventListener('change', function(event) {
  let files = event.target.files;
  const files_list = document.getElementById('files_list');
  for (var i = 0; i < files.length; i++) {
    file = files[i];
    const new_file_item = document.createElement('div');
    new_file_item.classList.add('list_item');
    new_file_item.innerText = file.name;
    files_list.appendChild(new_file_item);
  }
});

document.getElementById('ready').addEventListener('click', () => {
    delete_upload_list();
});

function delete_upload_list() {
    const file_items = document.querySelectorAll('.list_item')
    file_items.forEach(file_item => {
        file_item.remove();
    });
}

var result = location.search.substring(1,9);
if (result == "no_start") {
    const window = document.getElementById('start_window');
    window.classList.add('unactive_block')
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
    delete_upload_list()
}

function moveToAdd() {
    const active_block = document.querySelector('.active_block')
    const add_block = document.getElementById('add')
    move(active_block, add_block)
}

function moveToFiles() {
    const active_block = document.querySelector('.active_block')
    const files = document.getElementById('files')
    move(active_block, files)
}