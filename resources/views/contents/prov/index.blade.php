@extends('layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <form action="{{route('provinsi/cari')}}" method="GET" class="w-100">
                <div class="form-inline col col-md-12 d-flex flex-wrap align-items-start">
                    <div class="col-lg-6 col-md-6 mb-3">
                        <button type="button" class="btn btn-md btn-success create-modal-btn" data-toggle="modal" data-target="#createProv">Tambah Data</button>
                    </div>
                    <div class="col-lg-6 col-md-6 mb-3">
                        <select class="form-control w-100" id="provinsi" name="provinsi">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provin as $data)
                            <option value="{{$data->nama_provinsi}}" {{request()->input('provinsi')==$data->nama_provinsi ? 'selected' : '' }}>{{$data->nama_provinsi}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Create -->
        <div class="modal fade" id="createProv" tabindex="-1" role="dialog" aria-labelledby="createProvLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                @include('contents.prov.create')
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" name="dataTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Aksi</th>
                        <th scope="col">Nama Provinsi</th>
                        <th scope="col">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($provinsi as $data)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>
                            <a href="#" class="btn btn-success edit-modal-btn" data-toggle="modal" data-target="#editProv{{ $data->id }}">
                                <span class="glyphicon glyphicon-pencil">Edit</span>
                            </a>
                            <button class="btn btn-danger delete-btn" data-id="{{ $data->id }}">
                                <span class="glyphicon glyphicon-trash">Hapus</span>
                            </button>
                        </td>
                        <td>{{$data->nama_provinsi}}</td>
                        <td>{{$data->created_at}}</td>
                    </tr>
                    <!-- Modal Edit -->
                    <div class="modal fade" id="editProv{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="editProvLabel{{ $data->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            @include('contents.prov.edit')
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        //Filter Data Tampilan Provinsi
        $(document).ready(function() {

            var prov = $("#provinsi");
            var nama = $("#nama");

            // Filter Tabel Berdasarkan Provinsi
            if (prov !== "") {
                $("#provinsi").change(function() {
                    var the_selected_prov = $(this).val();
                    window.location = "{{route('provinsi/cari')}}?provinsi=" + the_selected_prov;
                });
            }

        });

        $(document).ready(function() {
            $('.delete-btn').on('click', function(event) {
                event.preventDefault(); // Menghentikan perilaku default tombol

                var button = $(this);
                var id = button.data('id');
                var url = '/provinsi/delete/' + id;

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
                            window.location.href = '/provinsi';
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