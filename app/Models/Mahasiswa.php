<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

    class Mahasiswa extends Model //Definisi Model
{
    protected $table="mahasiswas"; // Eloquent akan membuat model mahasiswa menyimpan record di tabel mahasiswas

     
    protected $primaryKey = 'nim'; // Memanggil isi DB Dengan primarykey
    public $timestamps= false;

    /**
 * The attributes that are mass assignable. *
 * @var array
 */
    protected $fillable = [
        'Nim',
        'Nama',
        'Foto',
        'Kelas',
        'Jurusan',
        'No_Handphone',
        'Email',
        'Tanggal_Lahir',
        ];
    
        public function kelas(){
            return $this->belongsTo(Kelas::class);
        }
        public function matakuliah(){
            return $this->belongsToMany(Mahasiswa_MataKuliah::class, 'mahasiswa_matakuliah', 'mahasiswa_id', 'matakuliah_id')->withPivot('nilai');
        }
 };
