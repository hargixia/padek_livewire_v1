<?php

use Livewire\Component;
use App\Http\Controllers\api_support;

use App\Models\data_materi;

new class extends Component
{

    public $datas;

    public $url;

    public int $permainan_id = 0;
    public int $urutan_materi = 1;
    public string $nama_materi = '';
    public string $jenis_materi = '';
    public string $deskripsi_materi = '';
    public string $isi_materi = '';

    public int $pos_tipe = 0;
    public $list_jenis = [
        ["tipe"=>"Gambar"   ,"view"=>'pages::permainan.⚡materi-tambah-1'],
        ["tipe"=>"Teks"     ,"view"=>'pages::permainan.⚡materi-tambah-2'],
        ["tipe"=>"Video"    ,"view"=>'pages::permainan.⚡materi-tambah-3']
    ];

    public function mount($data){
        $as = new api_support();
        $decrypt = $as->my_decrypt($data);
        $this->datas = $as->splitter(1,$decrypt);

        $this->permainan_id = $this->datas[1];
        $this->jenis_materi = $this->datas[2];
        $this->nama_materi = $this->datas[3];
        $this->deskripsi_materi = $this->datas[4];

        $data_materi = data_materi::where('permainan_id',$this->datas[1])->orderBy('urutan_materi','asc')->orderBy('updated_at','asc')->get();
        $count_dm = count($data_materi);

        $this->url = $as->my_encrypt("materi>>".$this->permainan_id);

        if($count_dm == 0){
            $this->urutan_materi = 1;
        }else{
            $this->urutan_materi = $count_dm + 1;
        }

        $this->pos_tipe = $this->validasi_tipe($this->jenis_materi);

        if($this->datas[0] != "tambah_materi" || $this->pos_tipe == 0){
            $parcel = $this->parcel_enc($this->permainan_id);
            return redirect()->route('permainan.materi',['data'=>$parcel])->with('gagal',"Tipe Materi ".$this->jenis_materi." Tidak Tersedia");
        }
    }

    public function parcel_enc($id)
    {
        $as = new api_support();
        return $as->my_encrypt("materi" . ">>" . $id);
    }

    public function validasi_tipe($value){

        $pos = 0;

        switch ($value) {
            case $this->list_jenis[0]['tipe']:
                $pos = 1;
                break;
            case $this->list_jenis[1]['tipe']:
                $pos = 2;
                break;
            case $this->list_jenis[2]['tipe']:
                $pos = 3;
                break;
            default:
                $pos = 0;
                break;
        }

        return $pos;
    }

    public function save(){
        $this->validate([
            'isi_materi' => $this->isi_materi,
        ]);

        data_materi::create([
            'permainan_id'     => $this->permainan_id,
            'urutan_materi'    => $this->urutan_materi,
            'nama_materi'      => $this->nama_materi,
            'deskripsi_materi' => $this->deskripsi_materi ?: null,
            'isi_materi'       => $this->isi_materi,
            'jenis_materi'     => $this->jenis_materi ?: 'Teks',
        ]);

        $as = new api_support();

        return redirect()->route('permainan.materi',['data'=>$this->url])->with('sukses', 'Materi berhasil ditambahkan.');
    }

    public function kembali(){

        return redirect()->route('permainan.materi',['data'=>$this->url])->with('gagal', 'Materi Batal ditambahkan.');
    }

    public function render(){
        $pos = $this->pos_tipe - 1;
        return view($this->list_jenis[$pos]['view']);
    }

};
?>

