@extends('template.master')

@section('column-table')
    <tr>
        <th>No</th>
        @if (in_array(session('log'), ['kasir', 'akuntansi']))
            <th>
                <div class="skin skin-check">
                    <input type="checkbox" name="select_row_all" id="check_all" value="0">
                </div>
            </th>
            <th>Aksi</th>
        @endif
        <th>No. Order</th>
        <th>Nama Kustomer</th>
        <th>Waktu Order</th>
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
                <div class="ms-auto">
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="hidden" name="delete_all" id="delete_all">
                            <button id="btn_delete" class="btn btn-danger position-relative me-4 w-100 mb-1" type="button"
                                onclick="deleteAll()" disabled>
                                <i class="bx bx-trash"></i>Hapus Data
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">0</span>
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('order.perdagangan.add') }}" class="btn btn-primary w-100">
                                <i class="bx bx-list-plus"></i>Input Order
                            </a>
                        </div>
                    </div>
                </div>
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
                                @if (in_array(session('log'), ['kasir', 'akuntansi']))
                                    <td>
                                        <div class="skin skin-check">
                                            <input type="checkbox" name="select_row[]" id="select_row_{{ $row->id }}"
                                                value="{{ $row->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('order.perdagangan.edit', ['id' => encode($row->id)]) }}"
                                            class="btn btn-info btn-sm" title="Update Data">
                                            <i class="lni lni-pencil-alt me-0 text-white font-sm"></i>
                                        </a>
                                        <button type="button" onclick="deleteData(this)" data-id="{{ encode($row->id) }}"
                                            data-link="{{ url('order/perdagangan/delete') }}"
                                            class="btn btn-sm btn-danger" title="Hapus Data">
                                            <i class="lni lni-trash me-0 font-sm"></i>
                                        </button>
                                    </td>
                                @endif
                                <td align="center">{{ $row->no_order }}</td>
                                <td>{{ $row->nama_klien }}</td>
                                <td align="center">{{ date('d/m/Y H:i', strtotime($row->tgl_order)) }}</td>
                                <td align="center">{{ $status_bayar[$row->status_bayar] }}</td>
                                <td align="center">{{ $row->jenis_bayar }}</td>
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

@push('modal')
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rincian Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead class="text-center">
                            <th>No.</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th>Jml. Order</th>
                            <th>Biaya Tambahan</th>
                            <th>Total (Rp)</th>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <th colspan="6">Total Bayar</th>
                            <th id="total_bayar"></th>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('css_plugin')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    {{-- Icheck --}}
    <link href="{{ asset_ext . 'icheck/css/icheck.css' }}" rel="stylesheet" />
    <link href="{{ asset_ext . 'icheck/css/custom.css' }}" rel="stylesheet" />
    {{-- Sweet Alert --}}
    <link href="{{ asset_ext . 'sweetalert/css/sweetalert.css' }}" rel="stylesheet" />
    {{-- Select 2 --}}
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
@endpush

@push('js_plugin')
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset_ext . 'icheck/js/icheck.min.js' }}"></script>
    <script src="{{ asset_ext . 'sweetalert/js/sweetalert.min.js' }}"></script>
    <script src="{{ asset_js . 'datatable_option.js' }}"></script>
    <script src="{{ asset_js . 'delete_data.js' }}"></script>
    <script src="{{ asset_js . 'number_input.js' }}"></script>
@endpush

