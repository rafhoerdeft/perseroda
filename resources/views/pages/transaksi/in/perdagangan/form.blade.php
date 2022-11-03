@extends('template.master')

{{-- @section('button-top')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route($main_route.'list') }}" class="btn btn-primary w-100">
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
        <th>Nama Produk</th>
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
    @else
        {!! show_alert() !!}
    @endif

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
            <form class="row g-3" method="POST" action="{{ url('transaksi/in/perdagangan/save') }}">
                @csrf

                <input type="hidden" name="order_id" value="{{ isset($order) ? encode($order->id) : '' }}">

                <div class="col-md-6">
                    <label for="tgl_order" class="form-label">Tanggal Pembelian</label>
                    <input type="text" class="form-control date-picker" id="tgl_order" name="tgl_order"
                        autocomplete="off" readonly required
                        value="{{ old('tgl_order') ?? (isset($order) ? date('d/m/Y', strtotime($order->tgl_order)) : date('d/m/Y')) }}">
                </div>

                <div class="col-md-6">
                    <label for="nama_klien" class="form-label">Nama Pembeli</label>
                    <input type="text" class="form-control" id="nama_klien" name="nama_klien"
                        value="{{ old('nama_klien') ?? (isset($order) ? $order->nama_klien : '') }}">
                </div>

                <div class="col-md-4">
                    <label for="jenis_bayar" class="form-label">Transaksi</label>
                    <div class="row gx-3" id="jenis_bayar">
                        <div class="col-md-6 mb-2">
                            <input class="btn-check" type="radio" name="jenis_bayar" id="tunai" value="tunai"
                                autocomplete="off"
                                {{ old('jenis_bayar') != null ? (old('jenis_bayar') == 'tunai' ? 'checked' : '') : (isset($order) ? ($order->jenis_bayar == 'tunai' ? 'checked' : '') : 'checked') }}>
                            <label class="btn btn-outline-primary w-100" for="tunai">Tunai</label>
                        </div>
                        <div class="col-md-6">
                            <input class="btn-check" type="radio" name="jenis_bayar" id="bank" value="bank"
                                autocomplete="off"
                                {{ old('jenis_bayar') != null ? (old('jenis_bayar') == 'bank' ? 'checked' : '') : (isset($order) ? ($order->jenis_bayar == 'bank' ? 'checked' : '') : '') }}>
                            <label class="btn btn-outline-info w-100" for="bank">Bank</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="status_bayar" class="form-label">Pembayaran</label>
                    <div class="row gx-3" id="status_bayar">
                        <div class="col-md-6 mb-2">
                            <input class="btn-check" type="radio" name="status_bayar" id="lunas" value="1"
                                autocomplete="off"
                                {{ old('status_bayar') != null ? (old('status_bayar') == '1' ? 'checked' : '') : (isset($order) ? ($order->status_bayar == '1' ? 'checked' : '') : 'checked') }}>
                            <label class="btn btn-outline-success w-100" for="lunas">Lunas</label>
                        </div>
                        <div class="col-md-6">
                            <input class="btn-check" type="radio" name="status_bayar" id="belum_bayar" value="0"
                                autocomplete="off"
                                {{ old('status_bayar') != null ? (old('status_bayar') == '0' ? 'checked' : '') : (isset($order) ? ($order->status_bayar == '0' ? 'checked' : '') : '') }}>
                            <label class="btn btn-outline-danger w-100" for="belum_bayar">Belum Bayar</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="status_terima" class="form-label">Penerimaan</label>
                    <div class="row gx-3" id="status_terima">
                        <div class="col-md-6 mb-2">
                            <input class="btn-check" type="radio" name="status_terima" id="langsung" value="1"
                                autocomplete="off"
                                {{ old('status_terima') != null ? (old('status_terima') == '1' ? 'checked' : '') : (isset($order) ? ($order->status_terima == '1' ? 'checked' : '') : 'checked') }}>
                            <label class="btn btn-outline-warning w-100" for="langsung">Langsung</label>
                        </div>
                        <div class="col-md-6">
                            <input class="btn-check" type="radio" name="status_terima" id="diantar" value="0"
                                autocomplete="off"
                                {{ old('status_terima') != null ? (old('status_terima') == '0' ? 'checked' : '') : (isset($order) ? ($order->status_terima == '0' ? 'checked' : '') : '') }}>
                            <label class="btn btn-outline-secondary w-100" for="diantar">Diantar</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <hr>
                    <label class="text-primary fs-5" for="">Rincian Produk</label>
                </div>

                <input type="hidden" id="rincian_produk" name="rincian_produk"
                    value="{{ isset($rincian_order) ? json_encode($rincian_data) : '' }}">

                @if (isset($rincian_order))
                    <input type="hidden" id="rincian_produk_old" name="rincian_produk_old"
                        value="{{ json_encode($rincian_data) }}">
                @endif

                <div class="col-md-8">
                    <select class="select2" name="produk" id="produk">
                        {{-- @foreach ($produk as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_produk }}</option>
                                @endforeach --}}
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="number" min="1" max="9999" class="form-control text-center"
                        id="jml_produk" name="jml_produk" value="1" placeholder="Jumlah"
                        onblur="checkNumber(this, event)" onkeypress="return numberInput(event);"
                        onkeyup="checkNumber(this, event)">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="button" onclick="getDataProduk()">
                        <i class="fa fa-plus"></i> Tambah
                    </button>
                </div>

                <div class="col-md-12">
                    <hr>
                </div>

                <div class="col-md-12">
                    <table class="table table-striped table-bordered table-hover" id="list_produk">
                        <thead>
                            @yield('column-table')
                        </thead>

                        <tbody>
                            @if (isset($rincian_order))
                                @foreach ($rincian_order as $key => $val)
                                    <tr>
                                        <td align="center">{{ $key + 1 }}</td>
                                        <td align="center"><button type="button" onclick="deleteRincian(this)"
                                                data-kode="{{ $val->tarif->produk->kode_produk }}"
                                                data-tarif_id="{{ $val->tarif->id }}"class="btn btn-sm btn-danger"
                                                title="Hapus Data"> <i class="lni lni-trash me-0 font-sm"> </i> </button>
                                        </td>
                                        <td align="center">{{ $val->tarif->produk->kode_produk }}</td>
                                        <td>{{ $val->tarif->produk->nama_produk }}</td>
                                        <td align="center">{{ $val->tarif->produk->satuan_produk }}</td>
                                        <td align="right">{{ nominal($val->tarif->harga) }}</td>
                                        <td align="right" width="125">
                                            <input type="number" min="1" max="9999"
                                                class="form-control form-control-sm text-center"
                                                id="{{ $val->tarif->produk->id }}'"
                                                name="jml_produk_{{ $val->tarif->produk->id }}"
                                                value="{{ $val->jml_order }}" data-jml="{{ $val->jml_order }}"
                                                data-harga="{{ $val->tarif->harga }}"
                                                data-kode="{{ $val->tarif->produk->kode_produk }}"
                                                data-tarif_id="{{ $val->tarif->id }}"
                                                data-stok="{{ $val->tarif->produk->stok_produk + $val->jml_order }}"
                                                onblur="checkNumber(this, event); updateTotal(this);"
                                                onkeypress="return numberInput(event);" onchange="updateTotal(this)"
                                                onkeyup="checkNumber(this, event); updateTotal(this);">
                                        </td>
                                        <td align="right">{{ nominal($val->jml_order * $val->tarif->harga) }}</td>
                                    </tr>
                                @endforeach
                            @endif
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
                        <input type="hidden" class="form-control" id="tot_bayar_input" name="total_bayar"
                            value="{{ isset($rincian_order) ? $tot_order : '0' }}">
                        <h4 class="text-success"><label>Rp.</label> <label
                                id="tot_bayar">{{ isset($rincian_order) ? nominal($tot_order) : '0' }}</label></h4>
                    </div>
                    <div class="col-md-4">
                        <label for="nominal_bayar">Nominal Bayar</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" class="form-control" id="nominal_bayar" name="nominal_bayar"
                                min="1" max="999999999" onblur="checkNumber(this, event); totalKembali();"
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

                <div class="offset-md-4 col-md-8">
                    <div class="row">
                        <div class="col-md-4">
                            <button type="reset" class="btn btn-warning w-100">
                                <i class="bx bx-reset"></i> Reset
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bx bx-save"></i> Simpan
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary w-100" onclick="saveData(this)">
                                <i class="bx bx-printer"></i> Simpan & Print
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('loading')
    <div class="loading-process" id="loading-show">
        <div style="top: 40%; position: relative; z-index: 30">
            @include('template.loading')
        </div>
    </div>
