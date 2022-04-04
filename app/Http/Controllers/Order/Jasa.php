<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\UserBaseController;
use App\Models\Barang as BarangModel;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Jasa extends UserBaseController
{
    public function index()
    {
        $breadcrumb = ['Stok Barang'];
        $list_barang = BarangModel::with('tarif')->orderByDesc('id')->get();
        return view('pages/barang/list', compact('breadcrumb', 'list_barang'));
    }

    public function add()
    {
        $breadcrumb = ['barang' => 'Stok Barang', 'Form Barang']; //url => title
        $form_title = 'Input Barang';
        return view('pages/barang/form', compact('breadcrumb', 'form_title'));
    }

    public function edit($id = null)
    {
        $barang = BarangModel::with('tarif')->find(decode($id));
        $breadcrumb = ['barang' => 'Stok Barang', 'Form Barang']; //url => title
        $form_title = 'Edit Barang';
        return view('pages/barang/form', compact('breadcrumb', 'form_title', 'barang'));
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

            $barang = BarangModel::updateOrCreate($exist_id, $data_barang);

            if ($barang_id == null) {
                $barang_id = $barang->id;
            }

            $data_tarif = [
                'unit_usaha_id' => 1,
                'nama_tarif'    => $request->nama_barang,
                'harga'         => rm_nominal($request->harga),
                'satuan_tarif'  => $request->satuan_barang,
            ];
            // $find_barang = BarangModel::find($barang->id);
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
            $deleted = BarangModel::find(decode($id))->delete();
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
