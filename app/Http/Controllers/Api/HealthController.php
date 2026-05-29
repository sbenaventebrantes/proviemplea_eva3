<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class HealthController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'message' => 'API ProviEmplea funcionando',
            'data' => [
                'status' => 'ok',
            ],
        ]);
    }
}
