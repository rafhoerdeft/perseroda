@extends('template.master')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body p-5">

                    <div class="page-breadcrumb d-sm-flex align-items-center">
                        <div class="card-title d-flex align-items-center">
                            <i class="bx bx-box me-1 font-22 text-primary"></i>
                            <h5 class="mb-0 text-primary">{{ $form_title }}
                            </h5>
                        </div>

                    </div>

                    {!! show_alert() !!}

                    <hr>
                    <form class="row g-3" method="POST" action="{{ url('config/periode') }}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="config_id" value="{{ isset($config) ? encode($config->id) : '' }}">

                        <div class="col-md-12">
                            <label for="active_period" class="form-label">Periode</label>
                            <select class="@error('active_period') is-invalid @enderror" name="active_period"
                                id="active_period">
                                @foreach ($thn_periode as $item)
                                    <option value="{{ $item }}"
                                        {{ old('active_period') == $item ? 'selected' : ($item == $config->active_period ? 'selected' : '') }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            @error('active_period')
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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css_plugin')
    {{-- Select 2 --}}
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
@endpush

@push('js_plugin')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
@endpush

@push('js_script')
    <script>
        $('#active_period').select2({
            theme: 'bootstrap4',
            width: '100%',
            minimumInputLength: 0,
            allowClear: true,
            placeholder: 'Pilih Periode',
        });
    </script>
@endpush
