<?php

namespace App\Http\Controllers;

use App\Models\Produk as ProdukModel;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Produk extends UserBaseController
{
    public function index()
    {
        $breadcrumb = ['Stok Produk'];
        $list_produk = ProdukModel::with('tarif:harga,produk_id')->orderByDesc('id')->get();
        return view('pages/produk/list', compact('breadcrumb', 'list_produk'));
    }

    public function add()
    {
        $breadcrumb = ['produk' => 'Stok Produk', 'Form Produk']; //url => title
        $form_title = 'Input Produk';
        return view('pages/produk/form', compact('breadcrumb', 'form_title'));
    }

    public function edit($id = null)
    {
        $produk = ProdukModel::with('tarif:harga,produk_id')->find(decode($id));
        $breadcrumb = ['produk' => 'Stok Produk', 'Form Produk']; //url => title
        $form_title = 'Edit Produk';
        return view('pages/produk/form', compact('breadcrumb', 'form_title', 'produk'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'nama_produk'  => 'required|string',
            'stok_produk'  => 'required|integer',
            'stok_minimal'  => 'required|integer',
            'satuan_produk'  => 'nullable',
            'harga'  => 'required|regex:/^[0-9\.,]+$/|not_in:0'
        ]);

        DB::beginTransaction();
        try {

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

            $produk = ProdukModel::updateOrCreate($exist_id, $data_produk);

            if ($produk_id == null) {
                $produk_id = $produk->id;
            }

            $data_tarif = [
                // 'unit_usaha_id' => 1,
                'nama_tarif'    => $request->nama_produk,
                'harga'         => rm_nominal($request->harga),
                'satuan_tarif'  => $request->satuan_produk,
            ];
            // $find_produk = ProdukModel::find($produk->id);
            // $find_produk->tarif()->create($data_tarif);

            $exist_id = ['produk_id' => $produk_id];
            Tarif::updateOrCreate($exist_id, $data_tarif);

            DB::commit();
            alert_success('Data produk berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            alert_failed('Data produk gagal disimpan.' . $e->getMessage());
        }
        return redirect('produk');
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), ['id' => 'required']);

            if ($validator->fails()) {
                throw new \Exception($validator->errors());
            }

            $id = $request->id;
            $deleted = ProdukModel::find(decode($id))->delete();
            if (!$deleted) {
                throw new \Exception('Gagal hapus data!');
            }

            $res = ['success' => true];
        } catch (\Exception $e) {
            $res = ['success' => false, 'alert' => $e->getMessage()];
        }

        return json_encode($res);
    }
}
