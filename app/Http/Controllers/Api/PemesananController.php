<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;


class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Pemesanan::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ada = Pemesanan::where('lapangan_id', $request->lapangan_id)
            ->where('tanggal', $request->tanggal)
            ->where(function ($q) use ($request) {
                $q->where('jam_mulai', '<', $request->jam_selesai)
                    ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($ada) {
            return response()->json([
                'message' => 'Lapangan sudah dibooking pada jam tersebut.'
            ], 409);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'status' => 'in:pending,confirmed,cancelled',
            'bukti_tf' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $exists = \App\Models\Pemesanan::where('lapangan_id', $validated['lapangan_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('status', 'confirmed')
            ->where(function ($q) use ($validated) {
                $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhere(function ($q2) use ($validated) {
                        $q2->where('jam_mulai', '<', $validated['jam_mulai'])
                            ->where('jam_selesai', '>', $validated['jam_selesai']);
                    });
            })->exists();
        if ($exists) {
            return response()->json(['message' => 'Lapangan sudah terbooking silahkan pilih jam lain'], 422);
        }
        $lapangan = \App\Models\Lapangan::findOrFail($validated['lapangan_id']);
        $validated['harga'] = $lapangan->harga;
        $start = strtotime($validated['jam_mulai']);
        $end = strtotime($validated['jam_selesai']);
        $durasi = ($end - $start) / 3600;
        $validated['total_harga'] = $lapangan->harga * $durasi;
        if ($request->hasFile('bukti_tf')) {
            $file = $request->file('bukti_tf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti_tf'), $filename);
            $validated['bukti_tf'] = 'uploads/bukti_tf/' . $filename;
        }
        $pemesanan = \App\Models\Pemesanan::create($validated);
        return response()->json($pemesanan, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        return response()->json($pemesanan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pemesanan = \App\Models\Pemesanan::findOrFail($id);
        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'lapangan_id' => 'sometimes|exists:lapangans,id',
            'tanggal' => 'sometimes|date',
            'jam_mulai' => 'sometimes',
            'jam_selesai' => 'sometimes',
            'status' => 'sometimes|in:pending,confirmed,cancelled',
            'bukti_tf' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $lapangan_id = $validated['lapangan_id'] ?? $pemesanan->lapangan_id;
        $tanggal = $validated['tanggal'] ?? $pemesanan->tanggal;
        $jam_mulai = $validated['jam_mulai'] ?? $pemesanan->jam_mulai;
        $jam_selesai = $validated['jam_selesai'] ?? $pemesanan->jam_selesai;
        $exists = \App\Models\Pemesanan::where('lapangan_id', $lapangan_id)
            ->where('tanggal', $tanggal)
            ->where('status', 'confirmed')
            ->where('id', '!=', $id)
            ->where(function ($q) use ($jam_mulai, $jam_selesai) {
                $q->whereBetween('jam_mulai', [$jam_mulai, $jam_selesai])
                    ->orWhereBetween('jam_selesai', [$jam_mulai, $jam_selesai])
                    ->orWhere(function ($q2) use ($jam_mulai, $jam_selesai) {
                        $q2->where('jam_mulai', '<', $jam_mulai)
                            ->where('jam_selesai', '>', $jam_selesai);
                    });
            })->exists();
        if ($exists) {
            return response()->json(['message' => 'Lapangan sudah terbooking silahkan pilih jam lain'], 422);
        }
        $lapangan = \App\Models\Lapangan::findOrFail($lapangan_id);
        $validated['harga'] = $lapangan->harga;
        $start = strtotime($jam_mulai);
        $end = strtotime($jam_selesai);
        $durasi = ($end - $start) / 3600;
        $validated['total_harga'] = $lapangan->harga * $durasi;
        if ($request->hasFile('bukti_tf')) {
            $file = $request->file('bukti_tf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti_tf'), $filename);
            $validated['bukti_tf'] = 'uploads/bukti_tf/' . $filename;
        }
        $pemesanan->update($validated);
        return response()->json($pemesanan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->delete();
        return response()->json(['message' => 'Pemesanan deleted']);
    }

    /**
     * Check availability of the resource.
     */
    public function cekKetersediaan(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|integer|exists:lapangans,id',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $ada = Pemesanan::where('lapangan_id', $request->lapangan_id)
            ->where('tanggal', $request->tanggal)
            ->where(function ($q) use ($request) {
                // overlap rule: start < existing_end AND end > existing_start
                $q->where('jam_mulai', '<', $request->jam_selesai)
                    ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        return response()->json([
            'tersedia' => !$ada,
            'message'  => $ada ? 'Jadwal bentrok' : 'Tersedia',
        ]);
    }

    /**
     * Display a listing of the user's transaction history.
     */
    public function riwayatTransaksi(Request $request)
    {
        $user = $request->user();

        $transaksi = \App\Models\Pemesanan::with(['lapangan:id,nama'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($transaksi);
    }


    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_tf' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $p = Pemesanan::findOrFail($id);

        $file = $request->file('bukti_tf');
        $name = time() . '_' . $file->getClientOriginalName();

        // simpan ke storage/public/bukti
        $path = $file->storeAs('bukti', $name, 'public');

        $p->bukti_tf = 'storage/' . $path;   // contoh: storage/bukti/xxx.jpg
        $p->status = 'confirmed';
        $p->save();

        return response()->json([
            'message' => 'Upload bukti berhasil',
            'data' => $p,
        ]);
    }
    public function pay(Request $request, $id)
    {
        $user = $request->user();
        $booking = \App\Models\Pemesanan::where('user_id', $user->id)->findOrFail($id);

        if (in_array($booking->status, ['paid', 'confirmed'])) {
            return response()->json(['message' => 'Booking sudah dibayar.'], 400);
        }

        $gross = (int) $booking->total_harga;
        if ($gross <= 0) return response()->json(['message' => 'Total harga tidak valid'], 422);

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = filter_var(env('MIDTRANS_IS_PRODUCTION'), FILTER_VALIDATE_BOOLEAN);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = 'BOOK-' . $booking->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $gross,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [[
                'id' => (string) $booking->lapangan_id,
                'price' => $gross,
                'quantity' => 1,
                'name' => 'Booking Lapangan #' . $booking->lapangan_id,
            ]],
        ];

        $snap = \Midtrans\Snap::createTransaction($params);

        $booking->gateway = 'midtrans';
        $booking->gateway_order_id = $orderId;
        $booking->save();

        return response()->json([
            'order_id' => $orderId,
            'redirect_url' => $snap->redirect_url,
            'snap_token' => $snap->token,
        ]);
    }

    public function midtransWebhook(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');

        $signature = hash(
            'sha512',
            ($request->order_id ?? '') .
                ($request->status_code ?? '') .
                ($request->gross_amount ?? '') .
                $serverKey
        );

        if (($request->signature_key ?? '') !== $signature) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $booking = \App\Models\Pemesanan::where('gateway_order_id', $request->order_id)->first();
        if (!$booking) return response()->json(['message' => 'Booking not found'], 404);

        $booking->gateway_transaction_id = $request->transaction_id ?? $booking->gateway_transaction_id;
        $booking->gateway_payment_type = $request->payment_type ?? $booking->gateway_payment_type;

        $ts = $request->transaction_status; // settlement/capture/pending/deny/cancel/expire
        $fraud = $request->fraud_status;    // accept/challenge (untuk CC)

        if (in_array($ts, ['capture', 'settlement'])) {
            if ($ts === 'capture' && $fraud === 'challenge') {
                $booking->status = 'pending';
            } else {
                $booking->status = 'paid'; // atau 'confirmed'
                $booking->paid_at = now();
            }
        } elseif (in_array($ts, ['deny', 'cancel', 'expire'])) {
            $booking->status = 'failed';
        } else {
            $booking->status = 'pending';
        }

        $booking->save();
        return response()->json(['message' => 'OK']);
    }
}
