<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="editKabLabel{{ $data->id }}">Edit Data Kabupaten</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 15px; top: 15px;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="editForm{{ $data->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nama_provinsi" class="col-form-label">Nama Provinsi</label>
                <select class="form-control" id="nama_provinsi{{$data->id}}" name="nama_provinsi">
                    <option value="" disabled> Pilih Provinsi</option>
                    @foreach($provinsi as $prov)
                    @if ($prov->id != $data->provinsi_id)
                    <option value="{{ $prov->id }}"> {{ $prov->nama_provinsi }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="nama_kabupaten" class="form-label">Nama Kabupaten</label>
                <input type="text" name="nama_kabupaten" class="form-control @error('nama_kabupaten')is-invalid @enderror" id="nama_kabupaten{{ $data->id }}" value="{{$data->nama_kabupaten,old('nama_kabupaten')}}" required>
                @error('nama_kabupaten')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnEditKab{{ $data->id }}">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Fungsi untuk mengubah dropdown kabupaten berdasarkan provinsi yang dipilih
        $('[id^=nama_provinsi]').on('change', function() {
            var id = $(this).attr('id').replace('nama_provinsi', '');
            var provinsi = $(this).val();
            if (provinsi) {
                $.ajax({
                    url: 'provinsi/getKab/' + provinsi,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#nama_kabupaten' + id).empty();
                        $('#nama_kabupaten' + id).append('<option value="" selected disabled>Silakan Pilih Kabupaten</option>');
                        $.each(data, function(key, kabupaten) {
                            $('#nama_kabupaten' + id).append('<option value="' + kabupaten.nama_kabupaten + '">' + kabupaten.nama_kabupaten + '</option>');
                        });
                    }
                });
            } else {
                $('#nama_kabupaten' + id).empty();
            }
        });
        // Fungsi untuk menyimpan perubahan saat tombol "Simpan Perubahan" ditekan
        $('[id^=btnEditKab]').click(function() {
            var id = $(this).attr('id').replace('btnEditKab', '');
            var namaProv = $('#nama_provinsi' + id).val();
            var namaKab = $('#nama_kabupaten' + id).val();
            // Kirim data ke backend untuk disimpan
            $.ajax({
                url: '/kabupaten/update/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    nama_provinsi: namaProv,
                    nama_kabupaten: namaKab
                },
                success: function(response) {
                    var konfirmasi = confirm('Data berhasil diperbarui!');
                    if (konfirmasi) {
                        window.location.href = '/kabupaten';
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>