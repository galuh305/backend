<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;

class PaymentController extends Controller
{
    public function midtransWebhook(Request $request)
    {
        // Midtrans butuh endpoint yang selalu balas 200 OK
        // Jangan lempar error yang bikin 500/502, simpen log dulu aja

        try {
            $payload = $request->all();

            $orderId = $payload['order_id'] ?? null;
            $transactionStatus = $payload['transaction_status'] ?? null;
            $fraudStatus = $payload['fraud_status'] ?? null;

            if (!$orderId) {
                return response()->json(['message' => 'order_id missing'], 200);
            }

            // contoh order id kamu: BOOK-31-1767794854
            // ambil booking id "31"
            $bookingId = null;
            if (preg_match('/BOOK-(\d+)-/', $orderId, $m)) {
                $bookingId = (int) $m[1];
            }

            if ($bookingId) {
                $booking = Pemesanan::find($bookingId);
                if ($booking) {
                    // map status Midtrans => status booking
                    if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                        $booking->status = 'confirmed';
                    } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'deny' || $transactionStatus === 'expire') {
                        $booking->status = 'cancelled';
                    } else {
                        $booking->status = 'pending';
                    }

                    // simpen raw response kalau kamu punya kolom
                    if (property_exists($booking, 'midtrans_status')) {
                        $booking->midtrans_status = $transactionStatus;
                    }
                    if (property_exists($booking, 'midtrans_order_id')) {
                        $booking->midtrans_order_id = $orderId;
                    }

                    $booking->save();
                }
            }

            return response()->json(['message' => 'ok'], 200);
        } catch (\Throwable $e) {
            // yang penting balikin 200 biar Midtrans stop retry
            return response()->json(['message' => 'ok'], 200);
        }
    }
}