@push('js_script')
    <script>
        function showDetail(data) {
            var nama_barang = $(data).data('nama_barang').split(';');
            var satuan = $(data).data('satuan').split(';');
            var harga = $(data).data('harga').toString().split(';');
            var jml_order = $(data).data('jml_order').toString().split(';');
            var biaya_tambahan = $(data).data('biaya_tambahan').toString().split(';');

            var row = '';
            var tot_bayar = 0;
            for (let i = 0; i < nama_barang.length; i++) {
                let tot = (parseInt(jml_order[i]) * parseInt(harga[i])) + parseInt(biaya_tambahan[i]);
                row += '<tr>' +
                    '<td align="center">' + (i + 1) + '</td>' +
                    '<td>' + nama_barang[i] + '</td>' +
                    '<td align="center">' + satuan[i] + '</td>' +
                    '<td align="right">' + formatRupiah(harga[i]) + '</td>' +
                    '<td align="right">' + formatRupiah(jml_order[i]) + '</td>' +
                    '<td align="right">' + formatRupiah(biaya_tambahan[i]) + '</td>' +
                    '<td align="right">' + formatRupiah(tot.toString()) + '</td>' +
                    '</tr>';
                tot_bayar += parseInt(tot);
            }

            $('#detailModal table tbody').html(row);
            $('#detailModal #total_bayar').html(formatRupiah(tot_bayar.toString(), 'Rp. '));
            $('#detailModal #total_bayar').addClass('text-end');

            $('#detailModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#detailModal').modal('show');
        }
    </script>

    {{-- Config DataTable Serverside with Export DataTable --}}
    <script>
        var tgl_awal = "{{ date('d-m-Y') }}";
        var info = "List Order";
        var msg = "Sampai Tanggal " + tgl_awal;
        var url = "{{ url('order/perdagangan/data/' . $status_bayar_select . '/' . $jenis_bayar_select) }}";
        var columns = [{
                data: 'DT_RowIndex',
                class: "text-center",
                orderable: false,
                searchable: false
            },
            {
                data: 'check_all',
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

        createDataTableServerSide('list_data', url, columns, info, msg, 9);
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

    {{-- Select ROW to Highlight use ICheck --}}
    <script>
        function activeIcheck() {
            $('.skin-check input').on('ifChecked ifUnchecked', function(event) {
                selectRow(this, event.type);
            }).iCheck({
                checkboxClass: 'icheckbox_flat-green'
            });
        }

        // Hightlight on select 
        function selectRow(data, type) {
            let id = $(data).val();
            var tr = $(data).parent().parent().parent().parent();

            if (id == 0) {
                if (type == 'ifChecked') {
                    $('.skin-check input:checkbox').iCheck('check');
                } else {
                    $('.skin-check input:checkbox').iCheck('uncheck');
                }
            } else {
                var select_id = $('#delete_all').val();
                var value_id = '';

                if (type == 'ifChecked') {
                    tr.toggleClass('row_check');

                    if (select_id == '') {
                        value_id = id;
                        $('#btn_delete').attr('disabled', false);
                    } else {
                        value_id += select_id + ';' + id;
                    }
                } else {
                    tr.toggleClass();

                    var arr = select_id.split(";");
                    var result = arr.filter(function(val) {
                        return val != id;
                    });
                    value_id = result.join(';');

                    if (result.length == 0) {
                        $('#btn_delete').attr('disabled', true);
                    }
                }
                $("input[name=delete_all]").val(value_id);

                if (value_id != '' && value_id != null) {
                    count_select = value_id.split(";").length;
                } else {
                    count_select = 0;
                }

                $('.page-breadcrumb .badge').html(count_select);
            }
        }

        // Select on ROW 
        $(document).ready(function() {
            $('.table').on('click', 'tbody tr', function(e) {
                var td = $(this).children();
                var cekbox = td.eq(1).find('input');
                var checked = cekbox.parent().hasClass('checked');
                if (checked) {
                    if (e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'INPUT' && e.target
                        .tagName !== 'BUTTON' && e.target.tagName !== 'A' && e.target.tagName !== 'I') {
                        cekbox.iCheck('uncheck');
                    }
                } else {
                    var cek_disabled = cekbox.parent().hasClass('disabled');
                    if (!cek_disabled) {
                        if (e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A' && e.target
                            .tagName !== 'I') {
                            cekbox.iCheck('check');
                        }
                    }
                }
            });
        });

        // To highlight row checked when change page 
        function checkChangePage() {
            var select_id = $('#delete_all').val();
            var arr = select_id.split(";");
            arr.forEach(function(value, index) {
                var cekbox = $('.skin-check input:checkbox[value="' + value + '"]');
                cekbox.iCheck('check');
                cekbox.parent().parent().parent().toggleClass('row_cek');
            });
        }
    </script>

    {{-- Delete All function --}}
    <script>
        function deleteAll() {
            var dataid = $('#delete_all').val();
            var link = "{{ url('all/delete') }}";
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
