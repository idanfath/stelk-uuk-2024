<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\Item;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{ 
    // protected $fillable = [
    //     'pID',
    //     'total',
    //     'date',
    // ];

    // public function pelanggan()
    // {
    //     return $this->belongsTo(Pelanggan::class, 'pID', 'id');
    // }

    public function addTrx(Request $request)
    {
        // for transaksi insert into transaction the carbon formatted date, total, and pID (pelanggan id) 
        // for each inside items, insert into detail(each item has different table but united under one tID) the tID(transaction ID), iID (itemID), quantity, and subtotal
        $request->validate([
            'pID' => 'required',
            'total' => 'required',
            'transaction_date' => 'required',
            'items' => 'required|array',
        ]);

        // format the datetime into Date supported
        $date = Carbon::parse($request->transaction_date)->format('Y-m-d H:i:s');

        // Create a new Transaksi record
        $transaksi = Transaksi::create([
            'pID' => $request->pID,
            'total' => $request->total,
            'transaction_date' => $date,
        ]);

        // For each item, create a new Detail record associated with the Transaksi
        foreach ($request->items as $item) {
            $itemData = Item::find($item['iID']);
            if ($itemData) {
                if ($itemData->stok < $item['quantity']) {
                    return response()->json([
                        'message' => 'Stok ' . $itemData->name . ' tidak mencukupi',
                    ], 400);
                }
                $itemData->stok -= $item['quantity'];
                $itemData->save();
            }
            Detail::create([
                'tID' => $transaksi->id,
                'iID' => $item['iID'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ]);
            // check if item is negative, set to 0 and notify
            if ($itemData->stok < 0) {
                $itemData->stok = 0;
                $itemData->save();
                return response()->json([
                    'message' => 'Stok ' . $itemData->name . ' tidak mencukupi, stok diubah menjadi 0',
                ], 400);
            }
        }
        
        return response()->json([
            'message' => 'Transaksi berhasil ditambahkan',
            'data' => $transaksi,
        ]);
    }

    public function getTrxData(Request $request)
    {
        // Fetch all transactions with their associated buyer and details
        $transactions = Transaksi::with(['pelanggan', 'details.item'])->get();

        // Transform the data to the desired format
        $transformedData = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'total' => $transaction->total,
                'transaction_date' => $transaction->transaction_date,
                'created_at' => $transaction->created_at,
                'pelanggan' => [
                    'id' => $transaction->pelanggan->id,
                    'name' => $transaction->pelanggan->name,
                    'address' => $transaction->pelanggan->address,
                    'number' => $transaction->pelanggan->number,
                ],
                'details' => $transaction->details->map(function ($detail) {
                    return [
                        'quantity' => $detail->quantity,
                        'subtotal' => $detail->subtotal,
                        'item' => [
                            'name' => $detail->item->name,
                            'price' => $detail->item->price,
                            'desc' => $detail->item->desc,
                            'tag' => $detail->item->tag,
                            'thumbnail' => $detail->item->thumbnail,
                        ],
                    ];
                }),
            ];
        });

        return response()->json($transformedData);
    }


    public function deleteTrx(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $transaksi = Transaksi::find($request->id);

        if ($transaksi) {
            $transaksi->delete();

            return response()->json([
                'message' => 'Transaksi berhasil dihapus',
            ]);
        } else {
            return response()->json([
                'message' => 'Transaksi tidak ditemukan',
            ], 404);
        }
    }

    // delete many trx
    public function deleteTrxs(Request $request)
    {
        $request->validate([
            'id' => 'required|array',
        ]);

        Transaksi::whereIn('id', $request->id)->delete();

        return response()->json([
            'message' => 'Transaksi berhasil dihapus',
        ]);
    }
}
