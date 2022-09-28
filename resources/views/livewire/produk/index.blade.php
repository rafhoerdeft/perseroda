<div wire:init="loading">
    <div wire:loading>
        Loading...
    </div>
    <div style="height: 405px;" class="overflow-auto">
        {{ ucwords($name) }}
        <ul>
            @foreach ($produk_all as $item)
                <li><i class="bx bx-box"></i> {{ $item->nama_produk }} => Rp. {{ nominal($item->tarif->harga) }},-</li>
            @endforeach
        </ul>
    </div>
    {{-- {{ $produk->links() }} --}}

</div>
