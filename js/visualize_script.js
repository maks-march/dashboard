const cells = document.querySelectorAll(".cell");

cells.forEach(cell => {
    cell.addEventListener('click', () => {
        console.log(cell.id);
        if (cell.classList.contains('active')) {
            cell.classList.remove('active');
        } else {
            cell.classList.add('active');
        }
    });
});