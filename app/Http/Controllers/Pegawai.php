<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Pegawai as ModelsPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Pegawai extends UserBaseController
{
    protected $is_role;

    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            $this->is_role = false;
            $role = ['admin'];
            if (in_array(auth('pdau')->user()->role->nama_role, $role)) {
                $this->is_role = true;
            }
            return $next($request);
        });
    }

    public function index()
    {
        $breadcrumb = ['Pegawai'];
        $form_title = 'Input Pegawai';

        $is_role = $this->is_role;
        $main_route = 'pegawai.';

        $list_data = ModelsPegawai::with('jabatan')->latest()->get();
        $jabatan = Jabatan::get();
        return view('pages/pegawai/list', compact(
            'breadcrumb',
            'form_title',
            'list_data',
            'jabatan',
            'is_role',
            'main_route',
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pegawai' => 'required|regex:/^[a-z\s]+$/i', // only alphabet and white space
            'nip' => 'nullable|regex:/^[0-9\s]+$/', // only numeric and white space
            'pangkat' => 'nullable|regex:/^[a-z\s]+$/i', // only alphabet and white space
            'jabatan_id' => 'required|string'
        ]);

        try {
            if ($request->pegawai_id) {
                $id = decode($request->pegawai_id);

                $save = ModelsPegawai::find($id)->update($data);
            } else {
                $save = ModelsPegawai::create($data);
            }

            if (!$save) {
                throw new \Exception("Gagal simpan data!");
            }

            alert_success('Data berhasil disimpan.');
        } catch (\Exception $e) {
            alert_failed('Data gagal disimpan.' . json_check($e->getMessage()));
        }

        return back();
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), ['id' => 'required']);

            if ($validator->fails()) {
                throw new \Exception($validator->errors());
            }

            $id = $request->id;
            $data_pegawai = ModelsPegawai::find(decode($id));

            $deleted = $data_pegawai->delete();

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

            DB::commit();
            $res = ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            $res = ['success' => false, 'alert' => $e->getMessage()];
        }

        return json_encode($res);
    }
}
