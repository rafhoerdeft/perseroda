@extends('template.master')

{{-- @section('button-top')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('order.percetakan.list') }}" class="btn btn-primary w-100">
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
                    <a href="{{ route('order.percetakan.list') }}" class="btn btn-primary w-100">
                        <i class="bx bx-left-arrow-circle"></i>Kembali
                    </a>
                </div>
            </div>

            <hr>
            <form class="row g-3" method="POST" action="{{ url('order/percetakan/save') }}">
                @csrf

                <input type="hidden" name="order_id" value="{{ isset($order) ? encode($order->id) : '' }}">

                <div class="col-md-6">
                    <label for="tgl_order" class="form-label">Tanggal Pemesanan</label>
                    <input type="text" class="form-control date-picker" id="tgl_order" name="tgl_order"
                        autocomplete="off" readonly required
                        value="{{ old('tgl_order') ?? (isset($order) ? date('d/m/Y', strtotime($order->tgl_order)) : date('d/m/Y')) }}">
                </div>

                <div class="col-md-6">
                    <label for="nama_klien" class="form-label">Nama Pemesan</label>
                    <input type="text" class="form-control" id="nama_klien" name="nama_klien"
                        value="{{ old('nama_klien') ?? (isset($order) ? $order->nama_klien : '') }}">
                </div>

                <div class="col-md-6">
                    <label for="jenis_bayar" class="form-label">Transaksi</label>
                    <div class="row" id="jenis_bayar">
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="jenis_bayar" id="tunai" value="tunai"
                                autocomplete="off"
                                {{ old('jenis_bayar') != null ? (old('jenis_bayar') == 'tunai' ? 'checked' : '') : (isset($order) ? ($order->jenis_bayar == 'tunai' ? 'checked' : '') : 'checked') }}>
                            <label class="btn btn-outline-primary w-100" for="tunai">Tunai</label>
                        </div>
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="jenis_bayar" id="bank" value="bank"
                                autocomplete="off"
                                {{ old('jenis_bayar') != null ? (old('jenis_bayar') == 'bank' ? 'checked' : '') : (isset($order) ? ($order->jenis_bayar == 'bank' ? 'checked' : '') : '') }}>
                            <label class="btn btn-outline-info w-100" for="bank">Bank</label>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-md-6">
                    <label for="status_bayar" class="form-label">Pembayaran</label>
                    <div class="row" id="status_bayar">
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="status_bayar" id="lunas" value="1"
                                autocomplete="off"
                                {{ old('status_bayar') != null ? (old('status_bayar') == '1' ? 'checked' : '') : (isset($order) ? ($order->status_bayar == '1' ? 'checked' : '') : 'checked') }}>
                            <label class="btn btn-outline-success w-100" for="lunas">Lunas</label>
                        </div>
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="status_bayar" id="belum_bayar" value="0"
                                autocomplete="off"
                                {{ old('status_bayar') != null ? (old('status_bayar') == '0' ? 'checked' : '') : (isset($order) ? ($order->status_bayar == '0' ? 'checked' : '') : '') }}>
                            <label class="btn btn-outline-danger w-100" for="belum_bayar">Belum Bayar</label>
                        </div>
                    </div>
                </div> --}}

                <div class="col-md-6">
                    <label for="dasar_jenis" class="form-label">Dasar Jenis</label>
                    <div class="row" id="dasar_jenis">
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="dasar_jenis" id="surat" value="surat"
                                autocomplete="off"
                                {{ old('dasar_jenis') != null ? (old('dasar_jenis') == 'surat' ? 'checked' : '') : (isset($order) ? ($order->rincian_cetakan->dasar_jenis == 'surat' ? 'checked' : '') : 'checked') }}>
                            <label class="btn btn-outline-warning w-100" for="surat">Surat</label>
                        </div>
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="dasar_jenis" id="lesan" value="lesan"
                                autocomplete="off"
                                {{ old('dasar_jenis') != null ? (old('dasar_jenis') == 'lesan' ? 'checked' : '') : (isset($order) ? ($order->rincian_cetakan->dasar_jenis == 'lesan' ? 'checked' : '') : '') }}>
                            <label class="btn btn-outline-secondary w-100" for="lesan">Lesan</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="dasar_tgl" class="form-label">Dasar Tanggal</label>
                    <input type="text" class="form-control date-picker" id="dasar_tgl" name="dasar_tgl"
                        autocomplete="off" readonly required
                        value="{{ old('dasar_tgl') ?? (isset($order) ? date('d/m/Y', strtotime($order->rincian_cetakan->dasar_tgl)) : date('d/m/Y')) }}">
                </div>

                <div class="col-md-6"
                    style="display: {{ old('dasar_jenis') != null ? (old('dasar_jenis') == 'surat' ? 'block' : 'none') : (isset($order) ? ($order->rincian_cetakan->dasar_jenis == 'surat' ? 'block' : 'none') : 'block') }}">
                    <label for="dasar_nomor" class="form-label">Nomor Surat</label>
                    <input type="text" class="form-control" id="dasar_nomor" name="dasar_nomor"
                        value="{{ old('dasar_nomor') ?? (isset($order) ? $order->rincian_cetakan->dasar_nomor : '') }}"
                        {{ old('dasar_jenis') != null ? (old('dasar_jenis') == 'surat' ? '' : 'disabled') : (isset($order) ? ($order->rincian_cetakan->dasar_jenis == 'surat' ? '' : 'disabled') : '') }}>
                </div>

                <div class="col-md-6"
                    style="display: {{ old('dasar_jenis') != null ? (old('dasar_jenis') == 'lesan' ? 'block' : 'none') : (isset($order) ? ($order->rincian_cetakan->dasar_jenis == 'lesan' ? 'block' : 'none') : 'none') }}">
                    <label for="dasar_oleh" class="form-label">Oleh</label>
                    <input type="text" class="form-control" id="dasar_oleh" name="dasar_oleh"
                        value="{{ old('dasar_oleh') ?? (isset($order) ? $order->rincian_cetakan->dasar_oleh : '') }}"
                        {{ old('dasar_jenis') != null ? (old('dasar_jenis') == 'lesan' ? '' : 'disabled') : (isset($order) ? ($order->rincian_cetakan->dasar_jenis == 'lesan' ? '' : 'disabled') : 'disabled') }}>
                </div>

                <div class="col-md-6">
                    <label for="tgl_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="text" class="form-control date-picker" id="tgl_selesai" name="tgl_selesai"
                        autocomplete="off" readonly required
                        value="{{ old('tgl_selesai') ?? (isset($order) ? date('d/m/Y', strtotime($order->rincian_cetakan->tgl_selesai)) : date('d/m/Y')) }}">
                </div>

                <div class="col-md-6">
                    <label for="lampiran_konsep" class="form-label">Lampiran Contoh/Konsep</label>
                    <div class="row" id="lampiran_konsep">
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="lampiran_konsep" id="ada"
                                value="1" autocomplete="off"
                                {{ old('lampiran_konsep') != null ? (old('lampiran_konsep') == '1' ? 'checked' : '') : (isset($order) ? ($order->rincian_cetakan->lampiran_konsep == '1' ? 'checked' : '') : 'checked') }}>
                            <label class="btn btn-outline-success w-100" for="ada">Ada</label>
                        </div>
                        <div class="col-md-4">
                            <input class="btn-check" type="radio" name="lampiran_konsep" id="tidak_ada"
                                value="0" autocomplete="off"
                                {{ old('lampiran_konsep') != null ? (old('lampiran_konsep') == '0' ? 'checked' : '') : (isset($order) ? ($order->rincian_cetakan->lampiran_konsep == '0' ? 'checked' : '') : '') }}>
                            <label class="btn btn-outline-danger w-100" for="tidak_ada">Tidak Ada</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="koordinator_konsep_nama" class="form-label">Koordinator Konsep</label>
                    <input type="text" class="form-control" id="koordinator_konsep_nama"
                        name="koordinator_konsep_nama"
                        value="{{ old('koordinator_konsep_nama') ?? (isset($order) ? $order->rincian_cetakan->koordinator_konsep_nama : '') }}">
                </div>

                <div class="col-md-6">
                    <label for="koordinator_konsep_tgl" class="form-label">Tanggal Konsep</label>
                    <input type="text" class="form-control date-picker" id="koordinator_konsep_tgl"
                        name="koordinator_konsep_tgl" autocomplete="off" readonly required
                        value="{{ old('koordinator_konsep_tgl') ?? (isset($order) ? date('d/m/Y', strtotime($order->rincian_cetakan->koordinator_konsep_tgl)) : date('d/m/Y')) }}">
                </div>

                <div class="col-md-12">
                    <label for="lain_lain" class="form-label">Lain - Lain</label>
                    <input type="text" class="form-control" id="lain_lain" name="lain_lain"
                        value="{{ old('lain_lain') ?? (isset($order) ? $order->rincian_cetakan->lain_lain : '') }}">
                </div>

                {{-- Pesanan --}}
                <div id="pesanan" class="col-md-12">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <hr>
                            <label class="text-primary fs-5" for="">Pesanan</label>
                        </div>

                        <div class="col-md-10">
                            <label for="jenis_pesanan" class="form-label">Jenis</label>
                            <textarea name="jenis_pesanan" class="form-control" id="" rows="1" style="height: 12px">{{ old('jenis_pesanan') ?? (isset($order) ? $order->rincian_cetakan->jenis_pesanan : '') }}</textarea>
                        </div>

                        <div class="col-md-2">
                            <label for="jml_pesanan" class="form-label">Jumlah</label>
                            <input type="number" min="1" max="10000" class="form-control text-center"
                                id="jml_pesanan" name="jml_pesanan"
                                value="{{ old('jml_pesanan') ?? (isset($order) ? $order->rincian_cetakan->jml_pesanan : '1') }}"
                                placeholder="Jumlah" onblur="checkNumber(this, event)"
                                onkeypress="return numberInput(event);" onkeyup="checkNumber(this, event)">
                        </div>
                    </div>
                </div>

                {{-- Bahan --}}
                <div id="bahan" class="col-md-12">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <hr>
                            <label class="text-primary fs-5" for="">Bahan</label>
                        </div>

                        <div class="col-md-6">
                            <label for="jenis_bahan" class="form-label">Jenis Bahan</label>
                            <input type="text" class="form-control" id="jenis_bahan" name="jenis_bahan"
                                value="{{ old('jenis_bahan') ?? (isset($order) ? $order->rincian_cetakan->jenis_bahan : '') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="ukuran_isi" class="form-label">Ukuran/Isi</label>
                            <input type="text" class="form-control" id="ukuran_isi" name="ukuran_isi"
                                value="{{ old('ukuran_isi') ?? (isset($order) ? $order->rincian_cetakan->ukuran_isi : '') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="warna_tinta" class="form-label">Warna</label>
                            <input type="text" class="form-control" id="warna_tinta" name="warna_tinta"
                                value="{{ old('warna_tinta') ?? (isset($order) ? $order->rincian_cetakan->warna_tinta : '') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="gramatur" class="form-label">Gramatur</label>
                            <input type="text" class="form-control" id="gramatur" name="gramatur"
                                value="{{ old('gramatur') ?? (isset($order) ? $order->rincian_cetakan->gramatur : '') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="muka_halaman" class="form-label">Muka Halaman</label>
                            <input type="text" class="form-control" id="muka_halaman" name="muka_halaman"
                                value="{{ old('muka_halaman') ?? (isset($order) ? $order->rincian_cetakan->muka_halaman : '') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="pakai_nomor" class="form-label">Pemakaian Nomor</label>
                            <div class="row" id="pakai_nomor">
                                <div class="col-md-4">
                                    <input class="btn-check" type="radio" name="pakai_nomor" id="tidak"
                                        value="0" autocomplete="off"
                                        {{ old('pakai_nomor') != null ? (old('pakai_nomor') == '0' ? 'checked' : '') : (isset($order) ? ($order->rincian_cetakan->pakai_nomor == '0' ? 'checked' : '') : 'checked') }}>
                                    <label class="btn btn-outline-danger w-100" for="tidak">Tidak</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="btn-check" type="radio" name="pakai_nomor" id="ya"
                                        value="1" autocomplete="off"
                                        {{ old('pakai_nomor') != null ? (old('pakai_nomor') == '1' ? 'checked' : '') : (isset($order) ? ($order->rincian_cetakan->pakai_nomor == '1' ? 'checked' : '') : '') }}>
                                    <label class="btn btn-outline-success w-100" for="ya">Ya</label>
                                </div>
                                <div class="col-md-4">
                                    {{-- <label for="mulai_nomor" class="form-label">Mulai Nomor</label> --}}
                                    <input type="text" min="0" max="1000" class="form-control text-center"
                                        id="mulai_nomor" name="mulai_nomor"
                                        value="{{ old('pakai_nomor') != null ? old('mulai_nomor') ?? '' : (isset($order) ? $order->rincian_cetakan->mulai_nomor : '') }}"
                                        placeholder="Mulai Nomor" onkeypress="return numberInput(event);"
                                        onkeyup="checkNumber(this, event)"
                                        {{ old('pakai_nomor') != null ? (old('pakai_nomor') == '1' ? '' : 'disabled') : (isset($order) ? ($order->rincian_cetakan->pakai_nomor == '1' ? '' : 'disabled') : 'disabled') }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Finishing --}}
                <div id="finishing" class="col-md-12">
                    <div class="row g-3">
                        <div class="col-md-12 mb-2">
                            <hr>
                            <label class="text-primary fs-5" for="">Finishing</label>
                        </div>

                        <div class="form-check col-md-2">
                            <input type="checkbox" class="form-check-input" name="finishing_lepas" id="finishing_lepas"
                                value="1"
                                {{ old('finishing_lepas') == '1' ? 'checked' : (isset($order) ? ($order->rincian_cetakan->finishing_lepas == '1' ? 'checked' : '') : '') }}>
                            <label for="finishing_lepas" class="form-label">Lepas</label>
                        </div>

                        <div class="form-check col-md-2">
                            <input type="checkbox" class="form-check-input" name="finishing_lem" id="finishing_lem"
                                value="1"
                                {{ old('finishing_lem') == '1' ? 'checked' : (isset($order) ? ($order->rincian_cetakan->finishing_lem == '1' ? 'checked' : '') : '') }}>
                            <label for="finishing_lem" class="form-label">Lem</label>
                        </div>

                        <div class="form-check col-md-2">
                            <input type="checkbox" class="form-check-input" name="finishing_jilid" id="finishing_jilid"
                                value="1"
                                {{ old('finishing_jilid') == '1' ? 'checked' : (isset($order) ? ($order->rincian_cetakan->finishing_jilid == '1' ? 'checked' : '') : '') }}>
                            <label for="finishing_jilid" class="form-label">Jilid</label>
                        </div>

                        <div class="form-check col-md-2">
                            <input type="checkbox" class="form-check-input" name="finishing_paku" id="finishing_paku"
                                value="1"
                                {{ old('finishing_paku') == '1' ? 'checked' : (isset($order) ? ($order->rincian_cetakan->finishing_paku == '1' ? 'checked' : '') : '') }}>
                            <label for="finishing_paku" class="form-label">Paku</label>
                        </div>

                        <div class="form-check col-md-2">
                            <input type="checkbox" class="form-check-input" name="finishing_perforasi"
                                id="finishing_perforasi" value="1"
                                {{ old('finishing_perforasi') == '1' ? 'checked' : (isset($order) ? ($order->rincian_cetakan->finishing_perforasi == '1' ? 'checked' : '') : '') }}>
                            <label for="finishing_perforasi" class="form-label">Perforasi</label>
                        </div>
                    </div>
                </div>

                {{-- Catatan --}}
                <div id="catatan" class="col-md-12">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <hr>
                            <label class="text-primary fs-5" for="">Catatan</label>
                        </div>

                        <div class="col-md-12">
                            <label for="ket_cetakan" class="form-label">Keterangan</label>
                            <textarea name="ket_cetakan" class="form-control" id="" rows="2">{{ old('ket_cetakan') ?? (isset($order) ? $order->rincian_cetakan->ket_cetakan : '') }}</textarea>
                        </div>
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

