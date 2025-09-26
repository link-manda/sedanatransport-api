<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Kita akan izinkan semua request untuk saat ini.
        // Logika otorisasi akan kita tangani di fase selanjutnya.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'car_id' => [
                'required',
                'integer',
                // Pastikan mobil ada di database dan statusnya 'tersedia'
                Rule::exists('cars', 'id')->where('status', 'tersedia')
            ],
            'nama_pelanggan' => 'required|string|max:255',
            'email_pelanggan' => 'required|email|max:255',
            'telepon_pelanggan' => 'required|string|max:20',
            'alamat_pelanggan' => 'required|string',
            'ktp_pelanggan' => 'required|string|max:30',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ];
    }

    public function messages(): array
    {
        return [
            'car_id.exists' => 'Mobil yang dipilih tidak valid atau tidak tersedia saat ini.',
        ];
    }
}
