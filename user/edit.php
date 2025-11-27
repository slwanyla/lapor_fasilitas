<!-- DARK OVERLAY -->
<div id="editOverlay" class="overlay"></div>

<!-- EDIT MODAL -->
<div id="editModal" class="edit-modal">
      <form action="../controllers/user/laporcontroller.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="update_report">
          <input type="hidden" id="editId" name="id">

    <div class="edit-header">
        <span>Edit Form</span>
       <button type="button" class="close-edit" onclick="closeEdit()">
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
                    <input type="file" id="fileInput" name="foto" hidden>

                    <button type="button" class="file-btn" onclick="document.getElementById('fileInput').click()">
                        <i class="fi fi-rr-file-image"></i>   
                    </button>
                    
                </div>

        </div>

        <div class="right-form">
           

            <label>Judul</label>
            <input type="text" id="editJudul" name="judul_laporan" class="input">

            <label>Lokasi</label>
            <input type="text" id="editLokasi" name="lokasi" class="input">

            <label>Deskripsi</label>
            <textarea id="editDeskripsi" name="deskripsi" class="textarea"></textarea>

            <div class="btn-area">
                <button type="submit" class="btn-change">Change</button>
                <button type="button" class="btn-cancel" onclick="closeEdit()">Cancel</button>
            </div>
        </div>

    </div>
</div>


<!-- JS OPEN & CLOSE MODAL -->
<script>

    function openEdit(id, judul, lokasi, deskripsi, foto) {
        document.getElementById("editOverlay").style.display = "block";
        const modal = document.getElementById("editModal");

        // Prefill form
        document.getElementById("editId").value = id;
        document.getElementById("editJudul").value = judul;
        document.getElementById("editLokasi").value = lokasi;
        document.getElementById("editDeskripsi").value = deskripsi;

        // Preview foto lama
        const previewImg = document.getElementById("previewImg");
        const fileInfo = document.getElementById("fileInfo");

        if (foto) {
            previewImg.src = "../uploads/" + foto;
            fileInfo.innerHTML = foto;
        } else {
            previewImg.src = "";
            fileInfo.innerHTML = "Tidak ada file";
        }

        // Show modal
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
