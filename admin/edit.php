<div id="editModal" class="edit-modal">
    <form action="../controllers/admin/reportcontroller.php" method="POST">
        <input type="hidden" name="action" value="update_status">
        <input type="hidden" id="editId" name="id_laporan">


        <div class="edit-header">
            <span>Edit Form</span>
            <button type="button" class="close-edit" onclick="closeEdit()">
                <i class="fi fi-br-cross-small"></i>
            </button>
        </div>

        <div class="edit-body">

            <!-- FOTO -->
            <div class="left-preview">
                <div class="preview-box">
                    <img id="previewImg" alt="">
                </div>

                <div id="fileInfo" class="file-info"></div>

                <!-- FILE UPLOAD DIHAPUS -->
            </div>

            <!-- FORM -->
            <div class="right-form">

                <label>Judul</label>
                <input type="text" id="editJudul" class="input" readonly>

                <label>Lokasi</label>
                <input type="text" id="editLokasi" class="input" readonly>

                <label>Deskripsi</label>
                <textarea id="editDeskripsi" class="textarea" readonly></textarea>

                <!-- STATUS DROPDOWN (Satu-satunya yang bisa diganti) -->
                <label>Status</label>
                <select id="editStatus" name="status" class="input">
                    <option value="baru">Baru</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                    <option value="tidak_valid">Tidak Valid</option>
                </select>

                <div class="btn-area">
                     <button type="submit" class="btn-save">confirm</button>
                    <button type="button" class="btn-cancel" onclick="closeEdit()">Cancel</button>
                </div>
            </div>

        </div>
    </form>
</div>
