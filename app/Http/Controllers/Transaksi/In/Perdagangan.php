<?php

namespace App\Http\Controllers\Transaksi\In;

use App\Http\Controllers\UserBaseController;
use App\Models\Produk;
use App\Models\NoOrder;
use App\Models\Order;
use App\Models\RincianOrder;
use App\Models\Tarif;
use App\Models\UnitUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class Perdagangan extends UserBaseController
{
    protected $is_role;

    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            $this->is_role = false;
            $role = ['kasir'];
            if (in_array(auth()->user()->role->nama_role, $role)) {
                $this->is_role = true;
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $breadcrumb = ['Transaksi Perdagangan'];
        $form_title = 'Input Transaksi';

        $status_bayar_select = $request->status_bayar;
        $jenis_bayar_select = $request->jenis_bayar;
        $status_terima_select = $request->status_terima;

        $is_role = $this->is_role;

        $main_route = 'transaksi.in.perdagangan.';
        $link_datatable = url('transaksi/in/perdagangan/data/' . $status_bayar_select . '/' . $jenis_bayar_select . '/' . $status_terima_select);

        $data = compact(
            'breadcrumb',
            'form_title',
            'is_role',
            'status_bayar_select',
            'jenis_bayar_select',
            'status_terima_select',
            'main_route',
            'link_datatable',
        );

        return view('pages/transaksi/in/perdagangan/list', $data);
    }

    public function getData($status = '', $jenis = '', $status_trm = '', Request $request)
    {
        $year = selected_year;

        if ($status == 'null') {
            $status = '';
        }

        if ($jenis == 'null') {
            $jenis = '';
        }

        if ($status_trm == 'null') {
            $status_trm = '';
        }

        if ($request->ajax()) {
            $status_bayar = [
                0 => 'Belum Bayar',
                1 => 'Lunas'
            ];

            $status_terima = [
                0 => 'Belum Diterima',
                1 => 'Diterima'
            ];

            $list_order = Order::whereHas('unit_usaha', function ($query) {
                $query->where('nama_unit_usaha', '=', 'perdagangan');
            });
            $list_order->with('rincian_order');
            if ($this->is_role) {
                $list_order->where('user_id', auth()->user()->id);
            }
            $list_order->whereYear('tgl_order', '=', $year);
            $list_order->where([['status_bayar', 'LIKE', '%' . $status . '%'], ['jenis_bayar', 'LIKE', '%' . $jenis . '%'], ['status_terima', 'LIKE', '%' . $status_trm . '%']]);
            $list_order->latest()->get();

            $data_tables = DataTables::of($list_order);
            $raw_columns = [];

            $data_tables->addIndexColumn();
            $data_tables->editColumn('tgl_order', function ($row) {
                return date('d/m/Y', strtotime($row->tgl_order));
            });
            $data_tables->addColumn('total', function ($row) {
                return nominal(
                    $row->rincian_order->sum(function ($item) {
                        return $item->jml_order * $item->tarif->harga + $item->biaya_tambahan;
                    })
                );
            });
            $data_tables->editColumn('status_bayar', function ($row) use ($status_bayar) {
                if ($row->status_bayar == 0) {
                    $bg = 'danger';
                    $col_sts_byr =  '<span class="badge rounded-pill bg-' . $bg . ' w-75">' . $status_bayar[$row->status_bayar] . '</span>';
                    if ($this->is_role) {
                        $col_sts_byr .= "<div class='mt-1'>
                                            <button type='button' class='btn btn-sm btn-warning text-sm-b p-1 w-75' 
                                            data-post='" . json_encode(['id' => encode($row->id)]) . "'
                                            data-link='" . url('transaksi/in/perdagangan/change/statusbayar') . "'
                                            data-table='list_data' 
                                            data-title='Ubah status bayar (" . $row->no_order . ")'
                                            data-text='Status bayar akan diubah menjadi LUNAS?'
                                            onclick='confirmDialog(this, false)' title='Ubah Status Bayar'>Ubah Status</button>
                                        </div>";
                    }
                    return $col_sts_byr;
                } else {
                    $bg = 'success';
                    return '<span class="badge rounded-pill bg-' . $bg . ' w-75">' . $status_bayar[$row->status_bayar] . '</span>';
                }
            });
            $raw_columns[] = 'status_bayar';

            $data_tables->editColumn('jenis_bayar', function ($row) {
                $bg = 'primary';
                if ($row->jenis_bayar == 'bank') {
                    $bg = 'info';
                }
                if ($this->is_role) {
                    return "<div class='btn-group w-75'>
                                <button type='button' class='btn btn-sm btn-" . $bg . " text-white text-sm-b p-0' title='" . text_uc($row->jenis_bayar) . "'>" . text_uc($row->jenis_bayar) . "</button>
                                <button type='button' class='btn btn-sm btn-" . $bg . " text-white p-0 split-bg-" . $bg . " dropdown-toggle dropdown-toggle-split' data-bs-toggle='dropdown' aria-expanded='false' title='Ubah Jenis Bayar'>
                                </button>
                                <div class='dropdown-menu dropdown-menu-right dropdown-menu-lg-end' style='margin: 0px;'>	
                                    <a class='dropdown-item " . ($row->jenis_bayar == 'bank' ? 'active bg-info' : '') . "' href='javascript:void(0);' data-post='" . json_encode(['id' => encode($row->id), 'jenis' => 'bank']) . "' 
                                    data-link='" . url('transaksi/in/perdagangan/change/jenisbayar') . "'
                                    data-table='list_data' 
                                    data-title='Ubah jenis bayar (" . $row->no_order . ")'
                                    data-text='Jenis bayar akan diubah menjadi Bank?'
                                    onclick='" . ($row->jenis_bayar == 'bank' ? '' : 'confirmDialog(this, false)') . "'>Bank</a>

                                    <a class='dropdown-item " . ($row->jenis_bayar == 'tunai' ? 'active' : '') . "' href='javascript:void(0);' data-post='" . json_encode(['id' => encode($row->id), 'jenis' => 'tunai']) . "' 
                                    data-link='" . url('transaksi/in/perdagangan/change/jenisbayar') . "'
                                    data-table='list_data' 
                                    data-title='Ubah jenis bayar (" . $row->no_order . ")'
                                    data-text='Jenis bayar akan diubah menjadi Tunai?' 
                                    onclick='" . ($row->jenis_bayar == 'tunai' ? '' : 'confirmDialog(this, false)') . "'>Tunai</a>
                                </div>
                            </div>";
                } else {
                    return '<span class="badge bg-' . $bg . ' w-75">' . text_uc($row->jenis_bayar) . '</span>';
                }
            });
            $raw_columns[] = 'jenis_bayar';

            $data_tables->editColumn('status_terima', function ($row) use ($status_terima) {
                if ($row->status_terima == 0) {
                    $bg = 'secondary';
                    $col_sts_byr =  '<span class="badge rounded-pill bg-' . $bg . ' w-75">' . $status_terima[$row->status_terima] . '</span>';
                    if ($this->is_role) {
                        $col_sts_byr .= "<div class='mt-1'>
                                            <button type='button' class='btn btn-sm btn-danger text-sm-b p-1 w-75' 
                                            data-post='" . json_encode(['id' => encode($row->id)]) . "'
                                            data-link='" . url('transaksi/in/perdagangan/change/statusterima') . "'
                                            data-table='list_data' 
                                            data-title='Ubah status terima (" . $row->no_order . ")'
                                            data-text='Status terima akan diubah menjadi DITERIMA?'
                                            onclick='confirmDialog(this, false)' title='Ubah Status Terima'>Ubah Status</button>
                                        </div>";
                    }
                    return $col_sts_byr;
                } else {
                    $bg = 'warning';
                    return '<span class="badge rounded-pill bg-' . $bg . ' w-75">' . $status_terima[$row->status_terima] . '</span>';
                }
            });
            $raw_columns[] = 'status_terima';

            if ($this->is_role) {
                $data_tables->addColumn('check_all', function ($row) {
                    $check = '<div class="form-check"> <input type="checkbox" class="form-check-input" onchange="onCheckChange(this)" name="select_row[]" id="select_row_' . $row->id . '" value="' . $row->id . '"> </div>';
                    return $check;
                });


                $raw_columns[] = 'check_all';
            }

            $data_tables->addColumn('action', function ($row) {
                $btn_detail = '<button type="button" onclick="showDetail(this)" 
                                    data-no_order="' . $row->no_order . '"
                                    data-nama_produk="' . $row->rincian_order->implode('tarif.produk.nama_produk', ';') . '"
                                    data-jml_order="' . $row->rincian_order->implode('jml_order', ';') . '"
                                    data-harga="' . $row->rincian_order->implode('harga', ';') . '"
                                    data-satuan="' . $row->rincian_order->implode('tarif.produk.satuan_produk', ';') . '"
                                    data-biaya_tambahan="' . $row->rincian_order->implode('biaya_tambahan', ';') . '"
                                    class="btn btn-sm btn-primary" title="Rincian Order">
                                    <i class="lni lni-list me-0 font-sm"></i>
                                </button> ';
                $btn_update = '<a href="' . route('transaksi.in.perdagangan.edit', ['id' => encode($row->id)]) . '"
                                    class="btn btn-info btn-sm" title="Update Data">
                                    <i class="lni lni-pencil-alt me-0 text-white font-sm"></i>
                                </a> ';
                $btn_delete = '<button type="button" onclick="deleteData(this, false)" 
                                    data-id="' . encode($row->id) . '"
                                    data-link="' . url('transaksi/in/perdagangan/delete') . '"
                                    data-table="list_data"
                                    class="btn btn-sm btn-danger" title="Hapus Data">
                                    <i class="lni lni-trash me-0 font-sm"></i>
                                </button> ';
                $action_btn = $btn_detail;
                if ($this->is_role) {
                    $action_btn .= $btn_update;
                    $action_btn .= $btn_delete;
                }
                return $action_btn;
            });
            $raw_columns[] = 'action';

            $data_tables->rawColumns($raw_columns);

            return $data_tables->make(true);
        }
    }

    public function getProduk(Request $request)
    {
        try {
            if ($request->id) {
                $produk = Produk::with('tarif:id,harga,produk_id')->find($request->id);
                if (!$produk) {
                    throw new \Exception('Data produk tidak ditemukan.');
                }
                $res = ['response' => true, 'result' => $produk];
            } else {
                $limit = $request->limit ?? 10;
                $produk = Produk::with('tarif:id,harga,produk_id')->select(['id', 'nama_produk', 'kode_produk', 'stok_produk'])->whereHas('tarif', function ($query) {
                    $query->where('produk_id', '!=', null);
                    // $query->whereHas('unit_usaha', function ($query) {
                    //     $query->where('nama_unit_usaha', 'perdagangan');
                    // });
                })->where('stok_produk', '>', 0)->where(function ($query) use ($request) {
                    $query->where('kode_produk', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('nama_produk', 'LIKE', '%' . $request->search . '%');
                });

                $count = $produk->count();
                $result = $produk->offset((($request->page - 1) * $limit))->limit($limit)->get();

                if (!$result) {
                    throw new \Exception('Gagal mengambil data produk.');
                }

                $res = [
                    'response' => true,
                    'count' => $count,
                    'result' => $result
                ];
            }
        } catch (\Exception $e) {
            $res = ['response' => false, 'result' => $e->getMessage()];
        }

        return json_encode($res);
    }

    public function add()
    {
        $no_order = $this->nomorOrder()['no_order'];
        $year = selected_year;
        $main_route = 'transaksi.in.perdagangan.';

        $breadcrumb = ['transaksi/in/perdagangan' => 'Transaksi Perdagangan', 'Form Input']; //url => title
        $form_title = 'Input Transaksi - ' . $no_order;
        return view('pages/transaksi/in/perdagangan/form', compact(
            'breadcrumb',
            'form_title',
            'year',
            'main_route'
        ));
    }

    public function edit($id = null)
    {
        $year = selected_year;
        $order = Order::with('rincian_order')->find(decode($id));
        $rincian_order = RincianOrder::with('tarif')->where('order_id', $order->id)->get();
        $tot_order = $rincian_order->sum(function ($item) {
            return $item->jml_order * $item->tarif->harga + $item->biaya_tambahan;
        });
        $rincian_data = [];
        foreach ($rincian_order as $val) {
            $rincian_data[$val->tarif_id] = $val->jml_order;
        }
        $no_order = $order->no_order;

        $main_route = 'transaksi.in.perdagangan.';

        $breadcrumb = ['transaksi/in/perdagangan' => 'Transaksi Perdagangan', 'Form Input']; //url => title
        $form_title = 'Edit Order - ' . $no_order;
        return view('pages/transaksi/in/perdagangan/form', compact(
            'breadcrumb',
            'form_title',
            'order',
            'year',
            'rincian_order',
            'tot_order',
            'rincian_data',
            'main_route'
        ));
    }

    public function save(Request $request)
    {
        // $request->validate([
        //     'tgl_order'  => 'required|date_format:d/m/Y',
        //     'nama_klien'  => 'string|nullable',
        //     'jenis_bayar'  => 'required|in:tunai,bank',
        //     'status_bayar'  => 'required|numeric|between:0,1',
        //     'rincian_produk'  => 'required|string',
        //     'total_bayar'  => 'required|integer',
        //     // 'harga'  => 'required|regex:/^[0-9\.,]+$/|not_in:0'
        // ]);

        $validator = Validator::make($request->all(), [
            'tgl_order'  => 'required|date_format:d/m/Y',
            'nama_klien'  => 'string|nullable',
            'jenis_bayar'  => 'required|in:tunai,bank',
            'status_bayar'  => 'required|numeric|between:0,1',
            'status_terima'  => 'required|numeric|between:0,1',
            'rincian_produk'  => 'required|string',
            'total_bayar'  => 'required|integer',
            // 'harga'  => 'required|regex:/^[0-9\.,]+$/|not_in:0'
        ]);

        DB::beginTransaction();
        try {
            if ($validator->fails()) {
                throw new \Exception(json_encode($validator->errors()->all()));
            }

            $data_order = [
                'user_id'   => auth()->user()->id,
                'nama_klien'  => $request->nama_klien,
                // 'no_hp_klien'  => $request->no_hp_klien,
                'tgl_order' => re_date_format($request->tgl_order),
                'status_order' => 3,
                // 'jenis_order' => $request->jenis_order,
                'status_bayar' => $request->status_bayar,
                'status_terima' => $request->status_terima,
                'jenis_bayar' => $request->jenis_bayar,
                'total_bayar' => $request->total_bayar,
            ];

            if ($request->order_id) {
                $order_id = decode($request->order_id);
            } else {
                $order_id = null;
                $no_order = $this->nomorOrder(true);
                if ($no_order === false) {
                    throw new \Exception("Gagal simpan nomor transaksi.in.");
                }
                $data_order['unit_usaha_id']   = $no_order['unit_usaha_id'];
                $data_order['no_order']   = $no_order['no_order'];
            }

            if ($order_id == null) {
                $order = Order::create($data_order);
                $order_id = $order->id;
            } else {
                $order = Order::find($order_id)->update($data_order);
            }

            if (!$order) {
                throw new \Exception("Gagal simpan data order");
            }

            // else {
            //     RincianOrder::where('order_id', $order_id)->delete();
            // }

            $rincian_produk = json_decode($request->rincian_produk, true); // New rincian produk

            if ($request->rincian_produk_old) { // Rincian produk before update
                $rincian_produk_old = json_decode($request->rincian_produk_old, true);

                foreach ($rincian_produk_old as $tarif_id => $jml) {
                    Produk::whereHas('Tarif', function ($query) use ($tarif_id) {
                        $query->where('id', $tarif_id);
                    })->increment('stok_produk', $jml); // return stok_produk

                    if (!array_key_exists($tarif_id, $rincian_produk)) {
                        RincianOrder::where([['order_id', '=', $order_id], ['tarif_id', '=', $tarif_id]])->delete(); // Delete rincian produk old
                    }
                }
            }

            // $data_rincian = [];
            foreach ($rincian_produk as $tarif_id => $jml) {
                $data_rincian = [
                    'order_id'      => $order_id,
                    'tarif_id'      => $tarif_id,
                    'jml_order'     => $jml,
                    'harga'         => Tarif::find($tarif_id)->harga,
                ];

                Produk::whereHas('Tarif', function ($query) use ($tarif_id) {
                    $query->where('id', $tarif_id);
                })->decrement('stok_produk', $jml); // reduce stok_produk

                $rincian_data = RincianOrder::where([['order_id', '=', $order_id], ['tarif_id', '=', $tarif_id]]);
                if ($rincian_data->count() > 0) { // Check if data exist
                    $rincian_data->update($data_rincian);
                } else {
                    RincianOrder::create($data_rincian);
                }
            }

            // RincianOrder::upsert($data_rincian, ['order_id', 'tarif_id'], ['jml_order', 'harga']); // Update rincian order OR create new data

            DB::commit();
            alert_success('Data order berhasil disimpan.');
            $response = ['success' => true, 'print' => url('transaksi/in/perdagangan/print/' . encode($order_id))];

            // return $this->printNota($order_id);
        } catch (\Exception $e) {
            DB::rollBack();
            alert_failed('Data order gagal disimpan.' . json_check($e->getMessage()));
            $response = ['success' => false, 'alert' => json_check($e->getMessage())];
        }

        if ($request->order_id) {
            $url = 'transaksi/in/perdagangan';
        } else {
            $url = 'transaksi/in/perdagangan/add';
        }

        if ($request->print) {
            $response['url'] = url($url);
            return json_encode($response);
        } else {
            if ($response['success']) {
                return redirect($url);
            } else {
                return redirect($url)
                    ->withErrors($validator)
                    ->withInput();
            }
        }
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), ['id' => 'required']);

            if ($validator->fails()) {
                throw new \Exception($validator->errors());
            }

            $id = $request->id;
            $deleted = Order::find(decode($id))->delete();
            if (!$deleted) {
                throw new \Exception('Gagal hapus data!');
            }

            $rincian = RincianOrder::where('order_id', decode($id))->get();

            foreach ($rincian as $val) {
                $tarif_id = $val->tarif_id;
                $jml = $val->jml_order;
                Produk::whereHas('Tarif', function ($query) use ($tarif_id) {
                    $query->where('id', $tarif_id);
                })->increment('stok_produk', $jml); // return stok_produk
            }

            $res = ['success' => true];
        } catch (\Exception $e) {
            $res = ['success' => false, 'alert' => $e->getMessage()];
        }

        return json_encode($res);
    }

    public function deleteAll(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), ['dataid' => 'required', 'table' => 'required']);

            if ($validator->fails()) {
                throw new \Exception($validator->errors());
            }

            $dataid = explode(";", $request->dataid);
            $table = $request->table;

            $query = DB::table($table)->whereIn('id', $dataid);

            if ($request->soft === 'true') {
                $deleted = $query->update(['deleted_at' => now()]);
            } else {
                $deleted = $query->delete();
            }
            if (!$deleted) {
                throw new \Exception('Gagal hapus data!');
            }

            $rincian = RincianOrder::whereIn('order_id', $dataid)->get();

            foreach ($rincian as $val) {
                $tarif_id = $val->tarif_id;
                $jml = $val->jml_order;
                Produk::whereHas('Tarif', function ($query) use ($tarif_id) {
                    $query->where('id', $tarif_id);
                })->increment('stok_produk', $jml); // return stok_produk
            }

            DB::commit();
            $res = ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            $res = ['success' => false, 'alert' => $e->getMessage()];
        }

        return json_encode($res);
    }

    public function nomorOrder($save = false)
    {
        $year = selected_year;

        $unit_usaha = UnitUsaha::where('nama_unit_usaha', '=', 'perdagangan')->first();

        $where = [
            'unit_usaha_id' => $unit_usaha->id,
            'tahun' =>  $year,
        ];

        $no_order = NoOrder::where($where);

        if ($no_order->count() > 0) {
            $last = (int) $no_order->first()->nomor;
            $nomor = sprintf("%04s", $last + 1);
        } else {
            $nomor = '0001';
        }

        $nomor_order = $unit_usaha->kode_unit_usaha . date('y', strtotime($year)) . $nomor;

        if ($save) {
            if (!NoOrder::updateOrCreate($where, ['nomor' => $nomor])) {
                return false;
            }
        }

        return ['no_order' => $nomor_order, 'unit_usaha_id' => $unit_usaha->id];
    }

    public function changeStatusBayar(Request $request)
    {
        DB::beginTransaction();
        try {
            $data_order = [
                'status_bayar' => 1,
            ];

            $order_id = decode($request->id);

            $order = Order::find($order_id)->update($data_order);

            if (!$order) {
                throw new \Exception("Gagal ubah status bayar");
            }

            DB::commit();
            $res = ['response' => true, 'text' => 'Berhasil ubah status bayar'];
        } catch (\Exception $e) {
            DB::rollBack();
            $res = ['response' => false, 'text' => $e->getMessage()];
        }

        return json_encode($res);
    }

    public function changeJenisBayar(Request $request)
    {
        DB::beginTransaction();
        try {
            $data_order = [
                'jenis_bayar' => $request->jenis,
            ];

            $order_id = decode($request->id);

            $order = Order::find($order_id)->update($data_order);

            if (!$order) {
                throw new \Exception("Gagal ubah jenis bayar");
            }

            DB::commit();
            $res = ['response' => true, 'text' => 'Berhasil ubah jenis bayar'];
        } catch (\Exception $e) {
            DB::rollBack();
            $res = ['response' => false, 'text' => $e->getMessage()];
        }

        return json_encode($res);
    }

    public function changeStatusTerima(Request $request)
    {
        DB::beginTransaction();
        try {
            $data_order = [
                'status_terima' => 1,
            ];

            $order_id = decode($request->id);

            $order = Order::find($order_id)->update($data_order);

            if (!$order) {
                throw new \Exception("Gagal ubah status terima");
            }

            DB::commit();
            $res = ['response' => true, 'text' => 'Berhasil ubah status terima'];
        } catch (\Exception $e) {
            DB::rollBack();
            $res = ['response' => false, 'text' => $e->getMessage()];
        }

        return json_encode($res);
    }

    public function printNota($id, $link = null)
    {
        $order = Order::find(decode($id));

        $data = compact(
            'order',
            'link'
        );

        return view('pages/transaksi/in/perdagangan/print/kwitansi', $data);
    }
}
