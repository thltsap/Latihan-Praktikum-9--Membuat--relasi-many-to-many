<!DOCTYPE html>
<html>

<head>
    <title>Sistem Informasi Mahasiswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <h4 class="text-center mt-3">Jurusan Teknologi Informasi - Politeknik Negeri Malang</h4>
    <h3 class="text-center">Kartu Hasil Studi(KHS)</h3>

    <div class="mt-5">
        <h6><b>Nama: </b> {{$mahasiswa->nama}} </h6>
        <h6><b>NIM: </b> {{$mahasiswa->nim}} </h6>
        <h6><b>Kelas: </b> {{$mahasiswa->kelas->nama_kelas}} </h6>
    </div>

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

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>