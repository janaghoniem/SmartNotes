const searchInput = document.getElementById('searchInput');
const table = document.getElementById('dataTable');
const rows = table.querySelectorAll('tbody tr');

searchInput.addEventListener('input', () => {
    const filter = searchInput.value.toLowerCase();
    rows.forEach(row => {
        const rowData = row.textContent.toLowerCase();
        row.style.display = rowData.includes(filter) ? '' : 'none';
    });
});