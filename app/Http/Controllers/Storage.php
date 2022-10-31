<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Storage extends Controller
{
    public function image($path_file = null)
    {
        try {
            $response = response('show image')
                // ->header('Access-Control-Allow-Origin', 'https://pdau.magelangkab.go.id')
                ->header('Content-Type', ['image/png', 'image/jpeg'])
                ->header('Cache-Control', 'no-cache')
                ->header('Pragma', 'no-cache');

            $arr = explode('.', $path_file);
            array_pop($arr); // remove last array value
            $file = join('', $arr); // join array value to string

            readfile(storage_path('app/' . decode($file)));
        } catch (\Exception $e) {
            $response = abort('404');
        }

        return $response;
    }
}
