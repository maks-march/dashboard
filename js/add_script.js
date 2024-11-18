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