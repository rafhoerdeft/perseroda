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
        $log = $request->session()->get('log');
        $role = explode(';', $roles);
        if (!in_array($log, $role)) {
?>
            <script>
                alert('Silahkan login dahulu!');
                // document.location = window.history.back();
                document.location = "<?= url('/') ?>";
            </script>
<?php
        }

        return $next($request);
    }
}
