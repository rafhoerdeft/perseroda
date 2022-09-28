<div>
    {{ ucwords($name) }}
    <ul>
        @foreach ($tarif as $item)
            <li><i class="bx bx-box"></i> {{ $item->produk->nama_produk }} : Rp. {{ $item->harga }} ,-</li>
        @endforeach
    </ul>
</div>
