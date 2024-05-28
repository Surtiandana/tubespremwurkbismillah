@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="row mb-0">
        <div class="col-lg-9 col-xl-10">
            <h4 class="mb-3">{{ $pageTitle }}</h4>
        </div>
        <div class="col-lg-3 col-xl-2">
            <div class="d-grid gap-2">
                <a href="{{ route('checkout.create') }}" class="btn btn-warning btn-primary">Create Order</a>
            </div>
        </div>
    </div>
    @vite('resources/css/card.css') <!-- Ensure this is inside the main container div -->
    <hr>
    <div class="table-responsive border p-3 rounded-3">
        <table class="table table-bordered table-hover table-striped mb-0 bg-white">
            <thead>
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Barang</th>
                    <th>Deskripsi Barang</th>
                    <th>Gambar Barang</th> <!-- Kolom untuk gambar -->
                    <th>Satuan Barang</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barang as $barang)
                <tr>
                    <td>{{ $barang->Kode_Barang }}</td>
                    <td>{{ $barang->Nama_Barang }}</td>
                    <td>{{ $barang->Harga_Barang }}</td>
                    <td>{{ $barang->Deskripsi_Barang }}</td>
                    <td><img src="{{ asset($barang->gambar) }}" alt="Gambar Barang" style="max-width: 100px;"></td> <!-- Tampilkan gambar -->
                    <td>{{ $barang->satuan->Nama_Satuan }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('checkout.show', ['checkout' => $barang->id]) }}" class="btn btn-outline-dark btn-sm me-2"><i class="bi bi-eye-fill"></i></a>
                            <a href="{{ route('checkout.edit', ['checkout' => $barang->id]) }}" class="btn btn-outline-dark btn-sm me-2"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('checkout.destroy', ['checkout' => $barang->id]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-dark btn-sm me-2"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
