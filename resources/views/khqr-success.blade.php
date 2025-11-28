@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <div class="text-success mb-4">
            <i class="bi bi-check-circle-fill" style="font-size: 4rem;"></i>
        </div>
        <h1 class="mb-3">Payment Successful! 🎉</h1>
        <p class="lead mb-4">Thank you for your purchase. Your order has been confirmed.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
            <a href="{{ route('orders') }}" class="btn btn-outline-secondary">View Orders</a>
        </div>
    </div>
</div>
@endsection