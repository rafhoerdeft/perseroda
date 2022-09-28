@extends('template.master')

{{-- @section('button-top')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('rekanan.list') }}" class="btn btn-primary w-100">
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
                            <h5 class="mb-0 text-primary">{{ $form_title }}
                                {{ isset($rekanan) ? ' - ' . $rekanan->kode_rekanan : '' }}</h5>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('rekanan.list') }}" class="btn btn-primary w-100">
                                <i class="bx bx-left-arrow-circle"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <hr>
                    <form class="row g-3" method="POST" action="{{ url('rekanan/save') }}">
                        @csrf

                        <input type="hidden" name="rekanan_id" value="{{ isset($rekanan) ? encode($rekanan->id) : '' }}">

                        <div class="col-md-12">
                            <label for="nama" class="form-label">Nama Rekanan</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                name="nama" value="{{ old('nama') ?? (isset($rekanan) ? $rekanan->nama : '') }}">
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="alamat" class="form-label">Alamat Rekanan</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2">{{ old('alamat') ?? (isset($rekanan) ? $rekanan->alamat : '') }}</textarea>
                            @error('alamat')
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
