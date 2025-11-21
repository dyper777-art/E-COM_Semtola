<h2>Thank you for your order!</h2>
<p>Here’s what you bought:</p>
<ul>
@foreach($cart as $item)
    <li>{{ $item['name'] }} - {{ $item['quantity'] }} x ${{ $item['price'] }}</li>
@endforeach
</ul>
<p>Total: ${{ $total }}</p>
