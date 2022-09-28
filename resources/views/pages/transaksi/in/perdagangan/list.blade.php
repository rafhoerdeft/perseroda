@extends('template.master')

@section('column-table')
    <tr>
        <th>No</th>
        {{-- Active checkbox according to role --}}
        @if ($is_role)
            <th>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" onchange="onCheckChange(this)" name="select_row_all"
                        id="check_all" value="0">
                </div>
            </th>
        @endif
        <th>Aksi</th>
        <th>No. Order</th>
        <th>Nama Kustomer</th>
        <th>Tanggal Order</th>
        {{-- <th>Status Order</th> --}}
        {{-- <th>Jenis Order</th> --}}
        <th>Status Bayar</th>
        <th>Jenis Bayar</th>
        <th>Total (Rp)</th>
    </tr>
@endsection

@section('content')
    {!! show_alert() !!}
    <div class="card">
        <div class="card-body">
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
                                <a href="{{ route($main_route . 'add') }}" class="btn btn-primary w-100">
                                    <i class="bx bx-list-plus"></i>Input Order
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <hr />

            <form class="row" method="GET">
                <div class="col-md-3 mb-1">
                    <select class="select2" name="status_bayar" id="status_bayar">
                        <option value="null" selected>Semua Status Bayar</option>
                        <option value="0" {{ $status_bayar_select == '0' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="1" {{ $status_bayar_select == '1' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-3 mb-1">
                    <select class="select2" name="jenis_bayar" id="jenis_bayar">
                        <option value="null" selected>Semua Jenis Bayar</option>
                        <option value="tunai" {{ $jenis_bayar_select == 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="bank" {{ $jenis_bayar_select == 'bank' ? 'selected' : '' }}>Bank</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100"> <i class="fa-regular fa-eye"></i> Tampil</button>
                </div>
            </form>

            <hr />

            <div class="table-responsive">
                <table id="list_data" class="table table-striped table-bordered table-hover" style="width:100%">
                    <thead class="text-center">
                        @yield('column-table')
                    </thead>

                    {{-- <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($list_order as $row)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                @if ($is_role)
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" onchange="onCheckChange(this)"
                                                name="select_row[]" id="select_row_{{ $row->id }}"
                                                value="{{ $row->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" onclick="showDetail(this)"
                                            data-nama_produk="{{ $row->rincian_order->implode('tarif.produk.nama_produk', ';') }}"
                                            data-jml_order="{{ $row->rincian_order->implode('jml_order', ';') }}"
                                            data-harga="{{ $row->rincian_order->implode('harga', ';') }}"
                                            data-satuan="{{ $row->rincian_order->implode('tarif.produk.satuan_produk', ';') }}"
                                            data-biaya_tambahan="{{ $row->rincian_order->implode('biaya_tambahan', ';') }}"
                                            class="btn btn-sm btn-primary" title="Rincian Order">
                                            <i class="lni lni-list me-0 font-sm"></i>
                                        </button>

                                        <a href="{{ route($main_route . 'edit', ['id' => encode($row->id)]) }}"
                                            class="btn btn-info btn-sm" title="Update Data">
                                            <i class="lni lni-pencil-alt me-0 text-white font-sm"></i>
                                        </a>
                                        <button type="button" onclick="deleteData(this)" data-id="{{ encode($row->id) }}"
                                            data-link="{{ url('transaksi/in/perdagangan/delete') }}" class="btn btn-sm btn-danger"
                                            title="Hapus Data">
                                            <i class="lni lni-trash me-0 font-sm"></i>
                                        </button>
                                    </td>
                                @endif
                                <td align="center">{{ $row->no_order }}</td>
                                <td>{{ $row->nama_klien }}</td>
                                <td align="center">{{ date('d/m/Y H:i', strtotime($row->tgl_order)) }}</td>
                                <td align="center">
                                    <span
                                        class="badge rounded-pill bg-{{ $row->status_bayar == 0 ? 'danger' : 'success' }} w-75">
                                        {{ $status_bayar[$row->status_bayar] }}</span>
                                </td>
                                <td align="center"><span
                                        class="badge bg-{{ $row->jenis_bayar == 'bank' ? 'info' : 'primary' }} w-75">{{ text_uc($row->jenis_bayar) }}</span>
                                </td>
                                <td align="right">
                                    {{ nominal(
                                        $row->rincian_order->sum(function ($item) {
                                            return $item->jml_order * $item->tarif->harga + $item->biaya_tambahan;
                                        }),
                                    ) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody> --}}

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

    {{-- Select 2 --}}
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
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
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset_js('datatable_option.js') }}"></script>
    <script src="{{ asset_js('number_input.js') }}"></script>
@endpush

@push('js_script')
    {{-- Config DataTable Serverside with Export DataTable --}}
    <script>
        var num_cols = 8;
        var remove_cols = 1;
        var tgl_awal = "{{ date('01/01/Y') }}";
        var tgl_akhir = "{{ date('d/m/Y') }}";
        var info = "List Order";
        var msg = tgl_awal + " - " + tgl_akhir;
        var url = "{{ $link_datatable }}";
        var columns = [{
                data: 'DT_RowIndex',
                class: "text-center",
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                class: "text-center",
                orderable: false,
                searchable: false
            },
            {
                data: 'no_order',
                class: "text-center"
            },
            {
                data: 'nama_klien',
            },
            {
                data: 'tgl_order',
                class: "text-center"
            },
            {
                data: 'status_bayar',
                class: "text-center"
            },
            {
                data: 'jenis_bayar',
                class: "text-center"
            },
            {
                data: 'total',
                class: "text-end"
            }
        ];

        // Active checkbox according to role
        if ("{{ $is_role }}") {
            var check_box = {
                data: 'check_all',
                class: "text-center",
                orderable: false,
                searchable: false
            };
            columns.splice(1, 0, check_box); // insert array in specific position At Index 1

            num_cols = 9;
            remove_cols = 2;
            createDataTableServerSide('list_data', url, columns, info, msg);
        } else {
            createDataTableServerSide('list_data', url, columns, info, msg, true, num_cols, remove_cols);
        }
    </script>

    {{-- Select2 config --}}
    <script>
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            // placeholder: $(this).data('placeholder'),
            // allowClear: Boolean($(this).data('allow-clear')),
        });
    </script>
@endpush

@if ($is_role)
    @push('css_plugin')
        <link href="{{ asset_ext('sweetalert/css/sweetalert.css') }}" rel="stylesheet" />
    @endpush

    @push('js_plugin')
        <script src="{{ asset_js('delete_data.js') }}"></script>
        <script src="{{ asset_js('confirm_dialog.js') }}"></script>
        <script src="{{ asset_ext('sweetalert/js/sweetalert.min.js') }}"></script>
    @endpush

    @push('js_script')
        <script>
            // Highlight ROW on click tr 
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

            // Check or uncheck 
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

            // Add value on input delete when checked 
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
        </script>

        <script>
            // Automatic find row checked when change page 
            function checkChangePage() {
                var select_id = $('#delete_all').val();
                var arr = select_id.split(";");

                arr.forEach(function(val, i) {
                    var checkbox = $('.form-check input:checkbox[value="' + val + '"]');
                    checkbox.prop('checked', true);
                    checkbox.closest('tr').addClass('row_check');
                });
            }
        </script>

        {{-- Delete All function --}}
        <script>
            function deleteAll() {
                var dataid = $('#delete_all').val();
                var link = "{{ route($main_route . 'delete.all') }}";
                var table = "order";
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
            function checkChangePage() {
                return true;
            }
        </script>
    @endpush
@endif

@include('pages.transaksi.in.perdagangan.modal_detail')
