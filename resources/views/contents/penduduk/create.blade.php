<!-- Modal untuk Tambah Data -->

<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 15px; top: 15px;">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="createModalLabel" style="margin-left: 15px;">Tambah Data Penduduk</h5>
    </div>
    <div class="modal-body">
        <form id="createForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nama" class="col-form-label">Nama</label>
                <input type="text" class="form-control" id="nama_penduduk" name="nama_penduduk">
            </div>
            <div class="form-group">
                <label for="nik" class="col-form-label">NIK:</label>
                <input type="text" class="form-control" id="nik" name="nik">
            </div>
            <div class="form-group">
                <label class="col-form-label">Jenis Kelamin</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="pria" value="pria">
                    <label class="form-check-label" for="pria">Pria</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="wanita" value="wanita">
                    <label class="form-check-label" for="wanita">Wanita</label>
                </div>
            </div>
            <div class="form-group">
                <label for="tgl_lahir" class="col-form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir">
            </div>
            <div class="form-group">
                <label for="alamat" class="col-form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat"></textarea>
            </div>
            <div class="form-group">
                <label for="provinsi" class="col-form-label">Provinsi</label>
                <select class="form-control" id="nama_provinsi" name="nama_provinsi">
                    <option id="nama_provinsi" name="nama_provinsi" value="" selected> Pilih Provinsi</option>
                    @foreach($provinsi as $prov)
                    <option id="provinsi" name="provinsi" value="{{$prov->nama_provinsi}}"> {{$prov->nama_provinsi}}</option>
                    @endforeach
                </select>
            </div>
            <div class="from-group">
                <label for="kabupaten" class="form-label">Kabupaten</label>
                <select class="form-control" name="nama_kabupaten" id="nama_kabupaten">
                    <option name="nama_kabupaten" id="nama_kabupaten" value="" selected>Silakan Pilih Kabupaten</option>
                    @foreach($kabupaten as $kab)
                    <option name="nama_kabupaten" id="nama_kabupaten" value="{{$kab->nama_kabupaten}}">{{$kab->nama_kabupaten}}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnCreate">Simpan</button>
    </div>
</div>
<script>
    // Dependent Select Kabupaten Berdasarkan Provinsi
    $('#nama_provinsi').on('change', function() {
        event.preventDefault();
        var provinsi = $(this).val();
        if (provinsi) {
            var newUrl = "{{ route('data-penduduk/getKab', ':provinsi') }}";
            newUrl = newUrl.replace(':provinsi', provinsi);
            $.ajax({
                url: newUrl,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#nama_kabupaten').empty();
                    $('#nama_kabupaten').append('<option value="" selected disabled>Silakan Pilih Kabupaten</option>');
                    $.each(data, function(key, kabupaten) {
                        $('#nama_kabupaten').append('<option value="' + kabupaten.nama_kabupaten + '">' + kabupaten.nama_kabupaten + '</option>');
                    });
                }
            });
        } else {
            $('#nama_kabupaten').empty();
        }
    });

    // Simpan Data
    $('#btnCreate').click(function() {
        var namaPenduduk = $('#nama_penduduk').val();
        var nik = $('#nik').val();
        var jenisKelamin = $('input[name="jenis_kelamin"]:checked').val();
        var tglLahir = $('#tgl_lahir').val();
        var alamat = $('#alamat').val();
        var namaProvinsi = $('#nama_provinsi').val();
        var namaKabupaten = $('#nama_kabupaten').val();

        // Kirim data ke backend
        $.ajax({
            url: '/data-penduduk/store',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nama_penduduk: namaPenduduk,
                nik: nik,
                jenis_kelamin: jenisKelamin,
                tgl_lahir: tglLahir,
                alamat: alamat,
                nama_provinsi: namaProvinsi,
                nama_kabupaten: namaKabupaten
            },
            success: function(response) {

                var konfirmasi = confirm('Data berhasil disimpan!');

                if (konfirmasi) {
                    window.location.href = '/data-penduduk';
                }
            },
            error: function(xhr, status, error) {

                console.error(xhr.responseText);
            }
        });
    });
</script>