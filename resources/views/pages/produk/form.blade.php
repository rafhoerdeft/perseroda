@extends('template.master')

{{-- @section('button-top')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('produk.list') }}" class="btn btn-primary w-100">
                <i class="bx bx-left-arrow-circle"></i>Kembali
            </a>
        </div>
    </div>
@endsection --}}

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body p-5">

                    <div class="page-breadcrumb d-sm-flex align-items-center">
                        <div class="card-title d-flex align-items-center">
                            <i class="bx bx-box me-1 font-22 text-primary"></i>
                            <h5 class="mb-0 text-primary">{{ $form_title }}</h5>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('produk.list') }}" class="btn btn-primary w-100">
                                <i class="bx bx-left-arrow-circle"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <hr>
                    <form class="row g-3" method="POST" action="{{ url('produk/save') }}">
                        @csrf

                        <input type="hidden" name="produk_id" value="{{ isset($produk) ? encode($produk->id) : '' }}">

                        <div class="col-md-12">
                            <label for="nama_produk" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control @error('nama_produk') is-invalid @enderror"
                                id="nama_produk" name="nama_produk"
                                value="{{ old('nama_produk') ?? (isset($produk) ? $produk->nama_produk : '') }}">
                            @error('nama_produk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="satuan_produk" class="form-label">Satuan Produk</label>
                            <input type="text" class="form-control @error('satuan_produk') is-invalid @enderror"
                                id="satuan_produk" name="satuan_produk"
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
                                name="harga" onblur="checkNumber(this, event)" onkeypress="return numberInput(event);"
                                onkeyup="checkNumber(this, event); changeRupe(this);" min="1" max="999999999"
                                value="{{ old('harga') ?? (isset($produk) ? nominal($produk->tarif->harga) : '') }}">
                            @error('harga')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="stok_produk" class="form-label">Stok Produk</label>
                            <input type="number" class="form-control @error('stok_produk') is-invalid @enderror"
                                id="stok_produk" name="stok_produk" min="1" max="1000000"
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
                            <input type="number" class="form-control @error('stok_minimal') is-invalid @enderror"
                                id="stok_minimal" name="stok_minimal" min="1" max="100000"
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

                        {{-- <div class="col-12">
                            <label for="inputAddress2" class="form-label">Address 2</label>
                            <textarea class="form-control" id="inputAddress2" placeholder="Address 2..." rows="3"></textarea>
                        </div>
        
                        <div class="col-md-4">
                            <label for="inputState" class="form-label">State</label>
                            <select id="inputState" class="form-select">
                                <option selected>Choose...</option>
                                <option>...</option>
                            </select>
                        </div> --}}


                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js_plugin')
    <script src="{{ asset_js . 'number_input.js' }}"></script>
@endpush
