@extends('layouts.app')

@section('title', 'Books')

@section('content')
    @include('components.book-modal', ['type' => 'create'])
    @include('components.book-table')
@endsection
