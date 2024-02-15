<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 15px; top: 15px;">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="createModalLabel" style="margin-left: 15px;">Tambah Data Provinsi</h5>
    </div>
    <div class="modal-body">
        <form id="createProvForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="nama_provinsi" class="form-label">Nama Provinsi</label>
                <input type=" text" name="nama_provinsi" class="form-control @error('nama_provinsi')is-invalid @enderror" id="nama_provinsi" value="{{old('nama_provinsi')}}" required>
                @error('nama_provinsi')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnCreate">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    // Simpan Data
    $('#btnCreate').click(function() {
        var namaProvinsi = $('#nama_provinsi').val();

        // Kirim data ke backend
        $.ajax({
            url: '/provinsi/store',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nama_provinsi: namaProvinsi,
            },
            success: function(response) {

                var konfirmasi = confirm('Data berhasil disimpan!');

                if (konfirmasi) {
                    window.location.href = '/provinsi';
                }
            },
            error: function(xhr, status, error) {

                console.error(xhr.responseText);
            }
        });
    });
</script>