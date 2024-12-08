document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-buttons .filter-btn');
    const sortLinks = document.querySelectorAll('.dropdown-content a');
    const folders = document.querySelectorAll('.folder');
    const notes = document.querySelectorAll('.note');

    function sortElements(elements, sortBy) {
        let sortedElements = Array.from(elements);
        sortedElements.sort((a, b) => {
            if (sortBy === 'name') {
                return a.querySelector('p').textContent.localeCompare(b.querySelector('p').textContent);
            } else if (sortBy === 'created') {
                return new Date(a.getAttribute('data-created-at')) - new Date(b.getAttribute('data-created-at'));
            } else if (sortBy === 'modified') {
                // Assuming data-modified-at attribute is present
                return new Date(a.getAttribute('data-modified-at')) - new Date(b.getAttribute('data-modified-at'));
            }
        });
        return sortedElements;
    }

    function applyFilterAndSort(filter = null, sortBy = null) {
        const today = new Date();
        let startDate;

        if (filter === 'today') {
            startDate = new Date(today.setHours(0, 0, 0, 0));
        } else if (filter === 'this week') {
            const firstDayOfWeek = today.getDate() - today.getDay();
            startDate = new Date(today.setDate(firstDayOfWeek));
            startDate.setHours(0, 0, 0, 0);
        } else if (filter === 'this month') {
            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            startDate.setHours(0, 0, 0, 0);
        }

        let filteredFolders = Array.from(folders);
        let filteredNotes = Array.from(notes);

        if (filter) {
            filteredFolders = filteredFolders.filter(folder => new Date(folder.getAttribute('data-created-at')) >= startDate);
            filteredNotes = filteredNotes.filter(note => new Date(note.getAttribute('data-created-at')) >= startDate);
        }

        if (sortBy) {
            filteredFolders = sortElements(filteredFolders, sortBy);
            filteredNotes = sortElements(filteredNotes, sortBy);
        }

        document.querySelector('.folders').innerHTML = '';
        document.querySelector('.notes').innerHTML = '';

        filteredFolders.forEach(folder => document.querySelector('.folders').appendChild(folder));
        filteredNotes.forEach(note => document.querySelector('.notes').appendChild(note));
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const isActive = this.classList.contains('active');

            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));

            if (isActive) {
                applyFilterAndSort();
            } else {
                this.classList.add('active');
                applyFilterAndSort(this.getAttribute('data-filter'));
            }
        });
    });

    sortLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            applyFilterAndSort(null, this.getAttribute('data-sort'));
        });
    });
});



document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.input-group .form-control');
    const clearSearchButton = document.getElementById('clear-search');
    const clearFiltersButton = document.getElementById('clear-filters');
    const folders = document.querySelectorAll('.folder');
    const notes = document.querySelectorAll('.note');
    const filterButtons = document.querySelectorAll('.filter-buttons .filter-btn');

    // Search functionality
    searchInput.addEventListener('input', function () {
        const query = searchInput.value.toLowerCase();
        let hasResults = false;

        // Toggle clear search button visibility
        clearSearchButton.style.display = query ? 'inline' : 'none';

        // Filter folders
        folders.forEach(folder => {
            const folderName = folder.querySelector('p').textContent.toLowerCase();
            if (folderName.includes(query)) {
                folder.style.display = '';
                hasResults = true;
            } else {
                folder.style.display = 'none';
            }
        });

        // Filter notes
        notes.forEach(note => {
            const noteName = note.querySelector('.note-name').textContent.toLowerCase();
            const noteContent = note.querySelector('p').textContent.toLowerCase();
            if (noteName.includes(query) || noteContent.includes(query)) {
                note.style.display = '';
                hasResults = true;
            } else {
                note.style.display = 'none';
            }
        });

        // Handle "No Results" message
        document.getElementById('no-results').style.display = hasResults ? 'none' : '';
    });

    // Clear search functionality
    clearSearchButton.addEventListener('click', function () {
        searchInput.value = ''; // Clear the input
        searchInput.dispatchEvent(new Event('input')); // Trigger the input event to reset results
    });

    if(clearFiltersButton) {
        // Clear filters functionality
        clearFiltersButton.addEventListener('click', function () {
            // Reset filter buttons
            filterButtons.forEach(button => button.classList.remove('active'));

            // Show all folders and notes
            folders.forEach(folder => (folder.style.display = ''));
            notes.forEach(note => (note.style.display = ''));

            // Reset search input
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input')); // Trigger the input event

            // Hide "No Results" message
            document.getElementById('no-results').style.display = 'none';
        });
    }
});
