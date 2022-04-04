<?php

namespace App\Http\Controllers;

// use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class All extends Controller
{
    public function delete(Request $request)
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
