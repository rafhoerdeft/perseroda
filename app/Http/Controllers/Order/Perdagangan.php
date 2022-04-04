<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\UserBaseController;
use App\Models\Barang;
use App\Models\NoOrder;
use App\Models\Order;
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
                return date('d/m/Y - H:i', strtotime($row->tgl_order));
            });
            $data_tables->addColumn('total', function ($row) {
                return nominal(
                    $row->rincian_order->sum(function ($item) {
                        return $item->jml_order * $item->tarif->harga + $item->biaya_tambahan;
                    })
                );
            });
            $data_tables->editColumn('status_bayar', function ($row) use ($status_bayar) {
                return $status_bayar[$row->status_bayar];
            });

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
        $no_order = $this->nomorOrder();
        $year = selected_year;

        $breadcrumb = ['order/perdagangan' => 'Order Perdagangan', 'Form Order']; //url => title
        $form_title = 'Input Order - ' . $no_order;
        return view('pages/order/perdagangan/form', compact('breadcrumb', 'form_title', 'year'));
    }

    public function getBarang(Request $request)
    {
        try {
            if ($request->id) {
                $barang = Barang::with('tarif:harga,barang_id')->find($request->id);
                if (!$barang) {
                    throw new \Exception('Data barang tidak ditemukan.');
                }
                $res = ['response' => true, 'result' => $barang];
            } else {
                $limit = 10;
                $barang = Barang::with('tarif:harga,barang_id')->select(['id', 'nama_barang', 'kode_barang'])->whereHas('tarif', function ($query) {
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
        $this->validate($request, [
            'nama_barang'  => 'required|string',
            'stok_barang'  => 'required|integer',
            'stok_minimal'  => 'required|integer',
            'satuan_barang'  => 'nullable',
            'harga'  => 'required|regex:/^[0-9\.,]+$/|not_in:0'
        ]);

        DB::beginTransaction();
        try {

            $data_barang = [
                'nama_barang'   => $request->nama_barang,
                'stok_barang'   => $request->stok_barang,
                'stok_minimal'  => $request->stok_minimal,
                'satuan_barang' => $request->satuan_barang,
            ];

            if ($request->barang_id) {
                $barang_id = decode($request->barang_id);
            } else {
                $barang_id = null;
                $data_barang['kode_barang'] = auto_code('kode_barang', 'barang', 'BR', 4);
            }

            $exist_id = ['id' => $barang_id];

            $barang = Order::updateOrCreate($exist_id, $data_barang);

            if ($barang_id == null) {
                $barang_id = $barang->id;
            }

            $data_tarif = [
                'unit_usaha_id' => 1,
                'nama_tarif'    => $request->nama_barang,
                'harga'         => rm_nominal($request->harga),
                'satuan_tarif'  => $request->satuan_barang,
            ];
            // $find_barang = Order::find($barang->id);
            // $find_barang->tarif()->create($data_tarif);

            $exist_id = ['barang_id' => $barang_id];
            Tarif::updateOrCreate($exist_id, $data_tarif);

            DB::commit();
            alert_success('Data barang berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            alert_failed('Data barang gagal disimpan.' . $e->getMessage());
        }
        return redirect('barang');
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

        return $nomor_order;
    }
}
