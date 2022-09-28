<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

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
        if (FacadesAuth::check()) {
            $log = $request->user()->role->nama_role;
            $role = explode(';', $roles);
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
