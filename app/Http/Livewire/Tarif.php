<?php

namespace App\Http\Livewire;

use App\Models\Tarif as ModelsTarif;
use Livewire\Component;

class Tarif extends Component
{
    public $tarif;
    public $name;

    public function mount()
    {
        $this->tarif = ModelsTarif::with('produk')->latest()->get();
    }

    public function render()
    {
        return view('livewire.tarif');
    }
}
