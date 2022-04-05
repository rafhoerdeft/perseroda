@extends('template.master')

{{-- @section('button-top')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('barang.list') }}" class="btn btn-primary w-100">
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
                            <a href="{{ route('barang.list') }}" class="btn btn-primary w-100">
                                <i class="bx bx-left-arrow-circle"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <hr>
                    <form class="row g-3" method="POST" action="{{ url('barang/save') }}">
                        @csrf

                        <input type="hidden" name="barang_id" value="{{ isset($barang) ? encode($barang->id) : '' }}">

                        <div class="col-md-12">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                id="nama_barang" name="nama_barang"
                                value="{{ old('nama_barang') ?? (isset($barang) ? $barang->nama_barang : '') }}">
                            @error('nama_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="satuan_barang" class="form-label">Satuan Barang</label>
                            <input type="text" class="form-control @error('satuan_barang') is-invalid @enderror"
                                id="satuan_barang" name="satuan_barang"
                                value="{{ old('satuan_barang') ?? (isset($barang) ? $barang->satuan_barang : '') }}">
                            @error('satuan_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="harga" class="form-label">Harga Barang</label>
                            <input type="text" class="form-control @error('harga') is-invalid @enderror" id="harga"
                                name="harga" onblur="checkNumber(this, event)" onkeypress="return numberInput(event);"
                                onkeyup="checkNumber(this, event); changeRupe(this);" min="1" max="999999999"
                                value="{{ old('harga') ?? (isset($barang) ? nominal($barang->tarif->harga) : '') }}">
                            @error('harga')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="stok_barang" class="form-label">Stok Barang</label>
                            <input type="number" class="form-control @error('stok_barang') is-invalid @enderror"
                                id="stok_barang" name="stok_barang" min="1" max="10000" onblur="checkNumber(this, event)"
                                onkeypress="return numberInput(event);" onkeyup="checkNumber(this, event)"
                                value="{{ old('stok_barang') ?? (isset($barang) ? $barang->stok_barang : '') }}">
                            @error('stok_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="stok_minimal" class="form-label">Stok Minimal</label>
                            <input type="number" class="form-control @error('stok_minimal') is-invalid @enderror"
                                id="stok_minimal" name="stok_minimal" min="1" max="10000" onblur="checkNumber(this, event)"
                                onkeypress="return numberInput(event);" onkeyup="checkNumber(this, event)"
                                value="{{ old('stok_minimal') ?? (isset($barang) ? $barang->stok_minimal : '') }}">
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
