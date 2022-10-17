<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if (auth()->guard('pdau')->check()) {
            $log = $request->user()->role->nama_role;
            $role = explode(';', $roles);

            // $finalArray = [];
            // $role = explode(';', $roles);
            // foreach ($role as $key) {
            //     $tmp = explode('|', $key);
            //     $finalArray[$tmp[0]] = explode('-', $tmp[1]);
            // }

            if (!in_array($log, $role)) {
                // abort(401);
?>
                <script>
                    alert("Silahkan login dahulu sebagai <?= ucwords(implode('/', $role)) ?>.");
                    // document.location = window.history.back();
                    document.location = "<?= url('/logout') ?>";
                </script>
            <?php
            }
        } else {
            ?>
            <script>
                alert("Silahkan login dahulu!");
                document.location = "<?= url('/logout') ?>";
            </script>
<?php
        }

        return $next($request);
    }
}
