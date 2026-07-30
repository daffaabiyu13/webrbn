@extends('admin.layout')

@section('title', 'Tambah Kategori')
@section('heading', 'Tambah Kategori')

@section('content')

<form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf
    @include('admin.categories._form', ['category' => null])
</form>

@endsection
