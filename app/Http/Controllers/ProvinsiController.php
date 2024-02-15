<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use Illuminate\Http\Request;

class ProvinsiController extends Controller
{
    // Controller Tabel Data Provinsi
    public function index(Request $request)
    {
        $prov = $request['provinsi'];
        $nama = $request['nama'];

        // Seleksi Data Jika Kolom Provinsi dan Nama Diisi
        if (!empty($request["provinsi"])) {
            $provinsi = Provinsi::where('provinsi.nama_provinsi', '=', $prov)
                ->paginate(10);
        } else {
            $provinsi = Provinsi::paginate(10);
        }
        $provin = Provinsi::get();
        return view('contents.prov.index', compact('provinsi', 'provin', 'request'));
    }

    // Controller View Tambah Data
    public function create()
    {
        return view('contents.prov.create');
    }

    // Controller Tambah Data Ke Database
    public function store(Request $request)
    {
        // Validasi Data
        $request->validate([
            'nama_provinsi' => 'required',
        ]);

        // Simpan Data Ke Database
        Provinsi::create([
            'nama_provinsi' => $request->nama_provinsi,
        ]);

        return response()->json(['message' => 'Data provinsi berhasil disimpan'], 200);
    }

    public function edit($id)
    {

        $provinsi = Provinsi::where('id', '=', $id)->first();
        return view('contents.prov.edit', compact('provinsi'));
    }

    // Controller Update Data Ke Database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_provinsi' => 'required|string',
        ]);

        $Provinsi = Provinsi::findOrFail($id);
        $Provinsi->nama_provinsi = $request->nama_provinsi;
        $Provinsi->save();

        return response()->json(['message' => 'Data provinsi berhasil diperbarui'], 200);
    }
    // Controller Hapus Data
    public function destroy($id)
    {
        $dataProvinsi = Provinsi::findOrFail($id);
        $dataProvinsi->delete();

        return response()->json(['message' => 'Data provinsi berhasil dihapus'], 200);
    }
}
