<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function addCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'number' => 'required',
        ]);

        Pelanggan::create([
            'name' => $request->name,
            'address' => $request->address,
            'number' => $request->number,
        ]);

        return response()->json([
            'message' => 'Pelanggan berhasil ditambahkan',
            'data' => $request->all(),
        ]);
    }

    public function fetchAllCustomers(Request $request)
    {
        $pelanggan = Pelanggan::all();
        return response()->json([
            'message' => 'Data pelanggan berhasil diambil',
            'data' => $pelanggan,
        ]);
    }

    public function fetchCustomer(Request $request)
    {
        $pelanggan = Pelanggan::where('id', $request->id)->first();
        return response()->json([
            'message' => 'Data pelanggan berhasil diambil',
            'data' => $pelanggan,
        ]);
    }

    // delete customer
    public function deleteCustomer(Request $request)
    {
        $pelanggan = Pelanggan::where('id', $request->id)->first();
        $pelanggan->delete();
        return response()->json([
            'message' => 'Data pelanggan berhasil dihapus',
            'data' => $pelanggan,
        ]);
    }

    // delete multiple via array customers
    public function deleteMultipleCustomers(Request $request)
    {
        $pelanggan = Pelanggan::whereIn('id', $request->id)->get();
        foreach ($pelanggan as $p) {
            $p->delete();
        }
        return response()->json([
            'message' => 'Data pelanggan berhasil dihapus',
            'data' => $pelanggan,
        ]);
    }

    // update allow all or some fields
    public function editCustomer(Request $request)
    {
        $pelanggan = Pelanggan::where('id', $request->id)->first();
        //existcheck
        $existcheck = Pelanggan::where('number', $request->number)->whereNot('id', $request->id)->first();
        if ($existcheck) {
                return response()->json([
                    'message' => 'Nomor telepon sudah digunakan',
                ], 409);
        }
        $pelanggan->update($request->all());
        return response()->json([
            'message' => 'Data pelanggan berhasil diubah',
            'data' => $pelanggan,
        ]);
    }

    public function getCustomersNameNumber(Request $request)
    {
        $pelanggan = Pelanggan::all();
        $data = [];
        foreach ($pelanggan as $p) {
            $data[] = [
                'name' => $p->name,
                'id' => $p->id,
            ];
        }
        return response()->json([
            'message' => 'Data pelanggan berhasil diambil',
            'data' => $data,
        ]);
    }

}
