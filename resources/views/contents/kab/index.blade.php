@extends('layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Kabupaten</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <form action="{{route('kabupaten/cari')}}" method="GET" class="w-100">
                <div class="form-inline col col-md-12 d-flex flex-wrap align-items-start">
                    <div class="col-lg col-md-2 mb-2">
                        <button type="button" class="btn btn-md btn-success create-modal-btn" data-toggle="modal" data-target="#createKab">Tambah Data</button>
                    </div>
                    <div class="col-lg col-md-2 mb-2">
                        <select class="form-control w-100" id="provinsi" name="provinsi">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinsi as $data)
                            <option value="{{$data->id}}" id="provinsi" {{request()->input('provinsi')==$data->id ? 'selected' : '' }}>{{$data->nama_provinsi}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg col-md-2 mb-2 ">
                        <select class="form-control w-100" id="kabupaten" name="kabupaten">
                            <option value="">Pilih Kabupaten</option>
                            @foreach($datakabupaten as $data)
                            <option value="{{$data->nama_kabupaten}}" id="kabupaten" {{request()->input('kabupaten')==$data->nama_kabupaten ? 'selected' : '' }}>{{$data->nama_kabupaten}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Create -->
        <div class="modal fade" id="createKab" tabindex="-1" role="dialog" aria-labelledby="createKabLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                @include('contents.kab.create')
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" name="dataTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Aksi</th>
                        <th scope="col">Nama Provinsi</th>
                        <th scope="col">Nama Kabupaten</th>
                        <th scope="col">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datakabupaten as $data)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>
                            <a href="#" class="btn btn-success edit-modal-btn" data-toggle="modal" data-target="#editKab{{ $data->id }}">
                                <span class="glyphicon glyphicon-pencil">Edit</span>
                            </a>
                            <button class="btn btn-danger delete-btn" data-id="{{ $data->id }}">
                                <span class="glyphicon glyphicon-trash">Hapus</span>
                            </button>
                        </td>
                        <td>{{$data->prov->nama_provinsi}}</td>
                        <td>{{$data->nama_kabupaten}}</td>
                        <td>{{$data->created_at}}</td>
                    </tr>
                    <!-- Modal Edit -->
                    <div class="modal fade" id="editKab{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="editKabLabel{{ $data->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            @include('contents.kab.edit')
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        // Filter Data Tampilan Kabupaten
        $(document).ready(function() {
            var prov = $("#provinsi");
            var kabupaten = $("#kabupaten");
            // Filter Tabel Berdasarkan Provinsi
            if (prov !== "") {
                $("#provinsi").change(function() {
                    var the_selected_prov = $(this).val();
                    window.location = "{{route('kabupaten/cari')}}?provinsi=" + the_selected_prov;
                    event.preventDefault();
                });
            }

            // Filter Tabel Berdasarkan Kabupaten
            if (kabupaten !== "") {
                $("#kabupaten").change(function() {
                    var the_selected_kab = $(this).val();
                    window.location = "{{route('kabupaten/cari')}}?kabupaten=" + the_selected_kab;
                    event.preventDefault();
                });
            }



        });
        $(document).ready(function() {
            $('.delete-btn').on('click', function(event) {
                event.preventDefault(); // Menghentikan perilaku default tombol

                var button = $(this);
                var id = button.data('id');
                var url = '/kabupaten/delete/' + id;

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
                            window.location.href = '/kabupaten';
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