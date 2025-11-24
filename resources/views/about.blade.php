@extends('layouts.app')

@section('title', 'About MatchaMinds')

@section('content')
    <div class="container py-5 d-flex flex-column align-items-center text-center">

        <!-- Header Section -->
        <div class="mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="MatchaMinds Logo" width="200" class="mb-3">
            <h1 class="fw-bold text-success">MatchaMinds</h1>
            <p class="text-muted fs-5">Your trusted platform for premium matcha and wellness products.</p>
        </div>

        <!-- Mission Section -->
        <section class="mb-5 w-100" style="max-width: 720px;">
            <h2 class="text-success mb-3">🌱 Our Mission</h2>
            <p>We’re on a mission to reconnect people with the power of green through the ancient tradition of matcha—made
                for modern living. One cup at a time, we’re making wellness a daily ritual.</p>
            <img src="{{ asset('images/monk-making-matcha.jpg') }}" alt="Matcha Ritual"
                class="img-fluid rounded shadow mt-3">
        </section>

        <!-- Why Choose Us Section -->
        <section class="mb-5 w-100" style="max-width: 720px;">
            <h2 class="text-success mb-3">✨ Why Choose MatchaMinds?</h2>
            <ul class="list-group list-group-flush text-start">
                <li class="list-group-item">✅ Organic, ethically sourced matcha from sustainable farms in Japan</li>
                <li class="list-group-item">✅ Freshly milled & shipped fast to preserve flavor and nutrients</li>
                <li class="list-group-item">✅ Transparent sourcing with full traceability</li>
                <li class="list-group-item">✅ Matcha for every mood — energy, calm, focus, detox</li>
                <li class="list-group-item">✅ Excellent support from real tea lovers</li>
            </ul>
            <img src="{{ asset('images/Product/IMG_2993.png') }}" alt="Matcha Products" width="500"
                class="img-fluid rounded shadow mt-4">
        </section>

        <!-- Team Section -->
        <section class="mb-5 w-100" style="max-width: 720px;">
            <h2 class="text-success mb-3">🧑‍🤝‍🧑 Our Team</h2>
            <p>We’re a tight-knit crew of tea sommeliers, wellness experts, and designers committed to making matcha
                approachable, authentic, and exciting for everyone.</p>
            {{-- <img src="{{ asset('images/matcha-team.jpg') }}" alt="Our Team" class="img-fluid rounded shadow mt-3"> --}}
        </section>

        <!-- Contact Section -->
        <section class="w-100" style="max-width: 720px;">
            <h2 class="text-success mb-3">📞 Contact</h2>
            <p>Got questions or curious about which matcha is right for you?</p>
            {{-- <a href="{{ route('contact') }}" class="btn btn-outline-success rounded-pill px-4 mt-2">Reach Out to Us</a> --}}
        </section>
    </div>
@endsection
