@extends('mahasiswas.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left mt-2">
                 <h2>JURUSAN TEKNOLOGI INFORMASI-POLITEKNIK NEGERI MALANG</h2>
            </div>
            <form class="float-right form-inline" id="searchForm" method="get" action="{{ route('mahasiswa.index') }}" role="search">
            <div class="form-group">
                <input type="text" name="keyword" class="form-control" id="Keyword" aria-describedby="Keyword" placeholder="Nama/NIM" value="{{request()->query('keyword')}}">
            </div>
            <button type="submit" class="btn btn-primary mx-2">Cari</button>
            <a href="{{ route('mahasiswa.index') }}">
                <button type="button" class="btn btn-danger">Reset</button>
            </a>
        </form>
        <div class="my-2">
            <a class="btn btn-success" href="{{ route('mahasiswa.create') }}"> Input Mahasiswa </a>
        </div>
    </div>
    </div>
 
    @if ($message = Session::get('success'))
         <div class="alert alert-success">
                <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>Nim</th>
            <th>Nama</th>
            {{--  <th>Foto_Profil</th>  --}}
            <th>Kelas</th>
            <th>Jurusan</th>
            <th>No_Handphone</th>
            <th>Email</th>
            <th>Tanggal_Lahir</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($mahasiswas as $Mahasiswa)
        <tr>
            <td>{{$Mahasiswa->nim}}</td>                       
            <td>{{ $Mahasiswa->nama }}</td>
            {{--  <td>
                @if(!is_null($Mahasiswa->foto))
                    <img src="{{asset('storage/' . $Mahasiswa->foto)}}" width="100px">
                @endif
            </td>   --}}
            <td>{{ $Mahasiswa->kelas->nama_kelas }}</td>
            <td>{{ $Mahasiswa->jurusan }}</td>
            <td>{{ $Mahasiswa->no_handphone }} </td>       
             <td>{{ $Mahasiswa->email }}</td>
            <td>{{ $Mahasiswa->tanggal_lahir }}</td> 
            <td>
                <form action="{{route('mahasiswa.destroy', $Mahasiswa->nim) }}" method="POST">                            
                <a class="btn btn-info" href="{{ route('mahasiswa.show',$Mahasiswa->nim) }}">Show</a>
                <a class="btn btn-primary" href="{{ route('mahasiswa.edit',$Mahasiswa->nim) }}">Edit</a>
                <a class="btn btn-warning" href="{{ route('mahasiswa.khs',$Mahasiswa->nim) }}">Nilai</a>

                @csrf 
                @method('DELETE')

                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
            </td>
        </tr>
    </tbody>    
        @endforeach
    </table>
    <div class="d-flex ">
        {{ $mahasiswas->links() }}
    </div>
@endsection
