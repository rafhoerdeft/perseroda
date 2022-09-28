@extends('template.master')

{{-- @section('button-top')
    <div class="row">
        <div class="col-md-6">
            <input type="hidden" name="delete_all" id="delete_all">
            <button id="btn_delete" class="btn btn-danger position-relative me-4 w-100 mb-1" type="button"
                onclick="deleteAll()" disabled>
                <i class="bx bx-trash"></i>Hapus Data
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">0</span>
            </button>
        </div>
        <div class="col-md-6">
            <a href="{{ route('produk.add') }}" class="btn btn-primary w-100">
                <i class="bx bx-list-plus"></i>Tambah Data
            </a>
        </div>
    </div>
@endsection --}}

@section('column-table')
    <tr>
        <th>No</th>
        @if ($is_role)
            <th>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" onchange="onCheckChange(this)" name="plh_brg_all"
                        id="check_all" value="0">
                </div>
            </th>
            <th>Aksi</th>
        @endif
        <th>Kode Produk</th>
        <th>Nama Produk</th>
        <th>Satuan</th>
        <th>Harga</th>
        <th>Stok Produk</th>
        <th>Stok Minimal</th>
    </tr>
@endsection

@section('content')
    {{-- <h6 class="mb-0 text-uppercase">List Data</h6>
    <hr /> --}}
    {!! show_alert() !!}
    <div class="card">
        <div class="card-body">
            {{-- <h5 class="card-title">List Data</h5> --}}
            <div class="page-breadcrumb d-sm-flex align-items-center">
                <h5 class="card-title">List Data</h5>
                @if ($is_role)
                    <div class="ms-auto">
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="hidden" name="delete_all" id="delete_all">
                                <button id="btn_delete" class="btn btn-danger position-relative me-4 w-100 mb-1"
                                    type="button" onclick="deleteAll()" disabled>
                                    <i class="bx bx-trash"></i>Hapus Data
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">0</span>
                                </button>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('produk.add') }}" class="btn btn-primary w-100">
                                    <i class="bx bx-list-plus"></i>Tambah Data
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <hr />

            <div class="table-responsive">
                <table id="list_data" class="table table-striped table-bordered table-hover" style="width:100%">
                    <thead class="text-center">
                        @yield('column-table')
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($list_produk as $row)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                @if ($is_role)
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" onchange="onCheckChange(this)"
                                                name="plh_brg[]" id="plh_brg_{{ $row->id }}"
                                                value="{{ $row->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('produk.edit', ['id' => encode($row->id)]) }}"
                                            class="btn btn-info btn-sm" title="Update Data">
                                            <i class="lni lni-pencil-alt me-0 text-white font-sm"></i>
                                        </a>
                                        <button type="button" onclick="deleteData(this)" data-id="{{ encode($row->id) }}"
                                            data-link="{{ url('produk/delete') }}" class="btn btn-sm btn-danger"
                                            title="Hapus Data">
                                            <i class="lni lni-trash me-0 font-sm"></i>
                                        </button>
                                    </td>
                                @endif
                                <td align="center">{{ $row->kode_produk }}</td>
                                <td>{{ $row->nama_produk }}</td>
                                <td align="center">{{ $row->satuan_produk }}</td>
                                <td align="right">{{ isset($row->tarif->harga) ? nominal($row->tarif->harga) : '' }}
                                </td>
                                <td align="right">{{ $row->stok_produk }}</td>
                                <td align="right">{{ $row->stok_minimal }}</td>
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
@endsection

@push('css_plugin')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

@push('css_style')
    <style>
        .form-check {
            padding-left: 2rem;
            margin: 0px;
        }

        .form-check-input {
            width: 19px;
            height: 19px;
        }
    </style>
@endpush

@push('js_plugin')
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset_js . 'datatable_option.js' }}"></script>
@endpush

@push('js_script')
    <script>
        var tgl_awal = "{{ date('d-m-Y') }}";
        var info = "Daftar Stok Produk";
        var msg = "Tanggal " + tgl_awal;

        // $('#list_data').on('draw.dt', function() {
        //     cekChangePage();
        // });
    </script>
@endpush

@if ($is_role)
    @push('css_plugin')
        <link href="{{ asset_ext . 'sweetalert/css/sweetalert.css' }}" rel="stylesheet" />
    @endpush

    @push('js_plugin')
        <script src="{{ asset_ext . 'sweetalert/js/sweetalert.min.js' }}"></script>
        <script src="{{ asset_js . 'delete_data.js' }}"></script>
    @endpush

    @push('js_script')
        <script>
            createDataTableExport('list_data', info, msg, 9);
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
                var link = "{{ url('all/delete') }}";
                var table = "produk";
                var data = {
                    dataid: dataid,
                    link: link,
                    table: table,
                    soft: true,
                };

                deleteAllData(data);
            }
        </script>
    @endpush
@else
    @push('js_script')
        <script>
            createDataTableExport('list_data', info, msg, 7, [0], 0);
        </script>

        <script>
            function checkChangePage() {
                return true;
            }
        </script>
    @endpush
@endif
