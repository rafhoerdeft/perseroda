<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use App\Models\Rekanan as ModelRekanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Rekanan extends UserBaseController
{
    protected $is_role;

    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            $this->is_role = false;
            $role = ['bendahara', 'akuntansi'];
            if (in_array(auth()->user()->role->nama_role, $role)) {
                $this->is_role = true;
            }
            return $next($request);
        });
    }

    public function index()
    {
        $is_role = $this->is_role;
        $breadcrumb = ['Rekanan'];
        $list_data = ModelRekanan::latest()->get();
        return view('pages/rekanan/list', compact('is_role', 'breadcrumb', 'list_data'));
    }

    public function add()
    {
        $breadcrumb = ['rekanan' => 'Data Rekanan', 'Form Rekanan']; //url => title
        $form_title = 'Input Rekanan';
        return view('pages/rekanan/form', compact('breadcrumb', 'form_title'));
    }

    public function edit($id = null)
    {
        $rekanan = ModelRekanan::find(decode($id));
        $breadcrumb = ['rekanan' => 'Data Rekanan', 'Form Rekanan']; //url => title
        $form_title = 'Edit Rekanan';
        return view('pages/rekanan/form', compact('breadcrumb', 'form_title', 'rekanan'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'nama'    => 'required|string',
            'alamat'  => 'string|nullable',
        ]);

        DB::beginTransaction();
        try {

            $data_rekanan = [
                'nama'     => $request->nama,
                'alamat'   => $request->alamat,
            ];

            if ($request->rekanan_id) {
                $rekanan_id = decode($request->rekanan_id);
            } else {
                $rekanan_id = null;
            }

            $exist_id = ['id' => $rekanan_id];

            $rekanan = ModelRekanan::updateOrCreate($exist_id, $data_rekanan);

            DB::commit();
            alert_success('Data rekanan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            alert_failed('Data rekanan gagal disimpan.' . $e->getMessage());
        }
        return redirect('rekanan');
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), ['id' => 'required']);

            if ($validator->fails()) {
                throw new \Exception($validator->errors());
            }

            $id = $request->id;
            $deleted = ModelRekanan::find(decode($id))->delete();
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
