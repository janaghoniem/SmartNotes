function formatDoc(cmd, value = null) {
    if (value) {
        document.execCommand(cmd, false, value);
    } else {
        document.execCommand(cmd);
    }
}

function addLink() {
    const url = prompt("Insert URL:");
    if (url) {
        formatDoc("createLink", url);
    }
}

const editorContent = document.getElementById("content");

editorContent.addEventListener("mouseenter", function () {
    const links = editorContent.querySelectorAll("a");
    links.forEach(link => {
        link.addEventListener("mouseenter", function () {
            editorContent.setAttribute("contenteditable", false);
            link.target = "_blank";
        });
        link.addEventListener("mouseleave", function () {
            editorContent.setAttribute("contenteditable", true);
        });
    });
});

const showCode = document.getElementById("show-code");
let isCodeActive = false;

const fileNameInput = document.getElementById("filename");

function fileHandle(action) {
    if (action === "new") {
        editorContent.innerHTML = "";
        fileNameInput.value = "untitled";
    } else if (action === "txt") {
        const blob = new Blob([editorContent.innerText]);
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = url;
        link.download = `${fileNameInput.value}.txt`;
        link.click();
    } else if (action === "pdf") {
        html2pdf(editorContent).save(fileNameInput.value);
    }
}

const fontSelect = document.getElementById("fontSelect");
fontSelect.addEventListener("change", function () {
    formatDoc("fontName", this.value);
    this.selectedIndex = 0; // Reset the select box
});

document.getElementById("update-content").addEventListener("click", function () {
    const userId = document.getElementById("userid").value;
    const fileContentHTML = editorContent.innerHTML;

    fetch("path/to/api", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            userId: userId,
            content: fileContentHTML,
        }),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok.");
            }
            return response.json();
        })
        .then(data => {
            console.log("File saved successfully:", data);
        })
        .catch(error => {
            console.error("Error savingg file:", error);
        });
});
