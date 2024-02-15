@extends('layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Penduduk</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <form action="{{ route('data-penduduk/cari') }}" method="GET" class="w-100">
                <div class="form-inline col col-md-12 d-flex flex-wrap align-items-start">
                    <div class="col-lg col-md-2 mb-2">
                        <button type="button" class="btn btn-md btn-success create-modal-btn" data-toggle="modal" data-target="#createModal">Tambah Data</button>
                    </div>

                    <div class="col-lg col-md-2 mb-2">
                        <select class="form-control" id="provinsi" name="provinsi">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinsi as $data)
                            <option value="{{ $data->nama_provinsi }}" id="provinsi" {{ request()->input('provinsi') == $data->nama_provinsi ? 'selected' : '' }}>
                                {{ $data->nama_provinsi }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg col-md-2 ">
                        <select class="form-control" id="kabupaten" name="kabupaten">
                            <option value="">Pilih Kabupaten</option>
                            @foreach($datakabupaten as $data)
                            <option value="{{ $data->nama_kabupaten }}" {{ request()->input('kabupaten') == $data->nama_kabupaten ? 'selected' : '' }}>
                                {{ $data->nama_kabupaten }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-2 ">
                        <input class="form-control" type="text" id="nama" name="nama" placeholder="Pencarian Nama">
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Create -->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                @include('contents.penduduk.create')
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" name="dataTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Aksi</th>
                        <th scope="col">Nama</th>
                        <th scope="col">NIK</th>
                        <th scope="col">Tanggal Lahir</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Jenis Kelamin</th>
                        <th scope="col">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datawarga as $data)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>
                            <a href="#" class="btn btn-success edit-modal-btn" data-toggle="modal" data-target="#editModal{{ $data->id }}">
                                <span class="glyphicon glyphicon-pencil">Edit</span>
                            </a>
                            <button class="btn btn-danger delete-btn" data-id="{{ $data->id }}">
                                <span class="glyphicon glyphicon-trash">Hapus</span>
                            </button>
                        </td>
                        <td>{{$data->nama}}</td>
                        <td>{{$data->nik}}</td>
                        <td>{{$data->tgl_lahir}}</td>
                        <td>{{$data->alamat.", ".$data->kabupaten.", ".$data->provinsi}}</td>
                        <td>{{$data->jenis_kelamin}}</td>
                        <td>{{$data->created_at}}</td>
                    </tr>
                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $data->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            @include('contents.penduduk.edit')
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <script>
        // Filter Data Tampilan data-Penduduk
        $(document).ready(function() {

            var prov = $("#provinsi");
            var kab = $("#kabupaten");

            // Filter Tabel Berdasarkan Provinsi
            if (prov !== "") {
                $("#provinsi").change(function() {
                    var the_selected_prov = $(this).val();
                    window.location = "{{route('data-penduduk/cari')}}?provinsi=" + the_selected_prov;
                    event.preventDefault();
                });
            }
            if (kab !== "") {
                // Filter Tabel Berdasarkan Kabupaten
                $("#kabupaten").change(function() {
                    var the_selected_kab = $(this).val();
                    window.location = "{{route('data-penduduk/cari')}}?kabupaten=" + the_selected_kab;
                    event.preventDefault();
                });
            }

        });



        $(document).ready(function() {
            $('.delete-btn').on('click', function(event) {
                event.preventDefault(); // Menghentikan perilaku default tombol

                var button = $(this);
                var id = button.data('id');
                var url = '/data-penduduk/' + id;

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        var konfirmasi = confirm('Data berhasil dihapus!');

                        if (konfirmasi) {
                            window.location.href = '/data-penduduk';
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Terjadi kesalahan. Data tidak dapat dihapus.');
                    }
                });
            });
        });
    </script>
    @endsection