@extends('admin.layout')

@section('title', 'Tambah Proyek')
@section('heading', 'Tambah Proyek Baru')

@section('content')

<form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.projects._form', ['project' => null])
</form>

@endsection
