<h1>Order Successful</h1>

<p>Thank you for your order!</p>

<p>Total: ${{ $total }}</p>

<ul>
    @foreach ($cart as $item)
        <li>{{ $item['name'] }} - Qty: {{ $item['quantity'] }} - Price: ${{ $item['price'] }}</li>
    @endforeach
</ul>
