<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Navigation;

class UserBaseController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct()
    {
        if (!session()->has('nav')) {
            session()->put('nav', Navigation::getMenu());
        }

        if (!session()->has('configs')) {
            $conf = Config::select('logo', 'app_name', 'active_period')->find(1)->toArray();
            session()->put('configs', $conf);
        }

        define('storage', url('storage') . '/');
        define('asset_js', url('assets/js') . '/');
        define('asset_css', url('assets/css') . '/');
        define('asset_ext', url('assets/external') . '/');
        define('selected_year', date('Y'));
    }
}
