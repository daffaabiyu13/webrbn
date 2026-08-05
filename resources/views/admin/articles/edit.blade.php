@extends('admin.layout')

@section('title', 'Edit Artikel')
@section('heading', 'Edit Artikel: ' . $article->title)

@section('content')

<form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.articles._form')
</form>

@endsection
