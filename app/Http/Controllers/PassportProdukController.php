<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PassportProdukController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Produk::with('tarif:harga,produk_id')->get()->toArray(),
        ]);
    }

    public function show($id)
    {
        try {
            $produk = Produk::with('tarif:harga,produk_id')->find($id);
            if (!$produk) {
                throw new \Exception('Produk tidak ditemukan!');
            }

            $res = ['success' => true, 'data' => $produk->toArray()];
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => $e->getMessage()];
        }

        return response()->json($res);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'nama_produk'  => 'required|string',
                'stok_produk'  => 'required|integer',
                'stok_minimal'  => 'required|integer',
                'satuan_produk'  => 'nullable',
                'harga'  => 'required|regex:/^[0-9\.,]+$/|not_in:0'
            ]);

            if ($validator->fails()) {
                throw new \Exception(json_encode($validator->errors()));
            }

            $data_produk = [
                'nama_produk'   => $request->nama_produk,
                'stok_produk'   => $request->stok_produk,
                'stok_minimal'  => $request->stok_minimal,
                'satuan_produk' => $request->satuan_produk,
            ];

            if ($request->produk_id) {
                $produk_id = decode($request->produk_id);
            } else {
                $produk_id = null;
                $kode_produk = auto_code('kode_produk', 'produk', 'PD', 4);
                $data_produk['kode_produk'] = $kode_produk;
                $data_produk['barcode'] = 0 . date('ymd') . $kode_produk . random_int(0, 9);
            }

            $exist_id = ['id' => $produk_id];

            $produk = Produk::updateOrCreate($exist_id, $data_produk);

            if ($produk_id == null) {
                $produk_id = $produk->id;
            }

            $data_tarif = [
                'nama_tarif'    => $request->nama_produk,
                'harga'         => rm_nominal($request->harga),
                'satuan_tarif'  => $request->satuan_produk,
            ];

            $exist_id = ['produk_id' => $produk_id];
            Tarif::updateOrCreate($exist_id, $data_tarif);

            DB::commit();
            $res = ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            $res = ['success' => false, 'message' => $e->getMessage()];
        }
        return response()->json($res);
    }

    public function update(Request $request, $id = null)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'nama_produk'  => 'required|string',
                'stok_produk'  => 'required|integer',
                'stok_minimal'  => 'required|integer',
                'satuan_produk'  => 'nullable',
                'harga'  => 'required|regex:/^[0-9\.,]+$/|not_in:0'
            ]);

            if ($validator->fails()) {
                throw new \Exception(json_encode($validator->errors()));
            }

            $data_produk = [
                'nama_produk'   => $request->nama_produk,
                'stok_produk'   => $request->stok_produk,
                'stok_minimal'  => $request->stok_minimal,
                'satuan_produk' => $request->satuan_produk,
            ];

            if ($id != null) {
                $produk_id = $id;
            } else {
                $produk_id = null;
                $kode_produk = auto_code('kode_produk', 'produk', 'PD', 4);
                $data_produk['kode_produk'] = $kode_produk;
                $data_produk['barcode'] = 0 . date('ymd') . $kode_produk . random_int(0, 9);
            }

            $exist_id = ['id' => $produk_id];

            $produk = Produk::updateOrCreate($exist_id, $data_produk);

            if ($produk_id == null) {
                $produk_id = $produk->id;
            }

            $data_tarif = [
                'nama_tarif'    => $request->nama_produk,
                'harga'         => rm_nominal($request->harga),
                'satuan_tarif'  => $request->satuan_produk,
            ];

            $exist_id = ['produk_id' => $produk_id];
            Tarif::updateOrCreate($exist_id, $data_tarif);

            DB::commit();
            $res = ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            $res = ['success' => false, 'message' => $e->getMessage()];
        }
        return response()->json($res);
    }

    public function destroy($id = null)
    {
        try {
            $produk = Produk::find($id);
            if (!$produk) {
                throw new \Exception('Produk tidak ditemukan!');
            }

            if (!$produk->delete()) {
                throw new \Exception('Gagal hapus data!');
            }

            $res = ['success' => true];
        } catch (\Exception $e) {
            $res = ['success' => false, 'alert' => $e->getMessage()];
        }

        return json_encode($res);
    }
}
