const dropBox = document.querySelector(".drop_box"),
      button = dropBox.querySelector("button"),
      input = dropBox.querySelector("input"),
      statusDisplay = document.getElementById("upload-status");

let selectedFile;

// Open file selection dialog when the button is clicked
button.onclick = () => input.click();

// Handle file selection
input.addEventListener("change", function (e) {
    selectedFile = e.target.files[0];
    if (!selectedFile) return;

    const fileName = selectedFile.name;
    const fileExtension = fileName.split('.').pop().toLowerCase();

    // Preserve the form structure but prepare it for submission
    const fileData = `
        <form id="uploadForm" method="post" enctype="multipart/form-data">
            <div class="form">
                <h4>${fileName}</h4>
                <input type="text" name="rename" placeholder="Rename file" />
                <button class="btn" type="submit">Upload</button>
            </div>
        </form>
    `;
    dropBox.innerHTML = fileData;

    // Attach event listener to the form for upload
    const uploadForm = document.getElementById("uploadForm");
    uploadForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission

        const renameInput = uploadForm.querySelector("input[name='rename']").value;
        const formData = new FormData();
        formData.append("file", selectedFile);
        formData.append("rename", renameInput);

        // Check file type and extract text accordingly
        if (fileExtension === 'pdf') {
            extractTextFromPDF(selectedFile).then(extractedText => {
                formData.append("content", extractedText); // Append extracted text
                uploadFile(formData);
            }).catch(err => {
                console.error("Error extracting text from PDF:", err);
                statusDisplay.textContent = "Failed to extract text from PDF.";
            });
        } else if (fileExtension === 'docx') {
            extractTextFromDOCX(selectedFile).then(extractedText => {
                formData.append("content", extractedText); // Append extracted text
                uploadFile(formData);
            }).catch(err => {
                console.error("Error extracting text from DOCX:", err);
                statusDisplay.textContent = "Failed to extract text from DOCX.";
            });
        } else if (fileExtension === 'doc') {
            extractTextFromDOC(selectedFile).then(extractedText => {
                formData.append("content", extractedText); // Append extracted text
                uploadFile(formData);
            }).catch(err => {
                console.error("Error extracting text from DOC:", err);
                statusDisplay.textContent = "Failed to extract text from DOC.";
            });
        } else if (fileExtension === 'txt') {
            extractTextFromTXT(selectedFile).then(extractedText => {
                formData.append("content", extractedText); // Append extracted text
                uploadFile(formData);
            }).catch(err => {
                console.error("Error extracting text from TXT:", err);
                statusDisplay.textContent = "Failed to extract text from TXT.";
            });
        } else {
            statusDisplay.textContent = "Unsupported file type.";
        }
    });
});

// Function to handle PDF extraction
async function extractTextFromPDF(file) {
    const pdf = await pdfjsLib.getDocument(URL.createObjectURL(file)).promise;
    let extractedText = '';

    for (let i = 1; i <= pdf.numPages; i++) {
        const page = await pdf.getPage(i);
        const textContent = await page.getTextContent();
        extractedText += textContent.items.map(item => item.str).join(' ');
    }

    return extractedText;
}

// Function to handle DOCX extraction using Mammoth.js
async function extractTextFromDOCX(file) {
    const reader = new FileReader();
    return new Promise((resolve, reject) => {
        reader.onload = function () {
            // Use Mammoth.js to convert DOCX file to text
            mammoth.extractRawText({ arrayBuffer: reader.result })
                .then(function (result) {
                    resolve(result.value); // Return extracted text
                })
                .catch(function (err) {
                    reject('Error extracting text from DOCX file: ' + err);
                });
        };

        reader.onerror = function () {
            reject('Error reading DOCX file');
        };

        reader.readAsArrayBuffer(file); // Read as ArrayBuffer for Mammoth.js
    });
}

// Function to handle DOC extraction
async function extractTextFromDOC(file) {
    const reader = new FileReader();
    return new Promise((resolve, reject) => {
        reader.onload = function () {
            // Use a conversion method or another library to handle the DOC file
            // For simplicity, we assume it's converted to DOCX on the server
            resolve("DOC files need server-side conversion to DOCX");
        };

        reader.onerror = function () {
            reject('Error reading DOC file');
        };

        reader.readAsArrayBuffer(file); // Read as ArrayBuffer (needed for conversion)
    });
}

// Function to handle TXT extraction
async function extractTextFromTXT(file) {
    const reader = new FileReader();
    return new Promise((resolve, reject) => {
        reader.onload = function () {
            resolve(reader.result);
        };

        reader.onerror = function () {
            reject('Error reading TXT file');
        };

        reader.readAsText(file);
    });
}

// Function to handle file upload via AJAX
function uploadFile(formData) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../includes/upload_handler.php", true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log(xhr.responseText); // Log the response for debugging
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.status) {
                    statusDisplay.textContent = response.message;
                    dropBox.innerHTML = `<h4>Upload Complete!</h4>`;
                } else {
                    statusDisplay.textContent = `Error: ${response.message}`;
                }
            } catch (e) {
                console.error("Error parsing response:", e);
                statusDisplay.textContent = "Server returned invalid response.";
            }
        } else {
            statusDisplay.textContent = "Server error. Please try again.";
        }
    };

    xhr.onerror = function () {
        statusDisplay.textContent = "Network error. Please try again.";
    };

    xhr.send(formData);
}
