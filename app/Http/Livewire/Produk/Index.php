<?php

namespace App\Http\Livewire\Produk;

use App\Models\Produk as ModelsProduk;
use Livewire\Component;
// use Livewire\WithPagination;

class Index extends Component
{
    // use WithPagination;

    protected $listeners = [
        'refreshProduk' => '$refresh',
    ];

    // public $produk;
    public $name;
    public $readyToLoad = false;

    // public function mount()
    // {
    //     $this->produk = ModelsProduk::with('tarif')->latest()->paginate(5);
    // }

    public function loading()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.produk.index', ['produk_all' => $this->readyToLoad ? ModelsProduk::latest()->get() : []]);
    }
}
