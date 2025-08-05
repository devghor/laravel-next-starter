<?php

namespace App\Http\Middleware;

use App\Models\Tenancy\Tenant;
use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;
use Symfony\Component\HttpFoundation\Response;

class CustomInitializeTenancyByRequestData extends InitializeTenancyByRequestData
{
    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() !== 'OPTIONS') {
            $tenantInRequest = $this->getPayload($request);

            if (!$tenantInRequest) {
                return $this->errorResponse(
                    'Tenant not specified.',
                    Response::HTTP_BAD_REQUEST
                );
            }

            $user = Auth::user();
            if ($user) {
                $tenant = Tenant::where('id', $tenantInRequest)->first();

                if (!$tenant) {
                    return $this->errorResponse(
                        'Tenant not found.',
                        Response::HTTP_NOT_FOUND
                    );
                }

                if (!$user->tenants()->where('id', $tenant->id)->exists()) {
                    return $this->errorResponse(
                        'User does not have access to this tenant.',
                        Response::HTTP_FORBIDDEN
                    );
                }
                return $this->initializeTenancy($request, $next, $tenantInRequest);
            }
        }

        return $next($request);
    }
}
