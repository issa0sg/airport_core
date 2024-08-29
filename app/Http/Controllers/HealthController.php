<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Exception;

class HealthController extends Controller
{

    function index(Request $request): JsonResponse
    {
        try {
            Cache::add(key: 'health_check', value: 'healthy', ttl: 60);
            $cacheStatus = Cache::get('health_check', 'illness');
        } catch (Exception $e) {
            $cacheStatus = $e->getMessage();
        }

        try {
            DB::table('migrations')->first();
            $dbStatus = 'healthy';
        } catch (Exception $e) {
            $dbStatus = $e->getMessage();
        }
        return response()->json([
            'cache_status'  => $cacheStatus,
            'db_status'     => $dbStatus
        ]);
    }
}
