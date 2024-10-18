<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Organization;
use App\Services\AuthService;
use Illuminate\Http\Request;

class TenantMiddleware
{
    public function __construct(protected AuthService $authService)
    { 
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $tenantApiToken = $request->header('X-Tenant-ID');
        $getTenantsIds = $this->authService->getTenantsId($tenantApiToken);

        app()->singleton('organizationIds', function () use ($getTenantsIds) {
            return is_array($getTenantsIds) ? $getTenantsIds : [$getTenantsIds];
        });

        return $next($request);
    }
}
