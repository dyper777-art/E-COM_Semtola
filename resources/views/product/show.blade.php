@extends('layouts.app') {{-- or your base layout --}}
@section('content')
    <div style="max-width: 800px; margin: 2rem auto; text-align: center;">
        <img src="{{ asset('images/Product/' . $product['image']) }}" alt="{{ $product['name'] }}"
            style="width: 300px; border-radius: 8px; margin-bottom: 1rem;">
        <h2>{{ $product['name'] }}</h2>
        <p style="font-size: 1.2rem;">$ {{ $product['price'] }}</p>
        <p style="margin-top: 1rem; font-style: italic;">{{ $product['description'] }}</p>
        <a href="{{ url('/') }}" class="btn-sm" style="margin-top: 2rem; display: inline-block;">← Back to Home</a>
    </div>
@endsection
