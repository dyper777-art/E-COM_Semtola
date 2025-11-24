<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TEEN. Navigation</title>
</head>

<body>

    <nav class="navbar">
        <div class="logo">
            <a href="/" style="font-size: 0.7cm">TEEN</a>
        </div>
        <div class="nav-center">
            <ul class="nav-links">
                <li><a href="/">Home</a></li>
                <li><a href="/catalog">Catalog</a></li>
                <li>
                    <a href="{{ route('cart.index') }}">
                        Cart ({{ session('cart') ? count(session('cart')) : 0 }})
                    </a>
                </li>
                <li><a href="/about">About</a></li>
            </ul>
        </div>
    </nav>

</body>

</html>
