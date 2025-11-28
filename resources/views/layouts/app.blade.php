<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TEEN.</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


</head>
<style>
#qrImage {
    display: block;
    width: 250px;
    height: 250px;
    object-fit: contain;
}
</style>

<body>
    <div class="page-wrapper">
        @include('partials.header')

        <main>
            @yield('content')
        </main>
    </div>

    @include('partials.footer')
</body>

</html>
