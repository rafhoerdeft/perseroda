@extends('template.master')

{{-- @section('button-top')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('config.list') }}" class="btn btn-primary w-100">
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
                            </h5>
                        </div>

                    </div>

                    {!! show_alert() !!}

                    <hr>
                    <form class="row g-3" method="POST" action="{{ url('config/logo') }}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="config_id" value="{{ isset($config) ? encode($config->id) : '' }}">
                        <input type="hidden" name="logo_old"
                            value="{{ isset($config) && $config->logo != null ? encode($config->logo) : '' }}">

                        <div class="col-md-12">
                            <label class="form-label" for="logo">Logo Aplikasi</label>
                            <input type="file" data-validation-required-message="Upload file logo" name="logo"
                                class="dropify @error('logo') is-invalid @enderror" data-height="200" accept="image/*"
                                data-max-file-size="100K" data-min-width="100" data-min-height="100"
                                data-allowed-file-extensions="jpg png jpeg" style="height: unset"
                                data-default-file="{{ show_image($config->logo) }}" />
                        </div>

                        <div class="col-md-12">
                            <label for="app_name" class="form-label">Nama Aplikasi</label>
                            <input type="text" class="form-control @error('app_name') is-invalid @enderror"
                                id="app_name" name="app_name"
                                value="{{ old('app_name') ?? (isset($config) ? $config->app_name : '') }}">
                            @error('app_name')
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
    <link rel="stylesheet" href="{{ asset_ext('dropify/css/dropify.min.css') }}">
@endpush

@push('js_plugin')
    <script src="{{ asset_ext('dropify/js/dropify.min.js') }}"></script>
@endpush

@push('js_script')
    <script type="text/javascript">
        var drEvent = $('.dropify').dropify({
            messages: {
                default: '<center>Upload file foto (<b>png/jpeg/jpg</b>).</center>',
                error: '<center>Maksimal ukuran file 100 KB.</center>',
            },
            error: {
                fileSize: '<center>Maksimal ukuran file 100 KB.</center>',
            }
        });
    </script>

    @error('logo')
        <script>
            $(document).ready(function() {
                var error_msg = "<li> {{ $message }} </li>";
                $('.dropify.is-invalid').closest('.dropify-wrapper').addClass('has-error');
                $('.dropify.is-invalid').closest('.dropify-wrapper').find('.dropify-errors-container ul').html(
                    error_msg);
            });
        </script>
    @enderror
@endpush
