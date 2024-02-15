<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\Provinsi;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $provinsi = $request['provinsi'];
        $kabupaten = $request['kabupaten'];
        $nama = $request['nama'];

        $idprovinsi = Provinsi::where('nama_provinsi', '=', $provinsi)->first();


        if (!empty($request["provinsi"]) && !empty($request["kabupaten"]) && !empty($request["nama"])) {
            $provID = $idprovinsi->id;
            $datawarga = Penduduk::where('penduduk.nama', '=', $nama)
                ->where('penduduk.kabupaten', '=', $kabupaten)
                ->where('penduduk.provinsi', '=', $provinsi)
                ->paginate(10);
            $datakabupaten = Kabupaten::where('provinsi_id', $provID)->get();
        } elseif (!empty($request["provinsi"]) && empty($request["kabupaten"]) && empty($request["nama"])) {
            $provID = $idprovinsi->id;
            $datawarga = Penduduk::where('penduduk.provinsi', '=', $provinsi)
                ->paginate(10);
            $datakabupaten = Kabupaten::where('provinsi_id', $provID)->get();
            // dd($datakabupaten);
        } elseif (empty($request["provinsi"]) && !empty($request["kabupaten"]) && empty($request["nama"])) {
            $datawarga = Penduduk::where('penduduk.kabupaten', '=', $kabupaten)
                ->paginate(10);
            $datakabupaten = Kabupaten::get();
        } elseif (empty($request["provinsi"]) && empty($request["kabupaten"]) && !empty($request["nama"])) {
            $provID = $idprovinsi->id;
            $datawarga = Penduduk::where('penduduk.nama', '=', $nama)
                ->paginate(10);
            $datakabupaten = Kabupaten::get();
        } else {
            $datawarga = Penduduk::paginate(10);
            $datakabupaten = Kabupaten::get();
        }

        $provinsi = Provinsi::get();
        $kabupaten = kabupaten::get();

        return view('contents.penduduk.index', compact('datawarga', 'request', 'provinsi', 'datakabupaten', 'kabupaten'));
    }

    public function create()
    {
        $provinsi = Provinsi::get();
        $kabupaten = Kabupaten::get();

        return view('contents.penduduk.create', compact('provinsi', 'kabupaten'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_penduduk' => 'required|string',
            'nik' => 'required|string|unique:penduduk,nik',
            'jenis_kelamin' => 'required|in:pria,wanita',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nama_provinsi' => 'required|string',
            'nama_kabupaten' => 'required|string',
        ]);

        $Penduduk = new Penduduk();
        $Penduduk->nama = $request->nama_penduduk;
        $Penduduk->nik = $request->nik;
        $Penduduk->jenis_kelamin = $request->jenis_kelamin;
        $Penduduk->tgl_lahir = $request->tgl_lahir;
        $Penduduk->alamat = $request->alamat;
        $Penduduk->provinsi = $request->nama_provinsi;
        $Penduduk->kabupaten = $request->nama_kabupaten;
        $Penduduk->save();

        return response()->json(['message' => 'Data penduduk berhasil disimpan'], 200);
    }

    public function edit($id)
    {
        $datawarga = Penduduk::where('id', '=', $id)->first();
        $provinsi = Provinsi::get();
        $kabupaten = Kabupaten::get();
        return view('contents.penduduk.edit', compact('datawarga', 'provinsi', 'kabupaten'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_penduduk' => 'required|string',
            'nik' => 'required|string',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nama_provinsi' => 'required|string',
            'nama_kabupaten' => 'required|string',
        ]);

        $Penduduk = Penduduk::findOrFail($id);
        $Penduduk->nama = $request->nama_penduduk;
        $Penduduk->nik = $request->nik;
        $Penduduk->jenis_kelamin = $request->jenis_kelamin;
        $Penduduk->tgl_lahir = $request->tgl_lahir;
        $Penduduk->alamat = $request->alamat;
        $Penduduk->provinsi = $request->nama_provinsi;
        $Penduduk->kabupaten = $request->nama_kabupaten;
        $Penduduk->save();

        return response()->json(['message' => 'Data penduduk berhasil diperbarui'], 200);
    }

    public function destroy($id)
    {
        $dataPenduduk = Penduduk::findOrFail($id);
        $dataPenduduk->delete();

        return response()->json(['message' => 'Data penduduk berhasil dihapus'], 200);
    }

    public function getKab(Request $request)
    {
        $idProv = Provinsi::where('nama_provinsi', $request->nama_provinsi)->get();
        dd($idProv);
        $data['kab'] = Kabupaten::where("provinsi_id", $request->country_id)
            ->get(["name", "id"]);

        return response()->json($data);
    }
}
