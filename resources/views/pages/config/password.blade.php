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
                    <form class="row g-3" method="POST" action="{{ url('config/password') }}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="config_id" value="{{ isset($config) ? encode($config->id) : '' }}">

                        <div class="col-md-12">
                            <label for="default_password" class="form-label">Password</label>
                            <input type="text" class="form-control @error('default_password') is-invalid @enderror"
                                id="default_password" name="default_password"
                                value="{{ old('default_password') ?? (isset($config) ? $config->default_password : '') }}">
                            @error('default_password')
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
