<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\UserBaseController;
use App\Models\Barang;
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
    public function index(Request $request)
    {
        $breadcrumb = ['Order Perdagangan'];
        $form_title = 'Input Order';

        $status_bayar_select = $request->status_bayar;
        $jenis_bayar_select = $request->jenis_bayar;

        $data = compact(
            'breadcrumb',
            'form_title',
            'status_bayar_select',
            'jenis_bayar_select',
        );

        return view('pages/order/perdagangan/list', $data);
    }

    public function getData($status = '', $jenis = '', Request $request)
    {
        $year = selected_year;

        if ($status == 'null') {
            $status = '';
        }

        if ($jenis == 'null') {
            $jenis = '';
        }

        if ($request->ajax()) {
            $status_bayar = [
                0 => 'Belum Bayar',
                1 => 'Lunas'
            ];

            $list_order = Order::whereHas('unit_usaha', function ($query) {
                $query->where('nama_unit_usaha', '=', 'perdagangan');
            })
                ->with('rincian_order')
                ->where('user_id', session('log_user_id'))
                ->whereYear('tgl_order', '=', $year)
                ->where([['status_bayar', 'LIKE', '%' . $status . '%'], ['jenis_bayar', 'LIKE', '%' . $jenis . '%']])
                ->orderByDesc('id')->get();

            $data_tables =  DataTables::of($list_order);
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
                $bg = 'success';
                if ($row->status_bayar == 0) {
                    $bg = 'danger';
                }
                return '<span class="badge rounded-pill bg-' . $bg . ' w-75">' . $status_bayar[$row->status_bayar] . '</span>';;
            });
            $raw_columns[] = 'status_bayar';

            $data_tables->editColumn('jenis_bayar', function ($row) {
                $bg = 'primary';
                if ($row->jenis_bayar == 'bank') {
                    $bg = 'info';
                }
                return '<span class="badge bg-' . $bg . ' w-75">' . text_uc($row->jenis_bayar) . '</span>';;
            });
            $raw_columns[] = 'jenis_bayar';

            if (in_array(session('log'), ['kasir', 'akuntansi'])) {
                $data_tables->addColumn('check_all', function ($row) {
                    $check = '<div class="skin skin-check">
                                        <input type="checkbox" name="select_row[]" id="select_row_' . $row->id . '"
                                            value="' . $row->id . '">
                                    </div>';
                    return $check;
                });

                $data_tables->addColumn('action', function ($row) {
                    $action_btn = '<button type="button" onclick="showDetail(this)" 
                                        data-nama_barang="' . $row->rincian_order->implode('tarif.barang.nama_barang', ';') . '"
                                        data-jml_order="' . $row->rincian_order->implode('jml_order', ';') . '"
                                        data-harga="' . $row->rincian_order->implode('tarif.harga', ';') . '"
                                        data-satuan="' . $row->rincian_order->implode('tarif.barang.satuan_barang', ';') . '"
                                        data-biaya_tambahan="' . $row->rincian_order->implode('biaya_tambahan', ';') . '"
                                        class="btn btn-sm btn-primary" title="Rincian Order">
                                        <i class="lni lni-list me-0 font-sm"></i>
                                    </button>
                                    <a href="' . route('order.perdagangan.edit', ['id' => encode($row->id)]) . '"
                                        class="btn btn-info btn-sm" title="Update Data">
                                        <i class="lni lni-pencil-alt me-0 text-white font-sm"></i>
                                    </a>
                                    <button type="button" onclick="deleteData(this, false)" 
                                        data-id="' . encode($row->id) . '"
                                        data-link="' . url('order/perdagangan/delete') . '"
                                        data-table="list_data"
                                        class="btn btn-sm btn-danger" title="Hapus Data">
                                        <i class="lni lni-trash me-0 font-sm"></i>
                                    </button>';
                    return $action_btn;
                });
                $raw_columns[] = 'check_all';
                $raw_columns[] = 'action';
            }
            $data_tables->rawColumns($raw_columns);

            return $data_tables->make(true);
        }
    }

    public function add()
    {
        $no_order = $this->nomorOrder()['no_order'];
        $year = selected_year;

        $breadcrumb = ['order/perdagangan' => 'Order Perdagangan', 'Form Order']; //url => title
        $form_title = 'Input Order - ' . $no_order;
        return view('pages/order/perdagangan/form', compact('breadcrumb', 'form_title', 'year'));
    }

    public function getBarang(Request $request)
    {
        try {
            if ($request->id) {
                $barang = Barang::with('tarif:id,harga,barang_id')->find($request->id);
                if (!$barang) {
                    throw new \Exception('Data barang tidak ditemukan.');
                }
                $res = ['response' => true, 'result' => $barang];
            } else {
                $limit = 10;
                $barang = Barang::with('tarif:id,harga,barang_id')->select(['id', 'nama_barang', 'kode_barang'])->whereHas('tarif', function ($query) {
                    $query->whereHas('unit_usaha', function ($query) {
                        $query->where('nama_unit_usaha', 'perdagangan');
                    });
                })->where('stok_barang', '>', 0)->where(function ($query) use ($request) {
                    $query->where('kode_barang', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('nama_barang', 'LIKE', '%' . $request->search . '%');
                });

                $count = $barang->count();
                $result = $barang->offset((($request->page - 1) * $limit))->limit($limit)->get();

                if (!$result) {
                    throw new \Exception('Gagal mengambil data barang.');
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

    public function edit($id = null)
    {
        $barang = Order::with('tarif')->find(decode($id));
        $breadcrumb = ['barang' => 'Stok Barang', 'Form Barang']; //url => title
        $form_title = 'Edit Barang';
        return view('pages/order/perdagangan/form', compact('breadcrumb', 'form_title', 'barang'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'tgl_order'  => 'required|date_format:d/m/Y',
            'nama_klien'  => 'string|nullable',
            'jenis_bayar'  => 'required|in:tunai,bank',
            'status_bayar'  => 'required|numeric|between:0,1',
            'rincian_barang'  => 'required|string',
            'total_bayar'  => 'required|integer',
            // 'harga'  => 'required|regex:/^[0-9\.,]+$/|not_in:0'
        ]);

        DB::beginTransaction();
        try {
            $data_order = [
                'user_id'   => session('log_user_id'),
                'nama_klien'  => $request->nama_klien,
                // 'no_hp_klien'  => $request->no_hp_klien,
                'tgl_order' => re_date_format($request->tgl_order),
                'status_order' => 3,
                // 'jenis_order' => $request->jenis_order,
                'status_bayar' => $request->status_bayar,
                'jenis_bayar' => $request->jenis_bayar,
                'total_bayar' => $request->total_bayar,
            ];

            if ($request->order_id) {
                $order_id = decode($request->order_id);
            } else {
                $order_id = null;
                $no_order = $this->nomorOrder(true);
                if ($no_order === false) {
                    throw new \Exception("Gagal simpan nomor order.");
                }
                $data_order['unit_usaha_id']   = $no_order['unit_usaha_id'];
                $data_order['no_order']   = $no_order['no_order'];
            }

            $exist_id = ['id' => $order_id];

            $order = Order::updateOrCreate($exist_id, $data_order);

            if ($order_id == null) {
                $order_id = $order->id;
            } else {
                RincianOrder::where('order_id', $order_id)->delete();
            }

            $rincian_barang = json_decode($request->rincian_barang);

            $data_rincian = [];
            foreach ($rincian_barang as $tarif_id => $jml) {
                $data_rincian[] = [
                    'order_id'      => $order_id,
                    'tarif_id'      => $tarif_id,
                    'jml_order'     => $jml,
                ];
            }

            RincianOrder::upsert($data_rincian, ['order_id', 'tarif_id'], ['jml_order']);

            DB::commit();
            alert_success('Data order berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            alert_failed('Data order gagal disimpan.' . $e->getMessage());
        }
        return redirect('order/perdagangan/add');
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

            $res = ['success' => true];
        } catch (\Exception $e) {
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
}
