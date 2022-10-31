@extends('template.master')

@push('css_plugin')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

@push('css_style')
    <style>
        .form-check {
            /* padding-left: 2rem; */
            margin: 0px;
        }

        .form-check-input {
            width: 19px;
            height: 19px;
            float: none !important;
        }
    </style>
@endpush

@push('js_plugin')
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset_js('datatable_option.js') }}"></script>
    <script src="{{ asset_js('number_input.js') }}"></script>
@endpush


@if ($is_role)
    @section('role-column')
        <th>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" onchange="onCheckChange(this)" name="plh_brg_all" id="check_all"
                    value="0">
            </div>
        </th>
        <th>Aksi</th>
    @endsection

    @section('role-button')
        <div class="ms-auto">
            <div class="row">
                <div class="col-sm-12">
                    <input type="hidden" name="delete_all" id="delete_all">
                    <button id="btn_delete" class="btn btn-danger position-relative me-4 w-100" type="button"
                        onclick="deleteAll()" disabled>
                        <i class="bx bx-trash"></i>Hapus Data
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">0</span>
                    </button>
                </div>
                {{-- <div class="col-sm-6">
                <a href="{{ route('produk.add') }}" class="btn btn-primary w-100">
                    <i class="bx bx-list-plus"></i>Tambah Data
                </a>
            </div> --}}
            </div>
        </div>
    @endsection

    @section('role-form')
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="page-breadcrumb d-sm-flex align-items-center">
                        <h5 class="card-title">Form Input</h5>
                    </div>
                    <hr>
                    <form class="row g-3" method="POST" action="{{ url('transaksi/out/nota/rincian/save') }}">
                        @csrf

                        <input type="hidden" name="rincian_nota_id" id="rincian_nota_id" value="">
                        <input type="hidden" name="nota_id" value="{{ encode($nota_id) }}">

                        <div class="col-md-12">
                            <label for="produk" class="form-label">Produk</label>
                            <select class="@error('produk') is-invalid @enderror" name="produk" id="produk">
                            </select>
                            @error('produk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="harga_produk" class="form-label">Harga Produk</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control @error('harga_produk') is-invalid @enderror"
                                    id="harga_produk" name="harga_produk" min="1" max="999999999"
                                    onblur="checkNumber(this, event);" onkeypress="return numberInput(event);"
                                    onkeyup="checkNumber(this, event); changeRupe(this); "
                                    value="{{ old('harga_produk') ?? (isset($nota) ? nominal($nota->harga_produk) : '') }}">
                                <span class="input-group-text">,-</span>
                                @error('harga_produk')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="jml_produk" class="form-label">Jumlah Produk</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('jml_produk') is-invalid @enderror"
                                    id="jml_produk" name="jml_produk" min="1" max="999999999"
                                    onblur="checkNumber(this, event);" onkeypress="return numberInput(event);"
                                    onkeyup="checkNumber(this, event); changeRupe(this); "
                                    value="{{ old('jml_produk') ?? (isset($nota) ? nominal($nota->jml_produk) : '') }}">
                                @error('jml_produk')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mt-0">
                            <hr>
                        </div>

                        <div class="col-md-12 mt-0">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-warning w-100" onclick="resetForm(this)">
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
        </div>
    @endsection

    @push('css_plugin')
        <link href="{{ asset_ext('sweetalert/css/sweetalert.css') }}" rel="stylesheet" />
        {{-- Select 2 --}}
        <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
    @endpush

    @push('js_plugin')
        <script src="{{ asset_ext('sweetalert/js/sweetalert.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset_js('delete_data.js') }}"></script>
    @endpush

    @push('js_script')
        {{-- Select 2 Config  --}}
        <script>
            var limit_data_show = 10;
            $('#produk').select2({
                ajax: {
                    url: "{{ route($main_route . 'produk') }}",
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
                placeholder: 'Pilih Produk',
                templateResult: function(item) { //format tampilan saat list pilihan terbuka
                    if (item.loading) {
                        return item.text;
                    }
                    var res = $(
                        '<div class="row g-0 font-sm">' +
                        '<div class="col-md-10">' + item.nama_produk + '</div>' +
                        '<div class="col-md-2 fw-bold">' + item.kode_produk + '</div>' +
                        '</div>'
                    );
                    return res;
                },
                // templateSelection: function(item) { //format tampilan saat list dipilih
                //     if (!item.id) {
                //         return item.text;
                //     }
                //     return item.nama_produk + ' - ' + item.kode_produk;
                // }
            }).on('select2:select', function(evt) {
                // var data = $(".select2 option:selected").val();
            });
        </script>

        <script>
            function resetForm(data) {
                var form = $(data).closest('form');
                $(form).trigger('reset');
                $("#produk").val('').trigger('change');
            }

            function editData(data) {
                var rincian_nota_id = $(data).data().id;
                var produk_id = $(data).data().produk;
                var produk_nama = $(data).data().produk_nama;
                var produk_kode = $(data).data().produk_kode;
                var harga = $(data).data().harga;
                var jml = $(data).data().jml;

                $('#rincian_nota_id').val(rincian_nota_id);
                $('#harga_produk').val(harga);
                $('#jml_produk').val(jml);

                var $selectedOption = $("<option selected></option>").val(produk_id).text(produk_nama + ' - ' +
                    produk_kode);

                $("#produk").append($selectedOption).trigger('change');
            }
        </script>
    @endpush

    @push('js_script')
        <script>
            createDataTable('list_data');
        </script>

        <script>
            // $('.form-check-input').on('change', function() {
            function onCheckChange(data) {
                let check_id = $(data).val();

                if ($(data).is(':checked')) {
                    if (check_id == 0) { // Chek all checkbox
                        $('.form-check-input').prop('checked', true);
                        $('.form-check-input').each(function(i, data) {
                            let ids = $(data).val();
                            if (ids != 0) { // not input in data
                                checkInput(data, true);
                            }
                        });
                    } else {
                        checkInput(data, true);
                    }
                } else {
                    if (check_id == 0) {
                        $('.form-check-input').prop('checked', false);
                        $('.form-check-input').each(function(i, data) {
                            let ids = $(data).val();
                            if (ids != 0) { // not input in data
                                checkInput(data, false);
                            }
                        });
                    } else {
                        checkInput(data, false);
                    }
                }
            }
            // });

            function checkInput(data, checked) {
                let id = $(data).val();
                var tr = $(data).closest('tr');
                var select_id = $('#delete_all').val();
                var value_id = '';

                if (checked) {
                    tr.addClass('row_check');
                    if (select_id == '') {
                        value_id = id;
                        $('#btn_delete').attr('disabled', false);
                    } else {
                        var arr = select_id.split(";");
                        if (jQuery.inArray(id, arr) !== -1) { // check ID if available in array
                            value_id = select_id;
                        } else {
                            value_id += select_id + ';' + id;
                        }
                    }
                } else {
                    tr.removeClass('row_check');

                    var arr = select_id.split(";");
                    var result = arr.filter(function(val) {
                        return val != id;
                    });
                    value_id = result.join(';');

                    if (result.length == 0) { // disabled button Delete All if nothing to check
                        $('#btn_delete').attr('disabled', true);
                    }
                }

                $("input[name=delete_all]").val(value_id);

                if (value_id != '' && value_id != null) {
                    var count_select = value_id.split(";").length;
                } else {
                    var count_select = 0;
                }

                $('.page-breadcrumb .badge').html(count_select);
            }

            function cekChangePage() {
                $('.form-check-input').each(function(i, data) {
                    let tr = $(this).closest('tr');
                    let ids = $(data).val();
                    if (ids != 0) { // not input in data
                        // console.log(tr);
                    }
                });
            }

            $(document).ready(function() {
                $('.table').on('click', 'tbody tr', function(e) {
                    var td = $(this).children();
                    var checkbox = td.eq(1).find('input');
                    var checked = checkbox.prop('checked');
                    if (e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'INPUT' && e.target
                        .tagName !== 'BUTTON' && e.target.tagName !== 'A' && e.target.tagName !== 'I') {
                        if (checked) {
                            checkbox.prop('checked', false);
                            checkInput(checkbox, false);
                        } else {
                            checkbox.prop('checked', true);
                            checkInput(checkbox, true);
                        }
                    }
                });
            });
        </script>

        <script>
            function deleteAll() {
                var dataid = $('#delete_all').val();
                var link = "{{ route($main_route . 'delete.all') }}";
                var table = "rincian_nota";
                var data = {
                    dataid: dataid,
                    link: link,
                    table: table,
                    soft: false,
                };

                deleteAllData(data);
            }
        </script>
    @endpush
@else
    @push('js_script')
        <script>
            createDataTable('list_data', [0]);
        </script>

        <script>
            function checkChangePage() {
                return true;
            }
        </script>
    @endpush
@endif


@section('column-table')
    <tr>
        <th>No</th>
        @yield('role-column')
        <th>Kode</th>
        <th>Produk</th>
        <th>Satuan</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Total</th>
        {{-- <th>Keterangan</th> --}}
    </tr>
@endsection

@section('content')
    {!! show_alert() !!}
    <div class="row g-2">
        {{-- Form Input  --}}
        @yield('role-form')

        {{-- List table  --}}
        <div class="{{ $is_role ? 'col-lg-8' : 'col-lg-12' }}">
            <div class="card">
                <div class="card-body">
                    {{-- <h5 class="card-title">List Data</h5> --}}
                    <div class="page-breadcrumb d-sm-flex align-items-center">
                        <h5 class="card-title">List Data</h5>
                        @yield('role-button')
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table id="list_data" class="table table-striped table-bordered table-hover font-sm"
                            style="width:100%">
                            <thead class="text-center">
                                @yield('column-table')
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($list_data as $row)
                                    <tr>
                                        <td align="center">{{ $no++ }}</td>
                                        @if ($is_role)
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        onchange="onCheckChange(this)" name="plh_brg[]"
                                                        id="plh_brg_{{ $row->id }}" value="{{ $row->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <button data-id="{{ encode($row->id) }}"
                                                    data-produk="{{ $row->produk_id }}"
                                                    data-produk_nama="{{ $row->produk->nama_produk }}"
                                                    data-produk_kode="{{ $row->produk->kode_produk }}"
                                                    data-harga="{{ nominal($row->harga_produk) }}"
                                                    data-jml="{{ nominal($row->jml_produk) }}" onclick="editData(this)"
                                                    class="btn btn-info btn-sm" title="Update Data">
                                                    <i class="lni lni-pencil-alt me-0 text-white font-sm"></i>
                                                </button>
                                                <button type="button" onclick="deleteData(this)"
                                                    data-id="{{ encode($row->id) }}"
                                                    data-link="{{ route($main_route . 'delete') }}"
                                                    class="btn btn-sm btn-danger" title="Hapus Data">
                                                    <i class="lni lni-trash me-0 font-sm"></i>
                                                </button>
                                            </td>
                                        @endif
                                        <td align="center">{{ $row->produk->kode_produk }}</td>
                                        <td>{{ $row->produk->nama_produk }}</td>
                                        <td align="center">{{ $row->produk->satuan_produk }}</td>
                                        <td align="right">{{ nominal($row->harga_produk) }}
                                        </td>
                                        <td align="right">{{ nominal($row->jml_produk) }}</td>
                                        <td align="right">{{ nominal($row->jml_produk * $row->harga_produk) }}</td>
                                        {{-- <td>{{ $row->ket_rincian_nota }}</td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="text-center">
                                @yield('column-table')
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
