@extends('mahasiswas.layout')
@section('content')
<h2 class="text-center mt-3">Jurusan Teknologi Informasi - Politeknik Negeri Malang</h2>
<h1 class="text-center">Kartu Hasil Studi(KHS)</h2>

    <h4><b>Nama: </b> {{$mahasiswa->nama}} </h4>
    <h4><b>NIM: </b> {{$mahasiswa->nim}} </h4>
    <h4><b>Kelas: </b> {{$mahasiswa->kelas->nama_kelas}} </h4>

    <table class="table table-bordered mt-3">
        <tr>
            <th width="300px">Mata Kuliah</th>
            <th>SKS</th>
            <th>Semester</th>
            <th>Nilai</th>
        </tr>
        @foreach($mahasiswa->matakuliah as $matakuliah)
        <tr>
            <td>{{$matakuliah->nama_matkul}}</td>
            <td>{{$matakuliah->sks}}</td>
            <td>{{$matakuliah->semester}}</td>
            <td>{{$matakuliah->pivot->nilai}}</td>
        </tr>
        @endforeach

    </table>
    <div class="text-center">
        <a href="{{route('mahasiswa.cetak.khs', $mahasiswa->nim)}}" class="btn btn-danger text-center"> <i class="fas fa-file-pdf" aria-hidden="true"></i> Cetak KHS</a>
    </div>
    @endsection