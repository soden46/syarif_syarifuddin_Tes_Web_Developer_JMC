<?php

namespace App\Http\Controllers;

use App\Exports\EksportExcel;
use App\Models\kabupaten;
use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\Provinsi;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function laporan(Request $request)
    {
        $prov = $request['provinsi'];
        $kab = $request['kabupaten'];
        $nama = $request['nama'];

        // Ambil Data ID Provinsi Berdasarkan Filter Yang Dipilih
        $idprov = Provinsi::where('nama_provinsi', '=', $prov)->value('id');

        // Seleksi Data Jika Kolom Provinsi, Kabupaten dan Nama Diisi
        if (!empty($request["provinsi"]) && !empty($request["kabupaten"]) && !empty($request["nama"])) {
            $datawarga = Penduduk::where('penduduk.nama', '=', $nama)
                ->where('penduduk.kabupaten', '=', $kab)
                ->where('penduduk.provinsi', '=', $prov)
                ->paginate(10);
        } elseif (!empty($request["provinsi"]) && empty($request["kabupaten"]) && empty($request["nama"])) {
            $datawarga = Penduduk::where('penduduk.provinsi', '=', $prov)
                ->paginate(10);
        } elseif (empty($request["provinsi"]) && !empty($request["kabupaten"]) && empty($request["nama"])) {
            $datawarga = Penduduk::where('penduduk.kabupaten', '=', $kab)
                ->paginate(10);
        } elseif (empty($request["provinsi"]) && empty($request["kabupaten"]) && !empty($request["nama"])) {
            $datawarga = Penduduk::where('penduduk.nama', '=', $nama)
                ->paginate(10);
        } else {
            $datawarga = Penduduk::paginate(10);
        }

        // Menghitung Jumlah Warga
        $jumlah = $datawarga->count();
        // Ambil Data Provinsi
        $provinsi = Provinsi::get();
        // Ambil data Kabupaten Berdasarkan ID Provinsi
        $kabupaten = kabupaten::where('provinsi_id', '=', $idprov)->get();

        return view('contents.penduduk.laporan', compact('datawarga', 'request', 'jumlah', 'provinsi', 'kabupaten'));
    }

    public function cetak_excel(Request $request)
    {
        // Ambil data provinsi dan kabupaten dari request
        $prov = $request['prvs'];
        $kab = $request['kbptn'];
        $data = [];
        // Hitung total penduduk dari data yang diperoleh


        // Seleksi Data Jika Kolom Provinsi dan Kabupaten Diisi
        if (!empty($request["provinsi"]) && !empty($request["kabupaten"])) {
            $data = Penduduk::where('provinsi', '=', $prov)
                ->orWhere('kabupaten', '=', $kab)
                ->get([
                    'id',
                    'nama',
                    'nik',
                    'jenis_kelamin',
                    'tgl_lahir',
                    'alamat',
                    'kabupaten',
                    'provinsi',
                    'created_at'
                ])->map(function ($penduduk) {
                    // Ubah format created_at menjadi tahun-bulan-tanggal jam:menit:detik
                    $penduduk['created_at'] = $penduduk['created_at']->format('Y-m-d H:i:s');
                    return $penduduk;
                })->toArray();
        } elseif (!empty($request["provinsi"]) && empty($request["kabupaten"])) {
            $data = Penduduk::where('provinsi', '=', $prov)
                ->get([
                    'id',
                    'nama',
                    'nik',
                    'jenis_kelamin',
                    'tgl_lahir',
                    'alamat',
                    'kabupaten',
                    'provinsi',
                    'created_at'
                ])->map(function ($penduduk) {
                    // Ubah format created_at menjadi tahun-bulan-tanggal jam:menit:detik
                    $penduduk['created_at'] = $penduduk['created_at']->format('Y-m-d H:i:s');
                    return $penduduk;
                })->toArray();
        } elseif (empty($request["provinsi"]) && !empty($request["kabupaten"])) {
            $data = Penduduk::where('kabupaten', '=', $kab)
                ->get([
                    'id',
                    'nama',
                    'nik',
                    'jenis_kelamin',
                    'tgl_lahir',
                    'alamat',
                    'kabupaten',
                    'provinsi',
                    'created_at'
                ])->map(function ($penduduk) {
                    // Ubah format created_at menjadi tahun-bulan-tanggal jam:menit:detik
                    $penduduk['created_at'] = $penduduk['created_at']->format('Y-m-d H:i:s');
                    return $penduduk;
                })->toArray();
        } else {
            $data = Penduduk::get([
                'id',
                'nama',
                'nik',
                'jenis_kelamin',
                'tgl_lahir',
                'alamat',
                'kabupaten',
                'provinsi',
                'created_at'
            ])->map(function ($penduduk) {
                // Ubah format created_at menjadi tahun-bulan-tanggal jam:menit:detik
                $penduduk['created_at'] = $penduduk['created_at']->format('Y-m-d H:i:s');
                return $penduduk;
            })->toArray();
        }

        $totalPenduduk = count($data);

        return Excel::download(new EksportExcel($data, $totalPenduduk), 'Laporan-Data-Penduduk.xls');
    }
}
