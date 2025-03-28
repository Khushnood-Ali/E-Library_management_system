function searchBooks() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const books = document.querySelectorAll('.book-item');

    books.forEach(book => {
        const title = book.querySelector('.title').textContent.toLowerCase();
        const author = book.querySelector('.author').textContent.toLowerCase();
        if (title.includes(searchTerm) || author.includes(searchTerm)) {
            book.style.display = 'block';
        } else {
            book.style.display = 'none';
        }
    });
}