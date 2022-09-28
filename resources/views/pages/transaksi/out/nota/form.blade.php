@extends('template.master')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
            <div class="d-flex align-items-center">
                <div class="font-35 text-danger">
                    <i class="bx bx-message-alt-x"></i>
                </div>
                <div class="ms-3">
                    {{-- <h6 class="mb-0 text-danger">Error</h6> --}}
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {!! show_alert() !!}

    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body p-5">

            <div class="page-breadcrumb d-sm-flex align-items-center">
                <div class="card-title d-flex align-items-center">
                    <i class="bx bx-box me-1 font-22 text-primary"></i>
                    <h5 class="mb-0 text-primary">{{ $form_title }}</h5>
                </div>
                <div class="ms-auto">
                    <a href="{{ route($main_route . 'list') }}" class="btn btn-primary w-100">
                        <i class="bx bx-left-arrow-circle"></i>Kembali
                    </a>
                </div>
            </div>

            <hr>
            <form class="row g-3" method="POST" action="{{ url('transaksi/out/nota/save') }}">
                @csrf

                <input type="hidden" name="nota_id" value="{{ isset($nota) ? encode($nota->id) : '' }}">

                <div class="col-md-6">
                    <label for="tgl_nota" class="form-label">Tanggal Nota</label>
                    <input type="text" class="form-control date-picker @error('tgl_nota') is-invalid @enderror"
                        id="tgl_nota" name="tgl_nota" autocomplete="off" readonly required
                        value="{{ old('tgl_nota') ?? (isset($nota) ? date('d/m/Y', strtotime($nota->tgl_nota)) : date('d/m/Y')) }}">
                    @error('tgl_nota')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="no_nota" class="form-label">Nomor Nota</label>
                    <input type="text" class="form-control" id="no_nota" name="no_nota"
                        value="{{ old('no_nota') ?? (isset($nota) ? $nota->no_nota : '') }}">
                </div>

                <div class="col-md-6">
                    <label for="rekanan" class="form-label">Rekanan</label>
                    <select class="select2 @error('rekanan') is-invalid @enderror" name="rekanan" id="rekanan">
                        <option value="" disabled selected>Pilih Rekanan</option>
                        @foreach ($rekanan as $item)
                            <option
                                {{ (old('rekanan') != null ? (decode(old('rekanan')) == $item->id ? 'selected' : '') : isset($nota) && $nota->rekanan_id == $item->id) ? 'selected' : '' }}
                                value="{{ encode($item->id) }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    @error('rekanan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="harga_total" class="form-label">Harga Total</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control @error('harga_total') is-invalid @enderror"
                            id="harga_total" name="harga_total" min="1" max="999999999"
                            onblur="checkNumber(this, event);" onkeypress="return numberInput(event);"
                            onkeyup="checkNumber(this, event); changeRupe(this); "
                            value="{{ old('harga_total') ?? (isset($nota) ? nominal($nota->harga_total) : '') }}">
                        <span class="input-group-text">,-</span>
                        @error('harga_total')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="jenis_bayar" class="form-label">Transaksi</label>
                    <div class="row" id="jenis_bayar">
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="jenis_bayar" id="tunai" value="tunai"
                                autocomplete="off"
                                {{ old('jenis_bayar') != null ? (old('jenis_bayar') == 'tunai' ? 'checked' : '') : (isset($nota) ? ($nota->jenis_bayar == 'tunai' ? 'checked' : '') : 'checked') }}>
                            <label class="btn btn-outline-primary w-100" for="tunai">Tunai</label>
                        </div>
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="jenis_bayar" id="bank" value="bank"
                                autocomplete="off"
                                {{ old('jenis_bayar') != null ? (old('jenis_bayar') == 'bank' ? 'checked' : '') : (isset($nota) ? ($nota->jenis_bayar == 'bank' ? 'checked' : '') : '') }}>
                            <label class="btn btn-outline-info w-100" for="bank">Bank</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="status_bayar" class="form-label">Pembayaran</label>
                    <div class="row" id="status_bayar">
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="status_bayar" id="lunas" value="1"
                                autocomplete="off"
                                {{ old('status_bayar') != null ? (old('status_bayar') == '1' ? 'checked' : '') : (isset($nota) ? ($nota->status_bayar == '1' ? 'checked' : '') : 'checked') }}>
                            <label class="btn btn-outline-success w-100" for="lunas">Lunas</label>
                        </div>
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="status_bayar" id="belum_bayar" value="0"
                                autocomplete="off"
                                {{ old('status_bayar') != null ? (old('status_bayar') == '0' ? 'checked' : '') : (isset($nota) ? ($nota->status_bayar == '0' ? 'checked' : '') : '') }}>
                            <label class="btn btn-outline-danger w-100" for="belum_bayar">Belum Bayar</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="ket_nota" class="form-label">Keterangan</label>
                    <textarea class="form-control" name="ket_nota" id="ket_nota" rows="1" style="height: 12px">{{ old('ket_nota') ?? (isset($nota) ? $nota->ket_nota : '') }}</textarea>
                </div>

                <div class="col-md-12">
                    <hr>
                </div>

                <div class="offset-md-6 col-md-6">
                    <div class="row">
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
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('css_plugin')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    {{-- Datepicker --}}
    <link href="{{ asset_ext('bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset_ext('bootstrap-datepicker/css/style-datepicker.css') }}" rel="stylesheet" />
    {{-- Select 2 --}}
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
@endpush

