<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Laravel\Prompts\Key;

class KabupatenController extends Controller
{
    public function index(Request $request)
    {
        $provinsi = $request['provinsi'];
        $kabupaten = $request['kabupaten'];

        $idprovinsi = Provinsi::where('nama_provinsi', '=', $provinsi)->first();

        if (!empty($request["provinsi"]) && !empty($request["kabupaten"])) {
            $datakabupaten = Kabupaten::where('kabupaten.nama_kabupaten', '=', $kabupaten)
                ->where('kabupaten.provinsi_id', '=', $provinsi)
                ->paginate(10);
        } elseif (!empty($request["provinsi"]) && empty($request["kabupaten"])) {
            $datakabupaten = Kabupaten::where('kabupaten.provinsi_id', '=', $provinsi)
                ->paginate(10);
        } elseif (empty($request["provinsi"]) && !empty($request["kabupaten"])) {
            $datakabupaten = Kabupaten::where('kabupaten.nama_kabupaten', '=', $kabupaten)
                ->paginate(10);
        } else {
            $datakabupaten = Kabupaten::paginate(10);
        }

        // Ambil Data Provinsi
        $provinsi = Provinsi::get();

        // Ambil data Kabupaten Berdasarkan ID Provinsi
        $kabupaten = Kabupaten::where('provinsi_id', '=', $idprovinsi)->get();


        return view('contents.kab.index', compact('datakabupaten', 'request', 'provinsi', 'kabupaten'));
    }

    // Controller View Tambah Data
    public function create()
    {
        $provinsi = Provinsi::get();
        $kabupaten = Kabupaten::get();
        return view('contents.kab.create', compact('provinsi', 'kabupaten'));
    }

    // Controller Tambah Data Ke Database
    public function store(Request $request)
    {
        // Validasi Data
        $request->validate([
            'nama_kabupaten' => 'required',
            'nama_provinsi' => 'required',
        ]);

        // Simpan Data Ke Database
        kabupaten::create([
            'nama_kabupaten' => $request->nama_kabupaten,
            'provinsi_id' => $request->nama_provinsi,
        ]);

        return response()->json(['message' => 'Data kabupaten berhasil disimpan'], 200);
    }

    public function edit($id)
    {

        $kabupaten = Kabupaten::where('id', $id)->first();
        $provi = Provinsi::where('id', $kabupaten->provinsi_id)->first();
        return view('contents.kab.edit', compact('kabupaten', 'provi'));
    }

    // Controller Update Data Ke Database
    public function update(Request $request, $id)
    {
        // Validasi Data
        $request->validate([
            'nama_kabupaten' => 'required',
            'nama_provinsi' => 'required',
        ]);
        // Simpan Data Ke Database
        kabupaten::where('id', '=', $id)->update([
            'provinsi_id' => $request->nama_provinsi,
            'nama_kabupaten' => $request->nama_kabupaten
        ]);

        return response()->json(['message' => 'Data kabupaten berhasil diupdate'], 200);
    }

    // Controller Hapus Data
    public function destroy($id)
    {
        $dataProvinsi = Kabupaten::findOrFail($id);
        $dataProvinsi->delete();

        return response()->json(['message' => 'Data provinsi berhasil dihapus'], 200);
    }
}
