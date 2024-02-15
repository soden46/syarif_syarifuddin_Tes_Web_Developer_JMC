<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel{{ $data->id }}">Edit Data Penduduk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 15px; top: 15px;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="editForm{{ $data->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nama_penduduk" class="col-form-label">Nama</label>
                <input type="text" class="form-control" id="nama_penduduk{{ $data->id }}" name="nama_penduduk" value="{{ $data->nama }}">
            </div>
            <div class="form-group">
                <label for="nik" class="col-form-label">NIK:</label>
                <input type="text" class="form-control" id="nik{{ $data->id }}" name="nik" value="{{ $data->nik }}">
            </div>
            <div class="form-group">
                <label class="col-form-label">Jenis Kelamin</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jenis_kelamin{{ $data->id }}" id="pria{{ $data->id }}" value="Pria" {{ $data->jenis_kelamin == 'Pria' ? 'checked' : '' }}>
                    <label class="form-check-label" for="pria{{ $data->id }}">Pria</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jenis_kelamin{{ $data->id }}" id="wanita{{ $data->id }}" value="Wanita" {{ $data->jenis_kelamin == 'Wanita' ? 'checked' : '' }}>
                    <label class="form-check-label" for="wanita{{ $data->id }}">Wanita</label>
                </div>
            </div>
            <div class="form-group">
                <label for="tgl_lahir" class="col-form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tgl_lahir{{ $data->id }}" name="tgl_lahir" value="{{ $data->tgl_lahir }}">
            </div>
            <div class="form-group">
                <label for="alamat" class="col-form-label">Alamat</label>
                <textarea class="form-control" id="alamat{{ $data->id }}" name="alamat">{{ $data->alamat }}</textarea>
            </div>
            <div class="form-group">
                <label for="nama_provinsi" class="col-form-label">Nama Provinsi</label>
                <select class="form-control" id="nama_provinsi{{$data->id}}" name="nama_provinsi">
                    <option value="" disabled> Pilih Provinsi</option>
                    <option value="{{$data->provinsi}}" selected> {{$data->provinsi}}</option>
                    @foreach($provinsi as $prov)
                    @if ($prov->nama_provinsi != $data->provinsi)
                    <option value="{{ $prov->nama_provinsi }}" {{ $prov->nama_provinsi == $data->provinsi ? 'selected' : '' }}> {{ $prov->nama_provinsi }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="nama_kabupaten" class="col-form-label">Nama Kabupaten</label>
                <select class="form-control" id="nama_kabupaten{{$data->id}}" name="nama_kabupaten">
                    <option value="" disabled> Pilih Kabupaten</option>
                    <option value="{{$data->kabupaten}}" selected> {{$data->kabupaten}}</option>
                    @foreach($kabupaten as $kab)
                    @if ($kab->nama_kabupaten != $data->kabupaten)
                    <option value="{{ $kab->nama_kabupaten }}" {{ $kab->nama_kabupaten == $data->kabupaten ? 'selected' : '' }}> {{ $kab->nama_kabupaten }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnEdit{{ $data->id }}">Simpan Perubahan</button>
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
                    url: 'data-penduduk/getKab/' + provinsi,
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
        $('[id^=btnEdit]').click(function() {
            var id = $(this).attr('id').replace('btnEdit', '');
            var namaPenduduk = $('#nama_penduduk' + id).val();
            var nik = $('#nik' + id).val();
            var jenisKelamin = $('input[name="jenis_kelamin' + id + '"]:checked').val(); // Perbaiki di sini
            var tglLahir = $('#tgl_lahir' + id).val();
            var alamat = $('#alamat' + id).val();
            var namaProvinsi = $('#nama_provinsi' + id).val();
            var namaKabupaten = $('#nama_kabupaten' + id).val();

            // Kirim data ke backend untuk disimpan
            $.ajax({
                url: '/data-penduduk/update/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    nama_penduduk: namaPenduduk,
                    nik: nik,
                    jenis_kelamin: jenisKelamin, // Sudah diperbaiki
                    tgl_lahir: tglLahir,
                    alamat: alamat,
                    nama_provinsi: namaProvinsi,
                    nama_kabupaten: namaKabupaten
                },
                success: function(response) {
                    var konfirmasi = confirm('Data berhasil diperbarui!');
                    if (konfirmasi) {
                        window.location.href = '/data-penduduk';
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Set nilai opsi kabupaten sesuai data yang ada di tabel penduduk
        var id = '{{ $data->id }}';
        var namaKabupaten = '{{ $data->kabupaten }}';
        $('#nama_kabupaten' + id).val(namaKabupaten);
    });
</script>