@push('css_style')
    <style>
        .datepicker {
            /* padding: 6px; */
            margin-top: 66px;
        }
    </style>
@endpush

@push('js_plugin')
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset_ext('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset_js('number_input.js') }}"></script>
    <script src="{{ asset_js('datatable_option.js') }}"></script>
    <script src="{{ asset_js('datepicker_conf.js') }}"></script>
@endpush

@push('js_script')
    {{-- Datatable config --}}
    <script>
        var dataTable;
        $(document).ready(function() {
            datepickerShow('date-picker', '{{ $year }}');
        });
    </script>

    {{-- Select2 config --}}
    <script>
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            minimumInputLength: 0,
            allowClear: true,
            placeholder: 'Pilih rekanan',
        });
    </script>

    {{-- <script>
        var limit_data_show = 10;
        $('.select2').select2({
            ajax: {
                url: "{{ route($main_route . 'rekanan') }}",
                dataType: 'json',
                type: "GET",
                // quietMillis: 50,
                delay: 150,
                data: function(params) {
                    return {
                        limit: limit_data_show,
                        search: params.term || '',
                        page: params.page || 1
                    }
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    if (data.response) {
                        return {
                            // results: $.map(data.result, function(item) { //jika tidak menggunakan template
                            //     return {
                            //         text: item.nama_produk,
                            //         id: item.id
                            //     }
                            // }),

                            //data yang akan ditangkap oleh templateResult & templateSelection
                            results: data.result,
                            pagination: {
                                more: (params.page * limit_data_show) < data.count
                            }
                        };
                    } else {
                        return {
                            results: {
                                text: data.result,
                            }
                        }
                    }
                },
                cache: true
            },
            theme: 'bootstrap4',
            width: '100%',
            minimumInputLength: 0,
            allowClear: true,
            placeholder: 'Pilih rekanan',
            templateResult: function(item) { //format tampilan saat list pilihan terbuka
                if (item.loading) {
                    return item.text;
                }
                var res = $(
                    '<div class="row">' +
                    '<div class="col-md-10">' + item.nama + '</div>' +
                    '</div>'
                );
                return res;
            },
            templateSelection: function(item) { //format tampilan saat list dipilih
                if (!item.id) {
                    return item.text;
                }
                return item.nama;
            }
        }).on('select2:select', function(evt) {
            // var data = $(".select2 option:selected").val();
        });
    </script> --}}
@endpush
