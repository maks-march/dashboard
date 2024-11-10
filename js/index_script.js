var result = location.search.substring(1,9);
if (result == "no_start") {
    const window = document.getElementById('start_window');
    window.classList.add('unactive_block')
}

function delStartWindow() {
    const window = document.getElementById('start_window');
    window.classList.add('window_unactive')
}