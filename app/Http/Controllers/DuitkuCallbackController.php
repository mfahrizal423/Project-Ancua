<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Pesanan;

class DuitkuCallbackController extends Controller
{
    public function callback(Request $request)
    {
        $merchantCode = env('DUITKU_MERCHANT_CODE');
        $apiKey = env('DUITKU_API_KEY');
        
        $amount = $request->amount;
        $merchantOrderId = $request->merchantOrderId;
        $signature = $request->signature;
        $resultCode = $request->resultCode;
        $reference = $request->reference;

        if(!empty($merchantCode) && !empty($amount) && !empty($merchantOrderId) && !empty($signature)) {
            $calcSignature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);
            
            if($signature == $calcSignature) {
                // Signature is valid
                $order = Pesanan::where('nomor_pesanan', $merchantOrderId)->first();
                
                if($order) {
                    if($resultCode == "00") {
                        // Success
                        $order->update([
                            'status' => 'pending' // As per your business logic (unpaid -> pending/preparing)
                        ]);
                        Log::info('Duitku Payment Success: ' . $merchantOrderId);
                    } else if($resultCode == "01") {
                        // Failed
                        $order->update([
                            'status' => 'cancelled'
                        ]);
                        Log::info('Duitku Payment Failed: ' . $merchantOrderId);
                    }
                }
                
                return response()->json(['status' => 'success'], 200);
            } else {
                Log::warning('Duitku Callback Invalid Signature: ' . $merchantOrderId);
                return response()->json(['status' => 'bad signature'], 400);
            }
        }
        
        return response()->json(['status' => 'bad request'], 400);
    }
}
