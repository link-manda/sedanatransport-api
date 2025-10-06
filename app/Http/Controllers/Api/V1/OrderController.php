<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        // Mengambil data yang sudah divalidasi oleh StoreOrderRequest
        $validatedData = $request->validated();

        try {
            // Memulai database transaction
            $result = DB::transaction(function () use ($validatedData) {
                // 1. Cari atau buat customer baru berdasarkan email
                $customer = Customer::firstOrCreate(
                    ['email' => $validatedData['email_pelanggan']],
                    [
                        // DISESUAIKAN: Menggunakan nama kolom yang benar
                        'nama_lengkap' => $validatedData['nama_pelanggan'],
                        'no_telepon' => $validatedData['telepon_pelanggan'],
                        'alamat' => $validatedData['alamat_pelanggan'],
                        'nomor_ktp' => $validatedData['ktp_pelanggan'],
                    ]
                );

                // 2. Ambil data mobil
                $car = Car::findOrFail($validatedData['car_id']);

                // 3. Hitung durasi sewa dan total harga
                $tanggalMulai = Carbon::parse($validatedData['tanggal_mulai']);
                $tanggalSelesai = Carbon::parse($validatedData['tanggal_selesai']);
                $durasiHari = $tanggalSelesai->diffInDays($tanggalMulai) + 1;
                $totalHarga = $durasiHari * $car->harga_sewa_per_hari;

                // 4. Buat order baru
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'car_id' => $car->id,
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_selesai' => $tanggalSelesai,
                    'total_harga' => $totalHarga,
                    'status' => 'dikonfirmasi', // Status awal
                ]);

                // 5. Update status mobil menjadi 'disewa'
                $car->status = 'disewa';
                $car->save();

                return $order;
            });

            // Jika transaksi berhasil
            return response()->json([
                'message' => 'Pesanan Anda telah berhasil dibuat.',
                'data' => $result,
            ], 201); // 201 Created

        } catch (\Exception $e) {
            // Jika terjadi error selama transaksi
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses pesanan.',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }
}
