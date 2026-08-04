@extends('admin.layout')

@section('title', 'Edit Project')
@section('heading', 'Edit Project: ' . $project->title)

@section('content')

<form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.projects._form')
</form>

@endsection
