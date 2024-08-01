<!-- resources/views/home.blade.php -->

@extends('layouts.app')

@section('content')
    @if (auth()->user()->hasRole('admin'))
        <p>Welcome, Admin!</p>
        <!-- Admin-specific content -->
    @elseif (auth()->user()->hasRole('customer'))
        <p>Welcome, Customer!</p>
        <!-- Customer-specific content -->
    @endif
@endsection
