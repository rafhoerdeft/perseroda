<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Akun extends UserBaseController
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

        $list_data = User::latest()->get();
        $pegawai = Pegawai::whereNotIn('id', function ($query) {
            $query->select('pegawai_id')
                ->from('user')
                ->get();
        })->get();
        $role = Role::get();
        return view('pages/akun/list', compact(
            'breadcrumb',
            'form_title',
            'list_data',
            'pegawai',
            'role',
            'is_role',
            'main_route',
        ));
    }

    public function store(Request $request)
    {
        $validator = [
            'username' => 'required|string',
            'role_id' => 'required|string'
        ];

        if ($request->pegawai_id || !$request->user_id) {
            $validator['pegawai_id'] = 'required|string';
            $validator['password'] = 'required|string';
        } else {
            $validator['password'] = 'string|nullable';
        }

        $data = $request->validate($validator);

        try {
            if ($request->user_id && !$request->pegawai_id) {
                $id = decode($request->user_id);

                $save = User::find($id)->update($data);
            } else {
                $data['nama_user'] = Pegawai::find(decode($request->pegawai_id))->nama_pegawai;
                $data['pegawai_id'] = decode($request->pegawai_id);
                $save = User::create($data);
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
            $data_pegawai = User::find(decode($id));

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
