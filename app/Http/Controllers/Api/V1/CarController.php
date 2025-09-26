<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data mobil dan mengembalikannya sebagai koleksi resource
        return CarResource::collection(Car::latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'tahun' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'plat_nomor' => 'required|string|unique:cars,plat_nomor|max:20',
            'harga_sewa_per_hari' => 'required|numeric|min:0',
        ]);

        $car = Car::create($validatedData);

        return new CarResource($car);
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        // Mengembalikan data mobil tunggal sebagai resource
        return new CarResource($car);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        $validatedData = $request->validate([
            'brand' => 'sometimes|required|string|max:255',
            'model' => 'sometimes|required|string|max:255',
            'tahun' => 'sometimes|required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'plat_nomor' => 'sometimes|required|string|max:20|unique:cars,plat_nomor,' . $car->id,
            'harga_sewa_per_hari' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|in:tersedia,disewa,perbaikan',
        ]);

        $car->update($validatedData);

        return new CarResource($car);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        $car->delete();

        // Mengembalikan respons kosong dengan status 204 No Content
        return response()->noContent();
    }
}
