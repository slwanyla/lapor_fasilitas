<?php
session_start(); 

require_once "../koneksi.php";
require_once "../controllers/nontifikasi/nontifikasi.php";

include 'sidebar.php';

// 🔥 WAJIB sebelum header.php
$notifController = new NotificationController($db);

$userId = $_SESSION['user_id'];
$unread = $notifController->getUnreadCount($userId, 'user');
$listNotif = $notifController->getNotifications($userId, 'user');

include 'header.php'; 
include '../alert.php'; 
showAlert(); 
?>

<div class="content-lapor">
    <div class="report-container">
        <div class="white-box">

            <h3>Create Report</h3>

            <!-- FORM START -->
            <form action="../controllers/user/laporcontroller.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="action" value="create_report">

                <!-- File input HARUS di dalam form -->
                <input type="file" name="foto" id="fileInput" accept=".jpg, .jpeg, .png" hidden>

                <!-- Upload Area -->
                <div id="uploadArea" class="upload-area">
                    <div class="upload-icon"><i class="fi fi-rr-cloud-upload"></i></div>
                    <p>choose a media or drag & drop it here</p>

                    <button type="button" class="browse-btn"
                        onclick="document.getElementById('fileInput').click()">
                        browse file
                    </button>
                </div>

                <!-- Progress Area -->
                <div id="progressArea" class="progress-area hidden">
                    <div class="progress-header">
                        <span id="fileName">filename</span>
                        <i class="fi fi-tr-cross-small close-progress" onclick="resetUpload()"></i>
                    </div>

                    <div class="progress-bar">
                        <div id="progressLine"></div>
                    </div>
                </div>

                <!-- Preview Area -->
                <div id="previewArea" class="preview-area hidden">
                    <i class="fi fi-sr-circle-xmark close-preview" onclick="resetUpload()"></i>

                    <img id="previewImg">

                    <div class="preview-info">
                        <span id="uploadedFileName"></span>
                    </div>
                </div>

                <br>

                <!-- Form Input -->
                <div class="row">
                    <input type="text" name="judul_laporan" placeholder="Judul" required>
                    <input type="text" name="lokasi" placeholder="Lokasi" required>
                </div>

                <textarea name="deskripsi" placeholder="Deskripsi" required></textarea>

                <div class="btn-row">
                    <button type="submit" class="upload-btn">upload</button>
                    <button type="reset" class="cancel-btn">Cancel</button>
                </div>

            </form>
            <!-- FORM END -->

        </div>
    </div>
</div>

<!-- JAVASCRIPT -->
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
fileInput.addEventListener("change", () => {
    const file = fileInput.files[0];
    if (!file) return;

    showProgress(file);
    simulateProgress(file);
});

// === Drag & Drop ===
uploadArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    uploadArea.style.borderColor = "#3a42e8";
});

uploadArea.addEventListener("dragleave", () => {
    uploadArea.style.borderColor = "#ccc";
});

uploadArea.addEventListener("drop", (e) => {
    e.preventDefault();
    uploadArea.style.borderColor = "#ccc";

    const file = e.dataTransfer.files[0];
    if (!file) return;

    fileInput.files = e.dataTransfer.files; // biar masuk ke form

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
