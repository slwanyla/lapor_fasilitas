<!-- DARK OVERLAY -->
<div id="editOverlay" class="overlay"></div>

<!-- EDIT MODAL -->
<div id="editModal" class="edit-modal">

    <div class="edit-header">
        <span>Edit Form</span>
        <button class="close-edit" onclick="closeEdit()">
            <i class="fi fi-br-cross-small"></i>
        </button>
    </div>

    <div class="edit-body">

        <div class="left-preview">
                <div class="preview-box">
                    <img id="previewImg" alt="">
                </div>


                <div id="fileInfo" class="file-info"></div>
                <div class="file-wrapper">
                    <input type="file" id="fileInput" hidden>

                    <button type="button" class="file-btn" onclick="document.getElementById('fileInput').click()">
                        <i class="fi fi-rr-file-image"></i>   
                    </button>
                    
                </div>

        </div>

        <div class="right-form">
            <label>Judul</label>
            <input type="text" id="editJudul" class="input">

            <label>Lokasi</label>
            <input type="text" id="editLokasi" class="input">

            <label>Deskripsi</label>
            <textarea id="editDeskripsi" class="textarea"></textarea>

            <div class="btn-area">
                <button class="btn-change">Change</button>
                <button class="btn-cancel" onclick="closeEdit()">Cancel</button>
            </div>
        </div>

    </div>
</div>


<!-- JS OPEN & CLOSE MODAL -->
<script>

    
    function openEdit() {
        document.getElementById("editOverlay").style.display = "block";
        const modal = document.getElementById("editModal");

        modal.style.display = "block";

        setTimeout(() => {
            modal.style.opacity = "1";
            modal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 20);
    }

    function closeEdit() {
        const modal = document.getElementById("editModal");

        modal.style.opacity = "0";
        modal.style.transform = "translate(-50%, -50%) scale(0.7)";
        document.getElementById("editOverlay").style.display = "none";

        setTimeout(() => {
            modal.style.display = "none";
        }, 200);
    }

   document.getElementById("fileInput").addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;

    const fileInfo = document.getElementById("fileInfo");
    const previewImg = document.getElementById("previewImg");

    // Tampilkan nama file
    fileInfo.innerHTML = `<strong>${file.name}</strong>`;

    // Load preview
    const reader = new FileReader();
    reader.onload = function (e) {
        previewImg.src = e.target.result;
    };
    reader.readAsDataURL(file);
});
</script>
