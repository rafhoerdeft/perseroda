@extends('template.master')

@section('column-table')
    <tr>
        <th>No</th>
        @if ($is_role)
            <th>
                <div class="skin skin-check">
                    <input type="checkbox" name="plh_brg_all" id="check_all" value="0">
                </div>
            </th>
            <th>Aksi</th>
        @endif
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Satuan</th>
        <th>Harga</th>
        <th>Stok Barang</th>
        <th>Stok Minimal</th>
    </tr>
@endsection

@section('content')
    {!! show_alert() !!}

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body p-5">

                    <div class="card-title d-flex align-items-center">
                        <i class="bx bx-cart me-1 font-22 text-primary"></i>
                        <h5 class="mb-0 text-primary">{{ $form_title }}</h5>
                    </div>

                    <hr>
                    <form class="row g-3" method="POST" action="{{ url('barang/save') }}">
                        @csrf

                        <input type="hidden" name="barang_id" value="{{ isset($barang) ? encode($barang->id) : '' }}">

                        <div class="col-md-12">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                id="nama_barang" name="nama_barang"
                                value="{{ old('nama_barang') ?? (isset($barang) ? $barang->nama_barang : '') }}">
                            @error('nama_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="satuan_barang" class="form-label">Satuan Barang</label>
                            <input type="text" class="form-control @error('satuan_barang') is-invalid @enderror"
                                id="satuan_barang" name="satuan_barang"
                                value="{{ old('satuan_barang') ?? (isset($barang) ? $barang->satuan_barang : '') }}">
                            @error('satuan_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="harga" class="form-label">Harga Barang</label>
                            <input type="text" class="form-control @error('harga') is-invalid @enderror" id="harga"
                                name="harga" onkeypress="return numberInput(event);" onkeyup="changeRupe(this)"
                                value="{{ old('harga') ?? (isset($barang) ? nominal($barang->tarif->harga) : '') }}">
                            @error('harga')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="stok_barang" class="form-label">Stok Barang</label>
                            <input type="number" class="form-control @error('stok_barang') is-invalid @enderror"
                                id="stok_barang" name="stok_barang" min="1"
                                value="{{ old('stok_barang') ?? (isset($barang) ? $barang->stok_barang : '') }}">
                            @error('stok_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="stok_minimal" class="form-label">Stok Minimal</label>
                            <input type="number" class="form-control @error('stok_minimal') is-invalid @enderror"
                                id="stok_minimal" name="stok_minimal" min="1"
                                value="{{ old('stok_minimal') ?? (isset($barang) ? $barang->stok_minimal : '') }}">
                            @error('stok_minimal')
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

                        {{-- <div class="col-12">
                            <label for="inputAddress2" class="form-label">Address 2</label>
                            <textarea class="form-control" id="inputAddress2" placeholder="Address 2..." rows="3"></textarea>
                        </div>
        
                        <div class="col-md-4">
                            <label for="inputState" class="form-label">State</label>
                            <select id="inputState" class="form-select">
                                <option selected>Choose...</option>
                                <option>...</option>
                            </select>
                        </div> --}}


                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="page-breadcrumb d-sm-flex align-items-center mb-3">
                <h5 class="card-title">List Data</h5>
                <div class="ms-auto">
                    <input type="hidden" name="delete_all" id="delete_all">
                    <button id="btn_delete" class="btn btn-danger position-relative me-4 w-100 mb-1" type="button"
                        onclick="deleteAll()" disabled>
                        <i class="bx bx-trash"></i>Hapus Data
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">0</span>
                    </button>
                </div>
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
                        @foreach ($list_barang as $row)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                @if ($is_role)
                                    <td>
                                        <div class="skin skin-check">
                                            <input type="checkbox" name="plh_brg[]" id="plh_brg_{{ $row->id }}"
                                                value="{{ $row->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('barang.edit', ['id' => encode($row->id)]) }}"
                                            class="btn btn-info btn-sm" title="Update Data">
                                            <i class="lni lni-pencil-alt me-0 text-white font-sm"></i>
                                        </a>
                                        <button type="button" onclick="deleteData(this)"
                                            data-id="{{ encode($row->id) }}" data-link="{{ url('barang/delete') }}"
                                            class="btn btn-sm btn-danger" title="Hapus Data">
                                            <i class="lni lni-trash me-0 font-sm"></i>
                                        </button>
                                    </td>
                                @endif
                                <td align="center">{{ $row->kode_barang }}</td>
                                <td>{{ $row->nama_barang }}</td>
                                <td align="center">{{ $row->satuan_barang }}</td>
                                <td align="right">{{ isset($row->tarif->harga) ? nominal($row->tarif->harga) : '' }}</td>
                                <td align="right">{{ $row->stok_barang }}</td>
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
    {{-- Icheck --}}
    <link href="{{ asset_ext . 'icheck/css/icheck.css' }}" rel="stylesheet" />
    <link href="{{ asset_ext . 'icheck/css/custom.css' }}" rel="stylesheet" />
    {{-- Sweet Alert --}}
    <link href="{{ asset_ext . 'sweetalert/css/sweetalert.css' }}" rel="stylesheet" />
@endpush

@push('js_plugin')
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset_ext . 'icheck/js/icheck.min.js' }}"></script>
    <script src="{{ asset_ext . 'sweetalert/js/sweetalert.min.js' }}"></script>
    <script src="{{ asset_js . 'datatable_option.js' }}"></script>
    <script src="{{ asset_js . 'delete_data.js' }}"></script>
@endpush

@push('js_script')
    <script>
        var tgl_awal = "{{ date('d-m-Y') }}";
        var info = "Daftar Stok Barang";
        var msg = "Tanggal " + tgl_awal;

        createDataTableExport('list_data', info, msg);
    </script>

    <script>
        var iCek = $('.skin-check input').on('ifChecked ifUnchecked', function(event) {
            selectRow(this, event.type);
        }).iCheck({
            checkboxClass: 'icheckbox_flat-green'
        });

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
    </script>

    <script>
        function deleteAll() {
            var dataid = $('#delete_all').val();
            var link = "{{ url('all/delete') }}";
            var table = "barang";
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
