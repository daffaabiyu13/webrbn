@extends('admin.layout')

@section('title', 'Tambah Project')
@section('heading', 'Tambah Project')

@section('content')

<form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.projects._form', ['project' => null])
</form>

@endsection