@endpush

@push('css_plugin')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    {{-- Datepicker --}}
    <link href="{{ asset_ext('bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset_ext('bootstrap-datepicker/css/style-datepicker.css') }}" rel="stylesheet" />
    {{-- Select 2 --}}
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
    {{-- Sweet Alert --}}
    <link href="{{ asset_ext('sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />
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
    <script src="{{ asset_ext('sweetalert2/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset_js('number_input.js') }}"></script>
    <script src="{{ asset_js('datatable_option.js') }}"></script>
    <script src="{{ asset_js('datepicker_conf.js') }}"></script>
@endpush

@push('js_script')
    <script>
        function saveData(data) {

            $("#loading-show").fadeIn("slow");

            var form = $(data).closest('form')[0];
            var url = form.action;
            var formData = new FormData(form); //membuat form data baru
            formData.append('print', true);

            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                processData: false,
                contentType: false,
                data: formData,
                success: function(data) {
                    $("#loading-show").fadeIn("slow").delay(10).fadeOut('slow');

                    if (data.success) {
                        var print_dom = $('<div id="print-dom"></div>');
                        var print_frame = $(
                            '<iframe id="print-content" src="' + data.print +
                            '" scrolling="no" width="100%" border="0" frameborder="0" name="print-content" />'
                        );

                        print_dom
                            .hide()
                            .append(print_frame)
                            .appendTo('body');

                        swal.fire({
                            title: "Sukses!",
                            text: "Nota akan segera diprint!",
                            type: "success",
                            icon: 'success',
                            timer: 2000
                        }).then(function() {
                            // location.reload();
                            location.href = data.url;
                        });
                    } else {
                        swal.fire({
                            title: "Gagal!",
                            html: data.alert,
                            type: "error",
                            icon: "error",
                            // timer: 2000
                        });
                    }
                },
                error: function(data) {
                    $("#loading-show").fadeIn("slow").delay(10).fadeOut('slow');

                    swal.fire({
                        title: "Gagal! " + '(Error ' + data.responseJSON.code + ')',
                        text: 'Error saat kirim data. ' + data
                            .responseJSON.title + '. ' + data
                            .responseJSON.message,
                        type: "error",
                        icon: "error",
                        // timer: 2000
                    }).then(function() {
                        clearInterval(tokenTimer);
                        $('#' + form.id)[0].reset();
                    });
                }
            });
        }
    </script>

    {{-- Datatable config --}}
    <script>
        var dataTable;
        $(document).ready(function() {
            createDataTable('list_produk');
            preventInputEnter();
            dataTable = $('#list_produk').DataTable();
            datepickerShow('date-picker', '{{ $year }}');
        });
    </script>

    {{-- Select2 config --}}
    <script>
        var limit_data_show = 10;
        $('.select2').select2({
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
            placeholder: 'Pilih Produk (cari nama produk/kode produk)',
            templateResult: function(item) { //format tampilan saat list pilihan terbuka
                if (item.loading) {
                    return item.text;
                }
                var res = $(
                    '<div class="row">' +
                    '<div class="col-md-10">' + item.nama_produk + '</div>' +
                    '<div class="col-md-2 fw-bold">' + item.kode_produk + '</div>' +
                    '</div>'
                );
                return res;
            },
            templateSelection: function(item) { //format tampilan saat list dipilih
                if (!item.id) {
                    return item.text;
                }
                return item.nama_produk + ' - ' + item.kode_produk;
            }
        }).on('select2:select', function(evt) {
            // var data = $(".select2 option:selected").val();
        });
    </script>

    <script>
        function getDataProduk() {
            var id = $(".select2 option:selected").val();
            var jml = parseInt($('#jml_produk').val());
            var order_id = $("input[name=order_id]").val();

            try {
                if (!id) {
                    throw 'Pilih produk dahulu!';
                }

                if (jml < 1 || jml == '') {
                    throw 'Jumlah produk tidak boleh kosong atau kurang dari satu';
                }

                $.get("{{ route($main_route . 'produk') }}", {
                    'id': id
                }, function(data, status) {
                    if (data.response) {
                        try {
                            var stok_produk = parseInt(data.result.stok_produk);

                            // if (jml > stok_produk) {
                            //     $('#jml_produk').val(stok_produk);
                            //     throw 'Stok produk tidak cukup';
                            // }

                            // Cek apakah edit data atau tambah data order baru
                            // untuk mengkalkulasikan stok produk 
                            if (order_id) {
                                var rincian_produk_old = $('#rincian_produk_old').val();
                                var obj_rincian = JSON.parse(rincian_produk_old);
                                // cek apakah data produk yg dipilih ada pada rincian produk old
                                if (data.result.tarif.id in obj_rincian) {
                                    stok_produk += obj_rincian[data.result.tarif.id];
                                }
                            }
                            var res = addRincianDataToInput(data.result.tarif.id, jml, stok_produk);

                            if (!res.response) throw res.message;

                            if (res.key_exist) {
                                updateRowData(data.result.kode_produk, res.value);
                            } else {
                                addRowData(data.result, jml, stok_produk);
                            }
                            countTotalBayar();
                            resetFormRincian();
                            preventInputEnter(data.result.id);
                        } catch (error) {
                            alert(error);
                        }
                    }
                }, 'json');
            } catch (error) {
                alert(error);
            }
        }

        function addRowData(data, jml, stok) {
            var last_row = dataTable.row(':last').data();

            if (!Number.isInteger(parseInt(last_row))) {
                var count = 1;
            } else {
                var count = parseInt(last_row[0]) + 1
            }

            var btn_delete = '<button type="button" onclick="deleteRincian(this)" data-kode="' + data.kode_produk +
                '" data-tarif_id="' + data.tarif.id +
                '"class = "btn btn-sm btn-danger" title = "Hapus Data"> <i class = "lni lni-trash me-0 font-sm"> </i> </button>';

            var input_jml =
                '<input type="number" min="1" max="9999" class="form-control form-control-sm text-center" id="' + data.id +
                '" name="jml_produk_' + data.id + '" value="' + jml +
                '" data-jml="' + jml +
                '" data-harga="' + data.tarif.harga +
                '" data-kode="' + data.kode_produk +
                '" data-tarif_id="' + data.tarif.id +
                '" data-stok="' + stok +
                '" onblur="checkNumber(this, event); updateTotal(this);" onkeypress="return numberInput(event);" onchange="updateTotal(this)" onkeyup="checkNumber(this, event); updateTotal(this);">';

            var total = jml * parseInt(data.tarif.harga);
            var row = '<tr>' +
                '<td align="center">' + count + '</td>' +
                '<td align="center">' + btn_delete + '</td>' +
                '<td align="center">' + data.kode_produk + '</td>' +
                '<td>' + data.nama_produk + '</td>' +
                '<td align="center">' + data.satuan_produk + '</td>' +
                '<td align="right">' + formatRupiah(data.tarif.harga.toString()) + '</td>' +
                // '<td align="center">' + stok + '</td>' +
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

                    $(dataTable.cell(idx, 6).node()).find('input').attr('value', jml);
                    $(dataTable.cell(idx, 6).node()).find('input').attr('data-jml', jml);
                }
            });
        }

        function deleteRincian(data) {
            var tarif_id = $(data).data('tarif_id');
            var kode = $(data).data('kode');
            var arr_rincian = JSON.parse($('#rincian_produk').val());

            // remove data from array 
            var results = Object.entries(arr_rincian).filter(function([key, val]) {
                return key != tarif_id;
            });

            // update input rincian_produk 
            $('#rincian_produk').val(JSON.stringify(Object.fromEntries(results)));

            // remove data from row table 
            dataTable.row($(data).parents('tr')).remove().draw(false);
            indexRow();
            countTotalBayar();
        }

        function addRincianDataToInput(tarif_id, jml, stok, add_prev_val = true) {
            try {
                var rincian_produk = $('#rincian_produk').val();

                var key_exist = false;
                var tot_ambil = 0;
                if (rincian_produk != '') {
                    var obj_rincian = JSON.parse(rincian_produk);
                    if (tarif_id in obj_rincian) {
                        key_exist = true;
                        tot_ambil += obj_rincian[tarif_id]; // total yg sudah diambil
                        if (add_prev_val) {
                            jml += obj_rincian[tarif_id];
                        }
                    }
                }

                if (rincian_produk == '') {
                    var arr_rincian = {};
                } else {
                    var arr_rincian = JSON.parse(rincian_produk);
                }
                arr_rincian[tarif_id] = jml;

                if (jml > stok) {
                    let sisa_stok = stok - tot_ambil;
                    $('#jml_produk').val(sisa_stok);
                    if (sisa_stok == 0) {
                        throw "Stok produk sudah habis";
                    } else {
                        throw "Stok produk tidak cukup";
                    }
                }

                $('#rincian_produk').val(JSON.stringify(arr_rincian));
                var result = {
                    'response': true,
                    'key_exist': key_exist,
                    'value': jml
                };
            } catch (error) {
                var result = {
                    'response': false,
                    'message': error
                };
            }

            return result;
        }

        function resetFormRincian() {
            $('.select2').val('').change();
            $('#jml_produk').val(1);
        }

        function updateTotal(data) {
            try {
                var jml = $(data).val();
                var current_jml = $(data)[0].dataset.jml;
                var stok = $(data).data('stok');
                var kode = $(data).data('kode');
                var tarif_id = $(data).data('tarif_id');
                var harga = $(data).data('harga');
                var total = parseInt(jml) * parseInt(harga);
                var rincian = addRincianDataToInput(tarif_id, parseInt(jml), parseInt(stok), false);
                if (!rincian.response) {
                    // jika false value jml akan diundo
                    $(data).val(current_jml);
                    throw rincian.message;
                }
                // update data pada value dan data-jml terakhir
                dataTable.rows(function(idx, data) {
                    if (data[2] == kode) {
                        $(dataTable.cell(idx, 6).node()).find('input').attr('value', jml);
                        $(dataTable.cell(idx, 6).node()).find('input').attr('data-jml', jml);
                    }
                });
                dataTable.cell($(data).parents('tr').children('td:eq(7)')).data(formatRupiah(total.toString()));
                countTotalBayar();
            } catch (error) {
                alert(error);
            }
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

        // Mengubah index nomor pada datatable agar urut kembali
        function indexRow() {
            //Looping row pada datatable
            dataTable.rows(function(idx, data, node) {
                dataTable.cell({
                    row: idx,
                    column: 0
                }).data(idx + 1).draw(false); //Redraw datatable dengan mempertahankan posisi paging saat ini
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
                            if ($(this)[0].id == 'jml_produk') {
                                getDataProduk();
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
