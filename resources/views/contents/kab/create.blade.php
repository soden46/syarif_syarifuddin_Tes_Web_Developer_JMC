<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 15px; top: 15px;">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="createKabLabel" style="margin-left: 15px;">Tambah Data Kabupaten</h5>
    </div>
    <div class="modal-body">
        <form id="createKabForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="form-group">
                    <label for="provinsi" class="col-form-label">Nama Provinsi</label>
                    <select class="form-control" id="nama_provinsi" name="nama_provinsi">
                        <option id="nama_provinsi" name="nama_provinsi" value="" selected> Pilih Provinsi</option>
                        @foreach($provinsi as $prov)
                        <option id="nama_provinsi" name="nama_provinsi" value="{{$prov->id}}"> {{$prov->nama_provinsi}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="nama_kabupaten" class="form-label">Nama Kabupaten</label>
                <input type=" text" name="nama_kabupaten" class="form-control @error('nama_kabupaten')is-invalid @enderror" id="nama_kabupaten" value="{{old('nama_kabupaten')}}" required>
                @error('nama_kabupaten')
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
        var namaKab = $('#nama_kabupaten').val();
        var ProvId = $('#nama_provinsi').val();

        // Kirim data ke backend
        $.ajax({
            url: '/kabupaten/store',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nama_kabupaten: namaKab,
                nama_provinsi: ProvId,

            },
            success: function(response) {

                var konfirmasi = confirm('Data berhasil disimpan!');

                if (konfirmasi) {
                    window.location.href = '/kabupaten';
                }
            },
            error: function(xhr, status, error) {

                console.error(xhr.responseText);
            }
        });
    });
</script>