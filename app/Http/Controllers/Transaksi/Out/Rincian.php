<?php

namespace App\Http\Controllers\Transaksi\Out;

use App\Http\Controllers\UserBaseController;
use App\Models\Nota;
use App\Models\Produk;
use App\Models\RincianNota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
// use Yajra\DataTables\DataTables;

class Rincian extends UserBaseController
{
    protected $is_role;

    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            $this->is_role = false;
            $role = ['bendahara'];
            if (in_array(Auth::user()->role->nama_role, $role)) {
                $this->is_role = true;
            }
            return $next($request);
        });
    }

    public function index($id = null)
    {
        $nota_id = decode($id);
        $nota = Nota::find($nota_id);

        $breadcrumb = ['transaksi/out/nota' => "Nota Pembelian ({$nota->no_nota})", 'Rincian'];
        $form_title = 'Input Rincian';

        $is_role = $this->is_role;

        $main_route = 'transaksi.out.nota.rincian.';

        $list_data = RincianNota::with('produk')->latest()->get();

        $data = compact(
            'breadcrumb',
            'form_title',
            'is_role',
            'main_route',
            'list_data',
            'nota_id'
        );

        return view('pages/transaksi/out/rincian/list', $data);
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
                $result = $produk->selectRaw("CONCAT(nama_produk, ' - ', kode_produk) AS text")
                    ->offset((($request->page - 1) * $limit))
                    ->limit($limit)->get();

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

    public function save(Request $request)
    {
        $request->validate([
            'nota_id'  => 'required',
            'produk'  => 'required|integer',
            'harga_produk'  => 'required|regex:/^[0-9\.,]+$/|not_in:0',
            'jml_produk'  => 'required|regex:/^[0-9\.,]+$/|not_in:0',
        ]);

        DB::beginTransaction();
        try {
            $data_rincian = [
                'nota_id'   => decode($request->nota_id),
                'produk_id' => $request->produk,
                'harga_produk' => rm_nominal($request->harga_produk),
                'jml_produk' => rm_nominal($request->jml_produk),
            ];

            if ($request->rincian_nota_id) {
                $rincian_nota_id = decode($request->rincian_nota_id);
            } else {
                $rincian_nota_id = null;
            }

            if ($rincian_nota_id == null) {
                $rincian_nota = RincianNota::create($data_rincian);
                Produk::find($request->produk)->increment('stok_produk', rm_nominal($request->jml_produk));
            } else {
                $data_rincian_nota = RincianNota::find($rincian_nota_id);
                $produk_id = $data_rincian_nota->produk_id;
                $jml_produk = $data_rincian_nota->jml_produk;
                Produk::find($produk_id)->decrement('stok_produk', $jml_produk); // reduce stok produk previous update
                Produk::find($request->produk)->increment('stok_produk', rm_nominal($request->jml_produk)); // add stok produk
                $rincian_nota = $data_rincian_nota->update($data_rincian);
            }

            if (!$rincian_nota) {
                throw new \Exception("Gagal simpan data rincian nota");
            }

            DB::commit();
            alert_success('Data rincian nota berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            alert_failed('Data rincian nota gagal disimpan.' . $e->getMessage());
        }

        return back();
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), ['id' => 'required']);

            if ($validator->fails()) {
                throw new \Exception($validator->errors());
            }

            $id = $request->id;
            $data_rincian = RincianNota::find(decode($id));

            $produk_id = $data_rincian->produk_id;
            $jml_produk = $data_rincian->jml_produk;
            Produk::find($produk_id)->decrement('stok_produk', $jml_produk); // reduce stok produk 

            $deleted = $data_rincian->delete();

            if (!$deleted) {
                throw new \Exception('Gagal hapus data!');
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

            $rincian = $query->get();

            foreach ($rincian as $val) {
                $produk_id = $val->produk_id;
                $jml_produk = $val->jml_produk;
                Produk::find($produk_id)->decrement('stok_produk', $jml_produk);
            }

            if ($request->soft === 'true') {
                $deleted = $query->update(['deleted_at' => now()]);
            } else {
                $deleted = $query->delete();
            }
            if (!$deleted) {
                throw new \Exception('Gagal hapus data!');
            }

            DB::commit();
            $res = ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            $res = ['success' => false, 'alert' => $e->getMessage()];
        }

        return json_encode($res);
    }
}
