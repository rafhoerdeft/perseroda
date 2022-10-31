<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class Password extends Controller
{
    public function index()
    {
        $breadcrumb = ['Password Utama'];
        $form_title = 'Konfigurasi Password';
        $config = Config::latest()->first();
        return view('pages/config/password', compact('breadcrumb', 'form_title', 'config'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'default_password' => 'required|string|max:25'
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
