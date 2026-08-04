@extends('admin.layout')

@section('title', 'Edit Proyek')
@section('heading', 'Edit Proyek: ' . $project->title)

@section('content')

<form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.projects._form')
</form>

@endsection
