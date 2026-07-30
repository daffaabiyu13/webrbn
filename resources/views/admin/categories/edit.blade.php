@extends('admin.layout')

@section('title', 'Edit Kategori')
@section('heading', 'Edit Kategori: ' . $category->name)

@section('content')

<form method="POST" action="{{ route('admin.categories.update', $category) }}">
    @csrf @method('PUT')
    @include('admin.categories._form')
</form>

@endsection
