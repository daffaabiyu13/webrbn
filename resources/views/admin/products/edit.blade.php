@extends('admin.layout')

@section('title', 'Edit Produk')
@section('heading', 'Edit Produk: ' . $product->name)

@section('content')

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.products._form')
</form>

@endsection
