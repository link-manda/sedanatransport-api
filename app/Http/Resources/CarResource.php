<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'brand' => $this->brand,
            'model' => $this->model,
            'tahun' => $this->tahun,
            'plat_nomor' => $this->plat_nomor,
            'harga_sewa_per_hari' => number_format($this->harga_sewa_per_hari, 2, ',', '.'),
            'status' => $this->status,
            'gambar_url' => $this->gambar ? url('storage/' . $this->gambar) : null,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
        ];
    }
}
