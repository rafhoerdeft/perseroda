<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class Periode extends Controller
{
    public function index()
    {
        $breadcrumb = ['Periode Aktif'];
        $form_title = 'Konfigurasi Periode';
        $thn_periode = range('2019', date('Y'));
        rsort($thn_periode);
        $config = Config::latest()->first();
        return view('pages/config/periode', compact('breadcrumb', 'form_title', 'config', 'thn_periode'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'active_period' => 'required|integer|digits:4'
        ]);

        try {
            $id = decode($request->config_id);

            $update = Config::find($id)->update($data);

            if (!$update) {
                throw new \Exception("Gagal update data!");
            }

            alert_success('Data berhasil disimpan.');
        } catch (\Exception $e) {
            alert_failed('Data gagal disimpan.' . json_check($e->getMessage()));
        }

        return back();
    }
}
