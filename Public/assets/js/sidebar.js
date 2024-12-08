document.addEventListener("DOMContentLoaded", function () {
    const newNote = document.getElementById('new-note');
    document.getElementById('add-new').addEventListener('click', function () {
        document.querySelector('.overlay').classList.add('open');
        document.querySelector('.pop-up').classList.add('open');
    });
    
    if(newNote){
        newNote.addEventListener('click', function () {
            document.querySelector('.pop-up').classList.add('open');
            document.querySelector('.overlay').classList.add('open');
        });
    }

    document.querySelector('.pop-up .close').addEventListener('click', function () {
        document.querySelector('.pop-up').classList.remove('open');
        document.querySelector('.overlay').classList.remove('open');
    });

    document.querySelector('.add-item form').addEventListener('submit', function (event) {
        // event.preventDefault();
        const type = document.getElementById('type').value;
        const name = document.getElementById('name').value;
        const description = document.getElementById('description').value;
        document.querySelector('.pop-up').classList.remove('open');
        document.querySelector('.overlay').classList.remove('open');
    });
});
document.addEventListener('click', function (event) {
    const ellipsisIcons = document.querySelectorAll('.ellipsis');
    let isEllipsis = false;
    let isPopover = false;

    ellipsisIcons.forEach(ellipsis => {
        const popover = ellipsis.nextElementSibling;
        if (event.target === ellipsis || popover.contains(event.target)) {
            isEllipsis = true;
            isPopover = true;
            if (popover.style.display === 'block') {
                popover.style.display = 'none';
            } else {
                popover.style.display = 'block';
            }
        } else {
            popover.style.display = 'none';
        }
    });
    if (!isEllipsis && !isPopover) {
        ellipsisIcons.forEach(ellipsis => {
            ellipsis.nextElementSibling.style.display = 'none';
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    // Open the trash modal when ".popover-btn.delete" is clicked
    document.querySelectorAll('.popover-btn.delete').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const itemId = this.getAttribute('data-item-id');
            const itemType = this.getAttribute('data-item-type'); // Get type dynamically
            
            // Set item_id and item_type values
            document.getElementById('trash_item_id').value = itemId;
            document.getElementById('trash_item_type').value = itemType; // Dynamic value
    
            // Update modal message based on item type
            const modalMessage = document.querySelector('#trashModal .modal-content p');
            modalMessage.textContent = `Are you sure you want to move this ${itemType} to trash?`;
    
            // Display the modal
            document.getElementById('trashModal').style.display = 'flex';
        });
    });
    
    

    // Open the delete modal when ".fa-solid.fa-trash" is clicked
    document.querySelectorAll('.fa-solid.fa-trash').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const folderId = this.getAttribute('data-folder-id');
            document.getElementById('folder_id').value = folderId;
            document.getElementById('deleteModal').style.display = 'flex';
        });
    });
});

// Close modal function for both modals
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    ['deleteModal', 'trashModal'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal.style.display === 'flex' && !modal.contains(event.target)) {
            closeModal(modalId);
        }
    });
});

document.getElementById('deleteForm').addEventListener('submit', function() {
    const folderId = document.getElementById('folder_id').value;
    console.log("Folder ID being submitted: ", folderId);
});


document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.popover-btn.rename').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.stopPropagation();

            // Close the popover when "Rename" is clicked
            const popover = button.closest('.popover');
            popover.style.display = 'none';

            // Determine if it's a folder or note
            const folderElement = button.closest('.folder');
            const noteElement = button.closest('.note');
            let currentName, id, isFolder, nameElement;

            if (folderElement) {
                nameElement = folderElement.querySelector('p');
                currentName = nameElement.textContent.trim();
                id = button.getAttribute('data-folder-id');
                isFolder = true;
            } else if (noteElement) {
                nameElement = noteElement.querySelector('.note-name');
                currentName = nameElement.childNodes[0].textContent.trim();
                id = button.getAttribute('data-note-id');
                isFolder = false;
            }

            const inputField = document.createElement('input');
            inputField.type = 'text';
            inputField.value = currentName;
            inputField.classList.add('rename-input');
            nameElement.replaceWith(inputField);
            inputField.focus();

            const saveHandler = function (event) {
                if (event.type === 'blur' || (event.type === 'keydown' && event.key === 'Enter')) {
                    inputField.removeEventListener('blur', saveHandler); // Prevent infinite loop
                    inputField.removeEventListener('keydown', saveHandler); // Prevent infinite loop
                    const newName = inputField.value.trim();
                    saveNewName(newName, id, isFolder, inputField, nameElement);
                }
            };

            inputField.addEventListener('keydown', saveHandler);
            inputField.addEventListener('blur', saveHandler);
        });
    });

    function saveNewName(newName, id, isFolder, inputField, originalElement) {
        if (!newName) {
            replaceWithOriginalElement(inputField, originalElement);
            return;
        }
    
        const xhr = new XMLHttpRequest();
        let url, data;
    
        if (isFolder) {
            url = '../includes/update_folder.php';
            data = `id=${id}&name=${encodeURIComponent(newName)}`;
        } else {
            url = '../includes/update_note.php';
            data = `id=${id}&name=${encodeURIComponent(newName)}`;
        }
    
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                console.log('Server response:', xhr.responseText.trim()); // Log the response
    
                if (xhr.status === 200 && xhr.responseText.trim() === 'success') {
                    // Update the DOM element with the new name
                    const updatedNameElement = originalElement.cloneNode(true);
                    updatedNameElement.textContent = newName;
    
                    if (!isFolder) {
                        const ellipsisIcon = document.createElement('i');
                        ellipsisIcon.className = 'fa-solid fa-ellipsis ellipsis';
                        updatedNameElement.appendChild(ellipsisIcon);
                    }
    
                    inputField.replaceWith(updatedNameElement);
                } else {
                    // Handle failure
                    location.reload(); // This reloads the page

                    replaceWithOriginalElement(inputField, originalElement);
                }
            }
        };
        xhr.send(data);
    }
    
    if (xhr.status === 200 && xhr.responseText.trim() === 'success') {
        location.reload(); // Reloads the entire page
    }
    

    function replaceWithOriginalElement(inputField, originalElement) {
        // Restore the original name element if the save fails
        inputField.replaceWith(originalElement);
    }
});

