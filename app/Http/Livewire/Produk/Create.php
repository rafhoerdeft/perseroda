<?php

namespace App\Http\Livewire\Produk;

use App\Models\Produk;
use App\Models\Tarif;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public $form_title;

    public $produk_id;
    public $nama_produk;
    public $stok_produk;
    public $stok_minimal;
    public $satuan_produk;
    public $harga;

    protected $rules = [
        'nama_produk'  => 'required|string',
        'stok_produk'  => 'required|integer',
        'stok_minimal'  => 'required|integer',
        'satuan_produk'  => 'nullable',
        'harga'  => 'required|regex:/^[0-9\.,]+$/|not_in:0'
    ];

    protected $listeners = [
        'refreshForm' => '$refresh',
    ];

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $data_produk = [
                'nama_produk'   => $this->nama_produk,
                'stok_produk'   => $this->stok_produk,
                'stok_minimal'  => $this->stok_minimal,
                'satuan_produk' => $this->satuan_produk,
            ];

            if ($this->produk_id) {
                $produk_id = decode($this->produk_id);
            } else {
                $produk_id = null;
                $kode_produk = auto_code('kode_produk', 'produk', 'PD', 4);
                $data_produk['kode_produk'] = $kode_produk;
                $data_produk['barcode'] = 0 . date('ymd') . $kode_produk . random_int(0, 9);
            }

            $exist_id = ['id' => $produk_id];

            $produk = Produk::updateOrCreate($exist_id, $data_produk);

            if ($produk_id == null) {
                $produk_id = $produk->id;
            }

            $data_tarif = [
                // 'unit_usaha_id' => 1,
                'nama_tarif'    => $this->nama_produk,
                'harga'         => rm_nominal($this->harga),
                'satuan_tarif'  => $this->satuan_produk,
            ];
            // $find_produk = ProdukModel::find($produk->id);
            // $find_produk->tarif()->create($data_tarif);

            $exist_id = ['produk_id' => $produk_id];
            Tarif::updateOrCreate($exist_id, $data_tarif);

            DB::commit();
            alert_success('Data produk berhasil disimpan.');
            $this->emit('refreshProduk');
        } catch (\Exception $e) {
            DB::rollBack();
            alert_failed('Data produk gagal disimpan.' . $e->getMessage());
        }
        $this->reset();
        // return redirect()->route('base');
    }

    public function render()
    {
        return view('livewire.produk.create');
    }
}
