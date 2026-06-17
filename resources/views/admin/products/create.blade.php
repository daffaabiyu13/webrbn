@extends('admin.layout')

@section('title', 'Tambah Produk')
@section('heading', 'Tambah Produk')

@section('content')

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.products._form', ['product' => null])
</form>

@endsection
