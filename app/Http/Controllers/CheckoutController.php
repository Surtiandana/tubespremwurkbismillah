<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Satuan;

class CheckoutController extends Controller
{
    public function index()
    {
        $pageTitle = 'Checkout';
        $barangs = Barang::all();

        return view('order.index', [
            'pageTitle' => $pageTitle,
            'barang' => $barangs
        ]);
    }

    public function create()
    {
        $pageTitle = 'Create Order';
        $satuans = Satuan::all();
        return view('order.create', compact('pageTitle', 'satuans'));
    }

    public function store(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'kode' => 'required|unique:barangs,Kode_Barang',
        'nama' => 'required',
        'harga' => 'required|numeric',
        'deskripsi' => 'required',
        'satuan_id' => 'required',
        'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Proses unggah gambar
    $gambar = $request->file('gambar');
    $nama_file = time().'.'.$gambar->getClientOriginalExtension();
    $gambar->move(public_path('images'), $nama_file);

    // Simpan data barang beserta nama file gambar
    $barang = new Barang;
    $barang->Kode_Barang = $request->kode;
    $barang->Nama_Barang = $request->nama;
    $barang->Harga_Barang = $request->harga;
    $barang->Deskripsi_Barang = $request->deskripsi;
    $barang->satuan_id = $request->satuan_id;
    $barang->gambar = 'images/'.$nama_file; // Simpan path gambar ke database
    $barang->save();

    return redirect()->route('checkout.index');
}

    public function show($id)
    {
        $pageTitle = 'Show';
        $barang = Barang::findOrFail($id); // Gunakan findOrFail untuk menangani jika barang tidak ditemukan
        return view('order.show', compact('pageTitle', 'barang'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Order';
        $satuans = Satuan::all();
        $barang = Barang::find($id);
        return view('order.edit', compact('pageTitle', 'satuans', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'kode.unique' => 'Kode barang sudah ada',
            'numeric' => 'Hanya bisa diisi dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:barangs,Kode_Barang,' . $id,
            'nama' => 'required',
            'harga' => 'required|numeric',
            'deskripsi' => 'required',
            'satuan_id' => 'required', // Sesuaikan dengan nama input pada form
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar

        ], $messages);

        $validator->after(function ($validator) use ($request, $id) {
            $value = $request->input('kode');
            $count = DB::table('barangs')
                ->where('Kode_Barang', $value)
                ->where('id', '<>', $id)
                ->count();

            if ($count > 0) {
                $validator->errors()->add('kode', 'Kode Barang ini sudah dipakai.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $barang = Barang::find($id);
        $barang->Kode_Barang = $request->kode;
        $barang->Nama_Barang = $request->nama;
        $barang->Harga_Barang = $request->harga;
        $barang->Deskripsi_Barang = $request->deskripsi;
        $barang->satuan_id = $request->satuan_id; // Corrected to use satuan_id

        $barang->save();

        return redirect()->route('checkout.index')->with('success', 'Order has been updated successfully.');
    }


    public function destroy($id)
    {
        Barang::find($id)->delete();

        return redirect()->route('checkout.index');
    }

    public function viewOnly()
{
    $pageTitle = 'View Orders';
    $barangs = Barang::all();

    return view('order.view', [
        'pageTitle' => $pageTitle,
        'barang' => $barangs
    ]);
}

}

