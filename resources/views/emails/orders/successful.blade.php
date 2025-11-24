@component('mail::message')
# Thank you for your order!

We have received your order. Here are the details:

@component('mail::table')
| Product      | Quantity | Price  | Subtotal |
| ------------ |:--------:| ------:| --------:|
@foreach ($cart as $item)
| {{ $item['name'] }} | {{ $item['quantity'] }} | ${{ number_format($item['price'], 2) }} | ${{ number_format($item['quantity'] * $item['price'], 2) }} |
@endforeach
@endcomponent

**Total:** ${{ number_format($total, 2) }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
