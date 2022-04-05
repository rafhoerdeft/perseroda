@extends('template.master')

{{-- @section('button-top')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('order.perdagangan.list') }}" class="btn btn-primary w-100">
                <i class="bx bx-left-arrow-circle"></i>Kembali
            </a>
        </div>
    </div>
@endsection --}}

@section('column-table')
    <tr align="center">
        <th>No.</th>
        <th>Aksi</th>
        <th>Kode</th>
        <th>Nama Barang</th>
        <th>Satuan</th>
        <th>Harga</th>
        {{-- <th>Stok</th> --}}
        <th>Jumlah</th>
        <th>Total</th>
    </tr>
@endsection

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
                    <a href="{{ route('order.perdagangan.list') }}" class="btn btn-primary w-100">
                        <i class="bx bx-left-arrow-circle"></i>Kembali
                    </a>
                </div>
            </div>

            <hr>
            <form class="row g-3" method="POST" action="{{ url('order/perdagangan/save') }}">
                @csrf

                <input type="hidden" name="order_id" value="{{ isset($order) ? encode($order->id) : '' }}">

                <div class="col-md-6">
                    <label for="tgl_order" class="form-label">Tanggal Pembelian</label>
                    <input type="text" class="form-control date-picker" id="tgl_order" name="tgl_order" autocomplete="off"
                        readonly required
                        value="{{ old('tgl_order') ?? (isset($order) ? $order->tgl_order : date('d/m/Y')) }}">
                </div>

                <div class="col-md-6">
                    <label for="nama_klien" class="form-label">Nama Pembeli</label>
                    <input type="text" class="form-control" id="nama_klien" name="nama_klien"
                        value="{{ old('nama_klien') ?? (isset($order) ? $order->nama_klien : '') }}">
                </div>

                <div class="col-md-6">
                    <label for="jenis_bayar" class="form-label">Transaksi</label>
                    <div class="row" id="jenis_bayar">
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="jenis_bayar" id="tunai" value="tunai" checked
                                autocomplete="off">
                            <label class="btn btn-outline-primary w-100" for="tunai">Tunai</label>
                        </div>
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="jenis_bayar" id="bank" value="bank"
                                autocomplete="off">
                            <label class="btn btn-outline-info w-100" for="bank">Bank</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="status_bayar" class="form-label">Pembayaran</label>
                    <div class="row" id="status_bayar">
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="status_bayar" id="lunas" value="1" checked
                                autocomplete="off">
                            <label class="btn btn-outline-success w-100" for="lunas">Lunas</label>
                        </div>
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="status_bayar" id="belum_bayar" value="0"
                                autocomplete="off">
                            <label class="btn btn-outline-danger w-100" for="belum_bayar">Belum Bayar</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <hr>
                    <label class="text-primary fs-5" for="">Rincian Barang</label>
                </div>

                <input type="hidden" id="rincian_barang" name="rincian_barang" value="">

                <div class="col-md-8">
                    <select class="select2" name="barang" id="barang">
                        {{-- @foreach ($barang as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
                                @endforeach --}}
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="number" min="1" max="1000" class="form-control text-center" id="jml_barang"
                        name="jml_barang" value="1" placeholder="Jumlah" onblur="checkNumber(this, event)"
                        onkeypress="return numberInput(event);" onkeyup="checkNumber(this, event)">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="button" onclick="getDataBarang()">
                        <i class="fa fa-plus"></i> Tambah
                    </button>
                </div>

                <div class="col-md-12">
                    <hr>
                </div>

                <div class="col-md-12">
                    <table class="table table-striped table-bordered table-hover" id="list_barang">
                        <thead>
                            @yield('column-table')
                        </thead>

                        <tbody>
                        </tbody>

                        <tfoot>
                            @yield('column-table')
                        </tfoot>
                    </table>
                </div>

                <div class="col-md-12">
                    <hr>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label for="tot_bayar">Total Bayar</label>
                        <input type="hidden" class="form-control" id="tot_bayar_input" name="total_bayar" value="0">
                        <h4 class="text-success"><label>Rp.</label> <label id="tot_bayar">0</label></h4>
                    </div>
                    <div class="col-md-4">
                        <label for="nominal_bayar">Nominal Bayar</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" class="form-control" id="nominal_bayar" name="nominal_bayar" min="1"
                                max="999999999" onblur="checkNumber(this, event); totalKembali();"
                                onkeypress="return numberInput(event);"
                                onkeyup="checkNumber(this, event); changeRupe(this); totalKembali();">
                            {{-- <span class="input-group-text">,-</span> --}}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="nominal_kembali">Kembalian</label>
                        <h4 class="text-danger"><label>Rp.</label> <label id="nominal_kembali">0</label></h4>
                    </div>
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
    <link href="{{ asset_ext . 'bootstrap-datepicker/css/bootstrap-datepicker.min.css' }}" rel="stylesheet" />
    <link href="{{ asset_ext . 'bootstrap-datepicker/css/style-datepicker.css' }}" rel="stylesheet" />
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
    <script src="{{ asset_ext . 'bootstrap-datepicker/js/bootstrap-datepicker.min.js' }}"></script>
    <script src="{{ asset_js . 'number_input.js' }}"></script>
    <script src="{{ asset_js . 'datatable_option.js' }}"></script>
@endpush

@push('js_script')
    {{-- Datepicker config --}}
    <script>
        $('.date-picker').datepicker({
            language: 'id',
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            toggleActive: true,
            orientation: 'bottom left',
            // startDate: '0d',
            startDate: '01/01/{{ $year }}',
            endDate: '31/12/{{ $year }}'
        });
    </script>

    {{-- Datatable config --}}
    <script>
        var dataTable;
        $(document).ready(function() {
            createDataTable('list_barang');
            preventInputEnter();
            dataTable = $('#list_barang').DataTable();
        });
    </script>

    {{-- Select2 config --}}
    <script>
        $('.select2').select2({
            ajax: {
                url: "{{ route('order.perdagangan.barang') }}",
                dataType: 'json',
                type: "GET",
                // quietMillis: 50,
                delay: 150,
                data: function(params) {
                    return {
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
                            //         text: item.nama_barang,
                            //         id: item.id
                            //     }
                            // }),

                            //data yang akan ditangkap oleh templateResult & templateSelection
                            results: data.result,
                            pagination: {
                                more: (params.page * 10) < data.count
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
            placeholder: 'Pilih Barang (cari nama barang/kode barang)',
            templateResult: function(item) { //format tampilan saat list pilihan terbuka
                if (item.loading) {
                    return item.text;
                }
                var res = $(
                    '<div class="row">' +
                    '<div class="col-md-10">' + item.nama_barang + '</div>' +
                    '<div class="col-md-2 fw-bold">' + item.kode_barang + '</div>' +
                    '</div>'
                );
                return res;
            },
            templateSelection: function(item) { //format tampilan saat list dipilih
                if (!item.id) {
                    return item.text;
                }
                return item.nama_barang + ' - ' + item.kode_barang;
            }
        }).on('select2:select', function(evt) {
            // var data = $(".select2 option:selected").val();
        });
    </script>

    <script>
        function getDataBarang() {
            var id = $(".select2 option:selected").val();
            var jml = parseInt($('#jml_barang').val());

            try {
                if (!id) {
                    throw 'Pilih barang dahulu!';
                }

                if (jml < 1 || jml == '') {
                    throw 'Jumlah barang tidak boleh kosong atau kurang dari satu';
                }

                $.get("{{ route('order.perdagangan.barang') }}", {
                    'id': id
                }, function(data, status) {
                    if (data.response) {
                        var res = addRincianDataToInput(data.result.tarif.id, jml);
                        if (res.key_exist) {
                            updateRowData(data.result.kode_barang, res.value);
                        } else {
                            addRowData(data.result, jml);
                        }
                        countTotalBayar();
                        resetFormRincian();
                        preventInputEnter(data.result.id);
                    }
                }, 'json');
            } catch (error) {
                alert(error);
            }
        }

        function addRowData(data, jml) {
            var last_row = dataTable.row(':last').data();

            if (!Number.isInteger(parseInt(last_row))) {
                var count = 1;
            } else {
                var count = parseInt(last_row[0]) + 1
            }

            var btn_delete = '<button type="button" onclick="deleteRincian(this)" data-kode="' + data.kode_barang +
                '" data-tarif_id="' + data.tarif.id +
                '"class = "btn btn-sm btn-danger" title = "Hapus Data"> <i class = "lni lni-trash me-0 font-sm"> </i> </button>';

            var input_jml =
                '<input type="number" min="1" max="1000" class="form-control form-control-sm text-center" id="' + data.id +
                '" name="jml_barang_' + data.id + '" value="' + jml +
                '" data-harga="' + data.tarif.harga +
                '" data-kode="' + data.kode_barang +
                '" data-tarif_id="' + data.tarif.id +
                '" onblur="checkNumber(this, event); updateTotal(this);" onkeypress="return numberInput(event);" onchange="updateTotal(this)" onkeyup="checkNumber(this, event); updateTotal(this);">';

            var total = jml * parseInt(data.tarif.harga);
            var row = '<tr>' +
                '<td align="center">' + count + '</td>' +
                '<td align="center">' + btn_delete + '</td>' +
                '<td align="center">' + data.kode_barang + '</td>' +
                '<td>' + data.nama_barang + '</td>' +
                '<td align="center">' + data.satuan_barang + '</td>' +
                '<td align="right">' + formatRupiah(data.tarif.harga.toString()) + '</td>' +
                // '<td align="center">' + data.stok_barang + '</td>' +
                '<td align="right">' + input_jml + '</td>' +
                '<td align="right">' + formatRupiah(total.toString()) + '</td>' +
                '</tr>';

            dataTable.row.add($(row));
            dataTable.draw(false);
        }

        function updateRowData(kode, jml) {
            dataTable.rows(function(idx, data) {
                if (data[2] == kode) {
                    $(dataTable.cell(idx, 6).node()).find('input').val(jml).change();
                }
            });
        }

        function deleteRincian(data) {
            var tarif_id = $(data).data('tarif_id');
            var kode = $(data).data('kode');
            var arr_rincian = JSON.parse($('#rincian_barang').val());

            // remove data from array 
            var results = Object.entries(arr_rincian).filter(function([key, val]) {
                return key != tarif_id;
            });

            // update input rincian_barang 
            $('#rincian_barang').val(JSON.stringify(Object.fromEntries(results)));

            // remove data from row table 
            dataTable.row($(data).parents('tr')).remove().draw(false);
            indexRow();
            countTotalBayar();
        }

        function addRincianDataToInput(tarif_id, jml, add_prev_val = true) {
            var rincian_barang = $('#rincian_barang').val();

            var key_exist = false;
            if (rincian_barang != '') {
                var obj_rincian = JSON.parse(rincian_barang);
                if (tarif_id in obj_rincian) {
                    key_exist = true;
                    if (add_prev_val) {
                        jml += obj_rincian[tarif_id];
                    }
                }
            }

            if (rincian_barang == '') {
                var arr_rincian = {};
            } else {
                var arr_rincian = JSON.parse(rincian_barang);
            }
            arr_rincian[tarif_id] = jml;

            $('#rincian_barang').val(JSON.stringify(arr_rincian));
            return {
                'key_exist': key_exist,
                'value': jml
            };
        }

        function resetFormRincian() {
            $('.select2').val('').change();
            $('#jml_barang').val(1);
        }

        function updateTotal(data) {
            var jml = $(data).val();
            var kode = $(data).data('kode');
            var tarif_id = $(data).data('tarif_id');
            var harga = $(data).data('harga');
            var total = parseInt(jml) * parseInt(harga);
            addRincianDataToInput(tarif_id, parseInt(jml), false);
            dataTable.cell($(data).parents('tr').children('td:eq(7)')).data(formatRupiah(total.toString()));
            countTotalBayar();
        }

        function countTotalBayar() {
            var total = 0;

            dataTable.rows(function(idx, data, node) {
                var nominal = data[7];
                total += parseInt(nominal.replaceAll('.', ''));
            });

            $('#tot_bayar').html(formatRupiah(total.toString()));
            $('#tot_bayar_input').val(total);
            totalKembali();
        }

        function indexRow() {
            dataTable.rows(function(idx, data, node) {
                dataTable.cell({
                    row: idx,
                    column: 0
                }).data(idx + 1).draw(false);
            });
        }

        function totalKembali() {
            // $('#nominal_bayar').on('keyup blur', function() {
            let tot_bayar = $('#tot_bayar_input').val();
            let nominal = $('#nominal_bayar').val().replaceAll('.', '');
            let kembali = parseInt(nominal) - parseInt(tot_bayar);
            if (!kembali) {
                kembali = 0;
            }
            let operator = '';
            if (kembali < 0) {
                operator = '-';
            }
            $('#nominal_kembali').text(operator + formatRupiah(kembali.toString()));
            // });
        }
    </script>

    <script>
        function preventInputEnter(id) {
            if (id) {
                $('#' + id).keydown(function(event) {
                    if (event.keyCode == 13) {
                        event.preventDefault();
                        return false;
                    }
                });
            } else {
                $('input').each(function() {
                    $(this).keydown(function(event) {
                        if (event.keyCode == 13) {
                            if ($(this)[0].id == 'jml_barang') {
                                getDataBarang();
                            }
                            event.preventDefault();
                            return false;
                        }
                    });
                });
            }
        }
    </script>
@endpush
