@extends('layouts.app')

@section('title', 'Books')

@section('content')
    @include('components.book-create-form')
    @include('components.book-table')
@endsection
