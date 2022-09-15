<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Mahasiswa_MataKuliah;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //fungsi eloquent menampilkan data menggunakan pagination
        //$mahasiswas = Mahasiswa::all(); // Mengambil semua isi tabel
        $mahasiswas = Mahasiswa::where([
            ['nim', '!=', null, 'OR', 'nama', '!=', null], //ketika form search kosong, maka request akan null. Ambil semua data di database
            [function ($query) use ($request) {
                if (($keyword = $request->keyword)) {
                    $query->orWhere('nim', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('nama', 'LIKE', '%' . $keyword . '%')->get(); //ketika form search terisi, request tidak null. Ambil data sesuai keyword
                }
            }]
        ])   
        ->orderBy('nim', 'asc')->paginate(3);
        return view('mahasiswas.index', compact('mahasiswas'))->
        with('i', (request()->input('page', 1) - 1) * 5);

        //yang semula Mahasiswa::all, diubah menjadi with() yang menyatakan relasi
        $mahasiswa = Mahasiswa::with('kelas')->get();
        $paginate = Mahasiswa::orderBy('nim','asc')->paginate(3);
        return view('mahasiswas.index',['mahasiswas' =>$mahasiswa,'paginate'=>$paginate]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::all(); //mendapatkan data dari tabel kelas    
        return view('mahasiswas.create',['kelas' => $kelas]);
    }     

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            // 'Foto' => 'required|mimes:jpg,png|dimensions:max_width=100,max_height=100',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
            'Email' => 'required|email',
            'Tanggal_Lahir' => 'required|date',
            ]);

            $mahasiswa = new Mahasiswa;
            $mahasiswa->nim = $request->get('Nim');
            $mahasiswa->nama = $request->get('Nama');
            $mahasiswa->jurusan = $request->get('Jurusan');
            $mahasiswa->no_handphone = $request->get('No_Handphone');
            $mahasiswa->email = $request->get('Email');
            $mahasiswa->tanggal_lahir = $request->get('Tanggal_Lahir');

             //Menyimpan gambar
        //     if($request->file('Foto')){
        //         $image_dir = $request->file('Foto')->store('images/mahasiswa/profil', 'public');
        //         $mahasiswa->foto = $image_dir;
        // }
            
            $kelas = new Kelas();
            $kelas->id = $request->get('Kelas');
            $mahasiswa->kelas()->associate($kelas);
            $mahasiswa->save();


            //fungsi eloquent untuk menambah data
            //Mahasiswa::create($request->all());
            //jika data berhasil ditambahkan, akan kembali ke halaman utama
            return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        //$Mahasiswa = Mahasiswa::find($nim);
        $mahasiswa = Mahasiswa::with('kelas')->where('Nim', $nim)->first();
        return view('mahasiswas.detail',['Mahasiswa'=> $mahasiswa]);
    }
    public function khs($nim)
    {
        $mahasiswa = Mahasiswa::with('Kelas', 'matakuliah')->where('Nim', $nim)->first();
        return view('mahasiswas.khs', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        //$Mahasiswa = Mahasiswa::find($nim);
        $Mahasiswa = Mahasiswa::with('kelas')->where('Nim', $nim)->first();
        $kelas = Kelas::all();
        return view('mahasiswas.edit', compact('Mahasiswa','kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nim)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Foto' => 'nullable|mimes:jpg,png|dimensions:max_width=100,max_height=100',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
            'Email' => 'required|email',
            'Tanggal_Lahir' => 'required|date',
            ]);

            $mahasiswa = Mahasiswa::with('Kelas')->where('Nim', $nim)->first();
            $mahasiswa->nim = $request->get('Nim');
            $mahasiswa->nama = $request->get('Nama');
            $mahasiswa->jurusan = $request->get('Jurusan');
            $mahasiswa->no_handphone = $request->get('No_Handphone');
            $mahasiswa->email = $request->get('Email');
            $mahasiswa->tanggal_lahir = $request->get('Tanggal_Lahir');

            //Menghapus gambar profil yang sama
            if($mahasiswa->foto && file_exists(storage_path('app/public' . $mahasiswa->foto))){
                Storage::delete('public/' . $mahasiswa->foto);
        }

        //Menyimpan gambar perubahan jika ada
            if($request->file('Foto')){
                $image_dir = $request->file('Foto')->store('images/mahasiswa/profil', 'public');
                $mahasiswa->foto = $image_dir;
        }

        //Menyimpan id kelas yang merupakan foreign key
            $kelas = new Kelas();
            $kelas->id = $request->get('Kelas');
            $mahasiswa->kelas()->associate($kelas);
            $mahasiswa->save();

        //fungsi eloquent untuk mengupdate data inputan kita
            //Mahasiswa::find($nim)->update($request->all());
        //jika data berhasil diupdate, akan kembali ke halaman utama
            return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Diupdate');
   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($nim)
    {
        //fungsi eloquent untuk menghapus data
        Mahasiswa::find($nim)->delete();
        return redirect()->route('mahasiswa.index')
        -> with('success', 'Mahasiswa Berhasil Dihapus');

    }
    public function cetak($nim){
        $mahasiswa = Mahasiswa::with('kelas', 'matakuliah')->where('nim', $nim)->first();
        $pdf = PDF::loadView('mahasiswas.cetak', compact('mahasiswa'));
        return $pdf->stream();
    }
}
