<form action="/import-laporan-pwa" method="POST" enctype="multipart/form-data">

    @csrf

    <div class="mb-3">
        <label>Upload Excel</label>
        <input type="file" name="file_excel" required>
    </div>

    <button type="submit">
        Import Data
    </button>

</form>