@push('css_style')
    <style>
        .form-check {
            padding-left: 2rem;
            margin: 0px;
        }

        .form-check label {
            margin-left: 5px;
            margin-bottom: 0px;
            margin-top: 4px;
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
    <script src="{{ asset_ext . 'bootstrap-datepicker/js/bootstrap-datepicker.min.js' }}"></script>
    <script src="{{ asset_js . 'number_input.js' }}"></script>
    <script src="{{ asset_js . 'datatable_option.js' }}"></script>
    <script src="{{ asset_js . 'datepicker_conf.js' }}"></script>
@endpush

@push('js_script')
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
                url: "{{ route('order.percetakan.produk') }}",
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
        $("input[name=dasar_jenis]").change(function() {
            var dasar = $(this).val();
            if (dasar == 'lesan') {
                $('#dasar_oleh').closest('div').show();
                $('#dasar_oleh').attr('disabled', false);
                $('#dasar_nomor').closest('div').hide();
                $('#dasar_nomor').attr('disabled', true);
            } else {
                $('#dasar_oleh').closest('div').hide();
                $('#dasar_oleh').attr('disabled', true);
                $('#dasar_nomor').closest('div').show();
                $('#dasar_nomor').attr('disabled', false);
            }
        });

        $("input[name=lampiran_konsep]").change(function() {
            var konsep = $(this).val();
            if (konsep == 1) {
                $('#koordinator_konsep_nama').closest('div').show();
                $('#koordinator_konsep_nama').attr('disabled', false);
                $('#koordinator_konsep_tgl').closest('div').show();
                $('#koordinator_konsep_tgl').attr('disabled', false);
            } else {
                $('#koordinator_konsep_nama').closest('div').hide();
                $('#koordinator_konsep_nama').attr('disabled', true);
                $('#koordinator_konsep_tgl').closest('div').hide();
                $('#koordinator_konsep_tgl').attr('disabled', true);
            }
        });

        $("input[name=pakai_nomor]").change(function() {
            var dasar = $(this).val();
            if (dasar == '1') {
                $('#mulai_nomor').attr('disabled', false);
            } else {
                $('#mulai_nomor').attr('disabled', true);
            }
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

                $.get("{{ route('order.percetakan.produk') }}", {
                    'id': id
                }, function(data, status) {
                    if (data.response) {
                        try {
                            var stok_produk = parseInt(data.result.stok_produk);
                            if (jml > stok_produk) {
                                throw 'Stok produk tidak cukup';
                            }

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
                '<input type="number" min="1" max="1000" class="form-control form-control-sm text-center" id="' + data.id +
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
                if (rincian_produk != '') {
                    var obj_rincian = JSON.parse(rincian_produk);
                    if (tarif_id in obj_rincian) {
                        key_exist = true;
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

                if (jml > stok) throw "Stok produk tidak cukup";

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
