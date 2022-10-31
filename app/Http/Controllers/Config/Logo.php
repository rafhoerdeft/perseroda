<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\UserBaseController;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class Logo extends UserBaseController
{
    public function index()
    {
        $breadcrumb = ['Logo Aplikasi'];
        $form_title = 'Konfigurasi Logo';
        $config = Config::latest()->first();
        return view('pages/config/logo', compact('breadcrumb', 'form_title', 'config'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'logo'  => 'required|file|max:110',
        //     'app_name'  => 'required|string|max:50',
        // ]);

        $validate = [
            'app_name'  => 'required|string|max:50',
        ];

        if ($request->logo_old != null) {
            $validate['logo'] = 'file|max:110';
        } else {
            $validate['logo'] = 'required|file|max:110';
        }

        $validator = Validator::make($request->all(), $validate);

        try {
            if ($validator->fails()) {
                throw new \Exception(json_encode($validator->errors()->all()));
            }

            $id = decode($request->config_id);

            $data = [
                'app_name' => $request->app_name,
            ];

            $file = $request->file('logo');

            if ($file != null) {
                // $name = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                // open file a image resource from upload
                $img = Image::make($file);
                $path = 'image/logo/';

                // Main Logo ====================================
                $img->resize(150, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $file_name = 'logo2.' . $extension;
                $img->save(storage_path('app/' . $path) . $file_name);

                // Logo Icon ====================================
                $img->fit('50');

                $file_name_icon = 'logo2-ico.' . $extension;
                $img->save(storage_path('app/' . $path) . $file_name_icon);

                // $path = $file->storeAs('image/logo', 'logo.' . $extension);

                $data['logo'] = $path . $file_name;
            }

            $update = Config::find($id)->update($data);

            if (!$update) {
                throw new \Exception("Gagal update data!");
            }

            alert_success('Data berhasil disimpan.');
            return back();
        } catch (\Exception $e) {
            alert_failed('Data gagal disimpan.' . json_check($e->getMessage()));
            return back()
                ->withErrors($validator)
                ->withInput();;
        }
    }
}
