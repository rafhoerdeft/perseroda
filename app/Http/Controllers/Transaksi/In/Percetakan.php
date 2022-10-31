<?php

namespace App\Http\Controllers\Transaksi\In;

use App\Http\Controllers\UserBaseController;
use App\Models\Produk;
use App\Models\NoOrder;
use App\Models\Order;
use App\Models\RincianCetakan;
// use App\Models\RincianOrder;
use App\Models\UnitUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class Percetakan extends UserBaseController
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
        $breadcrumb = ['Transaksi Percetakan'];
        $form_title = 'Input Transaksi';

        $status_bayar_select = $request->status_bayar;
        $jenis_bayar_select = $request->jenis_bayar;

        $is_role = $this->is_role;

        $main_route = 'transaksi.in.percetakan.';

        $link_datatable = url('transaksi/in/percetakan/data/' . $status_bayar_select . '/' . $jenis_bayar_select);

        $data = compact(
            'breadcrumb',
            'form_title',
            'is_role',
            'status_bayar_select',
            'jenis_bayar_select',
            'main_route',
            'link_datatable',
        );

        return view('pages/transaksi/in/percetakan/list', $data);
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
                $query->where('nama_unit_usaha', '=', 'percetakan');
            });
            // ->with('rincian_order')
            $list_order->with('rincian_cetakan');
            if ($this->is_role) {
                $list_order->where('user_id', auth()->user()->id);
            }
            $list_order->whereYear('tgl_order', '=', $year);
            $list_order->where([['status_bayar', 'LIKE', '%' . $status . '%'], ['jenis_bayar', 'LIKE', '%' . $jenis . '%']]);
            $list_order->latest()->get();

            $data_tables = DataTables::of($list_order);
            $raw_columns = [];

            $data_tables->addIndexColumn();
            $data_tables->editColumn('tgl_order', function ($row) {
                return date('d/m/Y', strtotime($row->tgl_order));
            });

            // $data_tables->addColumn('total', function ($row) {
            //     return nominal(
            //         $row->rincian_order->sum(function ($item) {
            //             return $item->jml_order * $item->tarif->harga + $item->biaya_tambahan;
            //         })
            //     );
            // });

            $data_tables->addColumn('dasar_jenis', function ($row) {
                $bg = 'warning';
                if ($row->rincian_cetakan->dasar_jenis == 'lesan') {
                    $bg = 'secondary';
                }
                return '<span class="badge rounded-pill bg-' . $bg . ' w-75">' . ucfirst($row->rincian_cetakan->dasar_jenis) . '</span>';
            });
            $raw_columns[] = 'dasar_jenis';

            $data_tables->editColumn('status_bayar', function ($row) use ($status_bayar) {
                if ($row->status_bayar == 0) {
                    $bg = 'danger';
                    $col_sts_byr =  '<span class="badge rounded-pill bg-' . $bg . ' w-75">' . $status_bayar[$row->status_bayar] . '</span>';
                    if ($this->is_role) {
                        $col_sts_byr .= "<div class='mt-1'>
                                            <button type='button' class='btn btn-sm btn-warning text-sm-b p-1 w-75' 
                                            data-post='" . json_encode(['id' => encode($row->id)]) . "'
                                            data-link='" . url('transaksi/in/percetakan/change/statusbayar') . "'
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
                                    data-link='" . url('transaksi/in/percetakan/change/jenisbayar') . "'
                                    data-table='list_data' 
                                    data-title='Ubah jenis bayar (" . $row->no_order . ")'
                                    data-text='Jenis bayar akan diubah menjadi Bank?'
                                    onclick='" . ($row->jenis_bayar == 'bank' ? '' : 'confirmDialog(this, false)') . "'>Bank</a>

                                    <a class='dropdown-item " . ($row->jenis_bayar == 'tunai' ? 'active' : '') . "' href='javascript:void(0);' data-post='" . json_encode(['id' => encode($row->id), 'jenis' => 'tunai']) . "' 
                                    data-link='" . url('transaksi/in/percetakan/change/jenisbayar') . "'
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
                                    data-dasar_jenis="' . text_uc($row->rincian_cetakan->dasar_jenis) . '"
                                    data-dasar_tgl="' . format_date($row->rincian_cetakan->dasar_tgl) . '"
                                    data-dasar_nomor="' . $row->rincian_cetakan->dasar_nomor . '"
                                    data-dasar_oleh="' . $row->rincian_cetakan->dasar_oleh . '"
                                    data-tgl_selesai="' . format_date($row->rincian_cetakan->tgl_selesai) . '"
                                    data-lampiran_konsep="' . $row->rincian_cetakan->lampiran_konsep . '"
                                    data-koordinator_konsep_tgl="' . format_date($row->rincian_cetakan->koordinator_konsep_tgl) . '"
                                    data-koordinator_konsep_nama="' . $row->rincian_cetakan->koordinator_konsep_nama . '"
                                    data-lain_lain="' . $row->rincian_cetakan->lain_lain . '"
                                    data-jenis_pesanan="' . $row->rincian_cetakan->jenis_pesanan . '"
                                    data-jml_pesanan="' . $row->rincian_cetakan->jml_pesanan . '"
                                    data-jenis_bahan="' . $row->rincian_cetakan->jenis_bahan . '"
                                    data-ukuran_isi="' . $row->rincian_cetakan->ukuran_isi . '"
                                    data-warna_tinta="' . $row->rincian_cetakan->warna_tinta . '"
                                    data-gramatur="' . $row->rincian_cetakan->gramatur . '"
                                    data-muka_halaman="' . $row->rincian_cetakan->muka_halaman . '"
                                    data-pakai_nomor="' . $row->rincian_cetakan->pakai_nomor . '"
                                    data-mulai_nomor="' . $row->rincian_cetakan->mulai_nomor . '"
                                    data-finishing_lepas="' . $row->rincian_cetakan->finishing_lepas . '"
                                    data-finishing_lem="' . $row->rincian_cetakan->finishing_lem . '"
                                    data-finishing_jilid="' . $row->rincian_cetakan->finishing_jilid . '"
                                    data-finishing_paku="' . $row->rincian_cetakan->finishing_paku . '"
                                    data-finishing_perforasi="' . $row->rincian_cetakan->finishing_perforasi . '"
                                    data-ket_cetakan="' . $row->rincian_cetakan->ket_cetakan . '"
                                    class="btn btn-sm btn-primary" title="Rincian Order">
                                    <i class="lni lni-list me-0 font-sm"></i>
                                </button> ';
                $btn_update = '<a href="' . route('transaksi.in.percetakan.edit', ['id' => encode($row->id)]) . '"
                                    class="btn btn-info btn-sm" title="Update Data">
                                    <i class="lni lni-pencil-alt me-0 text-white font-sm"></i>
                                </a> ';
                $btn_delete = '<button type="button" onclick="deleteData(this, false)" 
                                    data-id="' . encode($row->id) . '"
                                    data-link="' . url('transaksi/in/percetakan/delete') . '"
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
                    //     $query->where('nama_unit_usaha', 'percetakan');
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
        $main_route = 'transaksi.in.percetakan.';

        $breadcrumb = ['transaksi/in/percetakan' => 'Transaksi Percetakan', 'Form Input']; //url => title
        $form_title = 'Input Transaksi - ' . $no_order;
        return view('pages/transaksi/in/percetakan/form', compact(
            'breadcrumb',
            'form_title',
            'year',
            'main_route',
        ));
    }

    public function edit($id = null)
    {
        $year = selected_year;
        $order = Order::with('rincian_cetakan')->find(decode($id));
        $main_route = 'transaksi.in.percetakan.';

        $no_order = $order->no_order;
        $breadcrumb = ['transaksi/in/percetakan' => 'Transaksi Percetakan', 'Form Input']; //url => title
        $form_title = 'Edit Transaksi - ' . $no_order;
        return view('pages/transaksi/in/percetakan/form', compact(
            'breadcrumb',
            'form_title',
            'order',
            'year',
            'main_route'
        ));
    }

    public function save(Request $request)
    {
        $validate = [
            'tgl_order'  => 'required|date_format:d/m/Y',
            'nama_klien'  => 'string|nullable',
            'jenis_bayar'  => 'required|in:tunai,bank',
            // 'status_bayar'  => 'required|numeric|between:0,1',
            // 'rincian_produk'  => 'required|string',
            // 'total_bayar'  => 'required|integer',
            // 'harga'  => 'required|regex:/^[0-9\.,]+$/|not_in:0',
            // =========================================================
            // Rincian Cetakan 
            // =========================================================
            'dasar_jenis' => 'required|in:lesan,surat',
            'dasar_tgl'  => 'required|date_format:d/m/Y',
            // 'dasar_nomor'  => 'string|nullable',
            // 'dasar_oleh'  => 'string|nullable',
            'tgl_selesai'  => 'required|date_format:d/m/Y',
            'lampiran_konsep'  => 'required|numeric|between:0,1',
            'koordinator_konsep_tgl'  => 'date_format:d/m/Y|nullable',
            'koordinator_konsep_nama'  => 'string|nullable',
            'lain_lain'  => 'string|nullable',
            'jenis_pesanan'  => 'required|string',
            'jml_pesanan'  => 'required|string',
            'jenis_bahan'  => 'required|string',
            'ukuran_isi'  => 'required|string',
            'warna_tinta'  => 'required|string',
            'gramatur'  => 'required|string',
            'muka_halaman'  => 'required|string',
            'pakai_nomor'  => 'required|numeric|between:0,1',
            'finishing_lepas'  => 'numeric|between:0,1',
            'finishing_lem'  => 'numeric|between:0,1',
            'finishing_jilid'  => 'numeric|between:0,1',
            'finishing_paku'  => 'numeric|between:0,1',
            'finishing_perforasi'  => 'numeric|between:0,1',
            'ket_cetakan'  => 'string|nullable',
        ];

        $validate['mulai_nomor'] = 'numeric|nullable';
        if ($request->pakai_nomor == '1') {
            $validate['mulai_nomor'] = 'required|numeric';
        }

        if ($request->dasar_jenis == 'lesan') {
            $validate['dasar_oleh'] = 'required|string';
        } else {
            $validate['dasar_nomor'] = 'required|string';
        }

        $request->validate($validate);

        DB::beginTransaction();
        try {
            $data_order = [
                'user_id'   => auth()->user()->id,
                'nama_klien'  => $request->nama_klien,
                // 'no_hp_klien'  => $request->no_hp_klien,
                'tgl_order' => re_date_format($request->tgl_order),
                'status_order' => 0,
                'jenis_order' => ($request->dasar_jenis == 'lesan' ? 'langsung' : 'dokumen'),
                // 'status_bayar' => $request->status_bayar,
                'jenis_bayar' => $request->jenis_bayar,
                // 'total_bayar' => $request->total_bayar,
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

            $data_rincian_cetakan = [
                'order_id' => $order_id,
                'dasar_jenis' => $request->dasar_jenis,
                'dasar_tgl'  => re_date_format($request->dasar_tgl),
                'dasar_nomor'  => $request->dasar_nomor,
                'dasar_oleh'  => $request->dasar_oleh,
                'tgl_selesai'  => re_date_format($request->tgl_selesai),
                'lampiran_konsep'  => $request->lampiran_konsep,
                'koordinator_konsep_tgl'  => $request->koordinator_konsep_tgl != null ? re_date_format($request->koordinator_konsep_tgl) : null,
                'koordinator_konsep_nama'  => $request->koordinator_konsep_nama,
                'lain_lain'  => $request->lain_lain,
                'jenis_pesanan'  => $request->jenis_pesanan,
                'jml_pesanan'  => $request->jml_pesanan,
                'jenis_bahan'  => $request->jenis_bahan,
                'ukuran_isi'  => $request->ukuran_isi,
                'warna_tinta'  => $request->warna_tinta,
                'gramatur'  => $request->gramatur,
                'muka_halaman'  => $request->muka_halaman,
                'pakai_nomor'  => $request->pakai_nomor,
                'mulai_nomor'  => $request->mulai_nomor,
                'finishing_lepas'  => ($request->finishing_lepas ?? 0),
                'finishing_lem'  => ($request->finishing_lem ?? 0),
                'finishing_jilid'  => ($request->finishing_jilid ?? 0),
                'finishing_paku'  => ($request->finishing_paku ?? 0),
                'finishing_perforasi'  => ($request->finishing_perforasi ?? 0),
                'ket_cetakan'  => $request->ket_cetakan,
            ];

            $rincian_cetakan = RincianCetakan::where('order_id', '=', $order_id);
            if ($rincian_cetakan->count() > 0) { // Check if data exist
                $save_rincian = $rincian_cetakan->update($data_rincian_cetakan);
            } else {
                $save_rincian = RincianCetakan::create($data_rincian_cetakan);
            }

            if (!$save_rincian) {
                throw new \Exception("Gagal simpan rincian cetakan");
            }

            DB::commit();
            alert_success('Data order berhasil disimpan.');

            if ($request->order_id) {
                return redirect('transaksi/in/percetakan');
            } else {
                return redirect('transaksi/in/percetakan/add');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            alert_failed('Data order gagal disimpan.' . $e->getMessage());
            return back()->withInput();
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

            $res = ['success' => true];
        } catch (\Exception $e) {
            $res = ['success' => false, 'alert' => $e->getMessage()];
        }

        return json_encode($res);
    }

    public function nomorOrder($save = false)
    {
        $year = selected_year;

        $unit_usaha = UnitUsaha::where('nama_unit_usaha', '=', 'percetakan')->first();

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
}
