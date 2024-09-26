<?php

namespace App\Http\Middleware;

use App\Models\Enterprise;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $tenantId = $request->header('X-Tenant-ID');
        $tenant = Enterprise::find($tenantId);
        if (!$tenant->multi_enterprise) {
            app()->singleton('enterpriseIds', function () use ($tenant) {
                return [$tenant->uuid];
            });

            return $next($request);
        }
        $mainTenant = $tenant->headOffice()->first();
        $parentTenants = $mainTenant->branches()->pluck('uuid')->toArray();
        $allTenants = array_merge([$mainTenant->uuid], $parentTenants);

        app()->singleton('enterpriseIds', function () use ($allTenants) {
            return $allTenants;
        });

        return $next($request);
    }
}
