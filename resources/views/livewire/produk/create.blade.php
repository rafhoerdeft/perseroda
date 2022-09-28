<div>
    <div class="page-breadcrumb d-sm-flex align-items-center">
        <div class="card-title d-flex align-items-center">
            <i class="bx bx-box me-1 font-22 text-primary"></i>
            <h5 class="mb-0 text-primary">{{ $form_title }}
                {{ isset($produk) ? ' - ' . $produk->kode_produk : '' }}</h5>
        </div>
    </div>

    <hr>
    {!! show_alert() !!}

    <form class="row g-3" wire:submit.prevent="save">
        @csrf

        <input type="hidden" name="produk_id" wire:model="produk_id"
            value="{{ isset($produk) ? encode($produk->id) : '' }}">

        <div class="col-md-12">
            <label for="nama_produk" class="form-label">Nama Produk</label>
            <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" id="nama_produk"
                name="nama_produk" wire:model="nama_produk"
                value="{{ old('nama_produk') ?? (isset($produk) ? $produk->nama_produk : '') }}">
            @error('nama_produk')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="satuan_produk" class="form-label">Satuan Produk</label>
            <input type="text" class="form-control @error('satuan_produk') is-invalid @enderror" id="satuan_produk"
                name="satuan_produk" wire:model="satuan_produk"
                value="{{ old('satuan_produk') ?? (isset($produk) ? $produk->satuan_produk : '') }}">
            @error('satuan_produk')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="harga" class="form-label">Harga Produk</label>
            <input type="text" class="form-control @error('harga') is-invalid @enderror" id="harga"
                name="harga" wire:model="harga" onblur="checkNumber(this, event)"
                onkeypress="return numberInput(event);" onkeyup="checkNumber(this, event); changeRupe(this);"
                min="1" max="999999999"
                value="{{ old('harga') ?? (isset($produk) ? nominal($produk->tarif->harga) : '') }}">
            @error('harga')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="stok_produk" class="form-label">Stok Produk</label>
            <input type="number" class="form-control @error('stok_produk') is-invalid @enderror" id="stok_produk"
                name="stok_produk" wire:model="stok_produk" min="1" max="1000000"
                onblur="checkNumber(this, event)" onkeypress="return numberInput(event);"
                onkeyup="checkNumber(this, event)"
                value="{{ old('stok_produk') ?? (isset($produk) ? $produk->stok_produk : '') }}">
            @error('stok_produk')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="stok_minimal" class="form-label">Stok Minimal</label>
            <input type="number" class="form-control @error('stok_minimal') is-invalid @enderror" id="stok_minimal"
                name="stok_minimal" wire:model="stok_minimal" min="1" max="100000"
                onblur="checkNumber(this, event)" onkeypress="return numberInput(event);"
                onkeyup="checkNumber(this, event)"
                value="{{ old('stok_minimal') ?? (isset($produk) ? $produk->stok_minimal : '') }}">
            @error('stok_minimal')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-md-12">
            <hr>
        </div>

        <div class="col-md-6">
            <button type="reset" class="btn btn-warning w-100">
                <i class="bx bx-reset"></i> Reset
            </button>
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-success w-100">
                <i class="bx bx-save"></i> Simpan
            </button>
        </div>
    </form>

    <div wire:loading>
        Process...
    </div>

    @push('js_plugin')
        <script src="{{ asset_js('number_input.js') }}"></script>
    @endpush
</div>
