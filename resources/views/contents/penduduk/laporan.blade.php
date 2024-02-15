@extends('layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Laporan Data Penduduk</h6>
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
    </div>
    <div class="card-body">
        <div class="row">
            <form action="{{ route('data-penduduk/cari') }}" method="GET" class="w-100">
                <div class="form-inline col col-md-12 d-flex flex-wrap align-items-start">
                    <div class="col-lg col-md-2 mb-2">
                        <a href="{{route('cetak-excel')}}" class="btn btn-success">Cetak Laporan</a>
                    </div>
                    <div class="col-lg col-md-2 mb-2">
                        <select class="form-control" id="provinsi" name="provinsi">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinsi as $data)
                            <option value="{{ $data->nama_provinsi }}" {{ request()->input('provinsi') == $data->nama_provinsi ? 'selected' : '' }}>
                                {{ $data->nama_provinsi }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg col-md-2 ">
                        <select class="form-control" id="kabupaten" name="kabupaten">
                            <option value="">Pilih Kabupaten</option>
                            @foreach($kabupaten as $data)
                            <option value="{{ $data->nama_kabupaten }}" {{ request()->input('kabupaten') == $data->nama_kabupaten ? 'selected' : '' }}>
                                {{ $data->nama_kabupaten }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" name="dataTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
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
                        <td>{{$data->nama}}</td>
                        <td>{{$data->nik}}</td>
                        <td>{{$data->tgl_lahir}}</td>
                        <td>{{$data->alamat.", ".$data->Kabupaten.", ".$data->Provinsi}}</td>
                        <td>{{$data->jenis_kelamin}}</td>
                        <td>{{$data->created_at}}</td>

                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" class="text-center">
                            Total Penduduk: {!!$jumlah!!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex">
                {!! $datawarga->links() !!}
            </div>
        </div>
    </div>
    <!-- Filter Data Tabel Berdasarkan Provinsi atau Kabupaten -->
    <script>
        // Filter Data Tabel Laporan
        $(document).ready(function() {

            var prov = $("#provinsi");
            var kab = $("#kabupaten");
            var nama = $("#nama");

            // Filter Tabel Berdasarkan Provinsi
            if (prov !== "") {
                $("#provinsi").change(function() {
                    var the_selected_prov = $(this).val();
                    window.location = "/laporan/cari?provinsi=" + the_selected_prov;
                });
            }
            if (kab !== "") {
                // Filter Tabel Berdasarkan Kabupaten
                $("#kabupaten").change(function() {
                    var the_selected_kab = $(this).val();
                    window.location = "/laporan/cari?kabupaten=" + the_selected_kab;
                });
            }

        });
        // Filter Data Ekport Excel
        function laporan() {
            $(document).ready(function() {

                var prov = $("#provinsi");
                var kab = $("#kabupaten");
                // Filter Tabel Berdasarkan Provinsi
                if (prov !== "") {
                    $("#btnexport").click(function() {
                        var the_selected_prov = $(this).val();
                        window.location = "/excel?provinsi=" + the_selected_prov;
                    });
                }
                if (kab !== "") {
                    // Filter Tabel Berdasarkan Kabupaten
                    $("#btnexport").click(function() {
                        var the_selected_kab = $(this).val();
                        window.location = "/excel?kabupaten=" + the_selected_kab;
                    });
                }
            });
        }
    </script>
    @endsection