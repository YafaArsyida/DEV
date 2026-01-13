<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FingerSpot\PresensiPegawaiLog;
use Illuminate\Http\Request;

class AbsensiWebhookController extends Controller
{
    public function receive(Request $request)
    {

        // 🔐 Cek Header Token
        // dd($request->header('X-ABSEN-KEY'), env('ABSEN_WEBHOOK_KEY'));
        if ($request->header('X-ABSEN-KEY') !== config('webhook.key')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $payload = $request->all();

        PresensiPegawaiLog::create([
            'type'          => $payload['type'] ?? null,
            'cloud_id'      => $payload['cloud_id'] ?? null,
            'pin'           => $payload['data']['pin'],
            'scan_time'     => $payload['data']['scan'],
            'verify_method' => $payload['data']['verify'] ?? null,
            'status_scan'   => $payload['data']['status_scan'] ?? null,
            'raw'           => $payload
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Webhook received'
        ]);
    }
}
