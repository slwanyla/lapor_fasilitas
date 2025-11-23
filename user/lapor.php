<?php include 'sidebar.php'; ?>
<?php include 'header.php'; ?>
<?php include '../alert.php'; showAlert(); ?>

<link rel="stylesheet" href="../assets/css/style.css">

        <div class="report-container">
            <div class="white-box">

        <h3>Create Report</h3>

        <!-- Upload Area -->
        <div id="uploadArea" class="upload-area">
            <div class="upload-icon">☁️</div>
            <p>choose a media or drag & drop it here</p>

            <input type="file" id="fileInput" hidden>
            <button class="browse-btn" onclick="document.getElementById('fileInput').click()">
                browse file
            </button>
        </div>

        <!-- Progress Area (hidden default) -->
        <div id="progressArea" class="progress-area hidden">
            <div class="progress-header">
                <span id="fileName">filename</span>
                <i class="fi fi-tr-cross-small close-progress" onclick="resetUpload()"></i>
            </div>

            <div class="progress-bar">
                <div id="progressLine"></div>
            </div>
        </div>

        <!-- Preview Area (hidden default) -->
        <div id="previewArea" class="preview-area hidden">
            <img id="previewImg">
            <div class="preview-info">
                <span id="uploadedFileName"></span>
                <i class="fi fi-tr-cross-small close-preview" onclick="resetUpload()"></i>
            </div>
        </div>

        <br>

        <!-- Form Input -->
        <form>
            <div class="row">
                <input type="text" placeholder="Judul">
                <input type="text" placeholder="Lokasi">
            </div>

            <textarea placeholder="Deskripsi"></textarea>

            <div class="btn-row">
                <button type="submit" class="upload-btn">upload</button>
                <button type="button" class="close-btn">close</button>
            </div>
        </form>
    </div>


<script>
const fileInput = document.getElementById("fileInput");
const uploadArea = document.getElementById("uploadArea");
const progressArea = document.getElementById("progressArea");
const previewArea = document.getElementById("previewArea");

// Upload progress bar line
const progressLine = document.getElementById("progressLine");

// File name text
const fileNameText = document.getElementById("fileName");
const uploadedFileName = document.getElementById("uploadedFileName");

// Preview image
const previewImg = document.getElementById("previewImg");

let interval;

// === Trigger input file ===
uploadArea.addEventListener("click", () => fileInput.click());

fileInput.addEventListener("change", () => {
    const file = fileInput.files[0];
    if (!file) return;

    showProgress(file);
    simulateProgress(file);
});

// === Show Progress ===
function showProgress(file) {
    uploadArea.classList.add("hidden");
    previewArea.classList.add("hidden");
    progressArea.classList.remove("hidden");

    fileNameText.textContent = file.name;
}

// === Simulasi Progress Bar ===
function simulateProgress(file) {
    let width = 0;

    interval = setInterval(() => {
        width += 10;
        progressLine.style.width = width + "%";

        if (width >= 100) {
            clearInterval(interval);
            setTimeout(() => showPreview(file), 300);
        }
    }, 200);
}

// === Show Preview ===
function showPreview(file) {
    progressArea.classList.add("hidden");
    previewArea.classList.remove("hidden");

    previewImg.src = URL.createObjectURL(file);
    uploadedFileName.textContent = file.name;
}

// === Reset Upload ===
function resetUpload() {
    clearInterval(interval);

    progressArea.classList.add("hidden");
    previewArea.classList.add("hidden");
    uploadArea.classList.remove("hidden");

    progressLine.style.width = "0%";
    fileInput.value = "";
}
</script>
