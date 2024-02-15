<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="editProvLabel{{ $data->id }}">Edit Data Provinsi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 15px; top: 15px;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="editForm{{ $data->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="nama_provinsi" class="form-label">Nama Provinsi</label>
                <input type="text" name="nama_provinsi" class="form-control @error('nama_provinsi')is-invalid @enderror" id="nama_provinsi{{ $data->id }}" value="{{$data->nama_provinsi,old('nama_provinsi')}}" required>
                @error('nama_provinsi')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnEditProv{{ $data->id }}">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Fungsi untuk menyimpan perubahan saat tombol "Simpan Perubahan" ditekan
        $('[id^=btnEditProv]').click(function() {
            var id = $(this).attr('id').replace('btnEditProv', '');
            var namaProvinsi = $('#nama_provinsi' + id).val();
            // Kirim data ke backend untuk disimpan
            $.ajax({
                url: '/provinsi/update/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    nama_provinsi: namaProvinsi,
                },
                success: function(response) {
                    var konfirmasi = confirm('Data berhasil diperbarui!');
                    if (konfirmasi) {
                        window.location.href = '/provinsi';
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>