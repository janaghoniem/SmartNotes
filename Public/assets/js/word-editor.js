function formatDoc(cmd, value = null) {
    if (value) {
        document.execCommand(cmd, false, value);
    } else {
        document.execCommand(cmd);
    }
}

function addLink() {
    const url = prompt('Insert url');
    formatDoc('createLink', url);
}

const content = document.getElementById('content');

content.addEventListener('mouseenter', function () {
    const a = content.querySelectorAll('a');
    a.forEach(item => {
        item.addEventListener('mouseenter', function () {
            content.setAttribute('contenteditable', false);
            item.target = '_blank';
        });
        item.addEventListener('mouseleave', function () {
            content.setAttribute('contenteditable', true);
        });
    });
});

const showCode = document.getElementById('show-code');
let active = false;

const filename = document.getElementById('filename');

function fileHandle(value) {
    if (value === 'new') {
        content.innerHTML = '';
        filename.value = 'untitled';
    } else if (value === 'txt') {
        const blob = new Blob([content.innerText]);
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `${filename.value}.txt`;
        link.click();
    } else if (value === 'pdf') {
        html2pdf(content).save(filename.value);
    }
}

const fontSelect = document.getElementById('fontSelect');
fontSelect.addEventListener('change', function() {
    formatDoc('fontName', this.value);
    this.selectedIndex = 0; // Reset the select box
});

document.addEventListener('DOMContentLoaded', function () {
    const AddContentButton = document.getElementById('update-content');
    console.log("DOM fully loaded");

    if (AddContentButton) {
        console.log("AddContentButton found");
        AddContentButton.addEventListener('click', function () {
            console.log("AddContentButton clicked");
            const contentDiv = document.getElementById('content');
            const contentToSave = contentDiv.innerText || contentDiv.textContent; // Use a different name
            const fileId = getQueryParam('id');
            console.log("File ID from URL:", fileId);
            console.log('Content to save:', contentToSave);
            addcontent(contentToSave);
        });
    } else {
        console.error('AddContentButton not found');
    }
});


function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

function addcontent(content) {
    const user_id = document.getElementById('userid').value;
    const fileId = getQueryParam('id');
    const folder_id = 1;
    const file_type = 4;

    console.log("File ID:", fileId);
    console.log("Content to save:", content);
    console.log("User ID:", user_id);

    fetch('../../app/includes/FileContent.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `content=${encodeURIComponent(content)}&user_id=${user_id}&id=${fileId}&folder_id=${folder_id}&file_type=${file_type}`,
    })
        .then((response) => response.text())
        .then((data) => {
            console.log('Server Response:', data);
            if (
                data.includes('Record updated successfully') ||
                data.includes('Record created successfully')
            ) {
                alert('Content saved successfully');
            } else {
                alert('Failed to save content. Response: ' + data);
            }
        })
        .catch((error) => {
            console.error('Error saving content:', error);
            alert('Error saving content: ' + error);
        });
}
