@extends('layouts.app')

@section('content')
    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <h2>Welcome to</h2><h1 class="fw-bold text-success">TEEN.</h1>
            <p>We are a family-run Japanese tea company founded in 1717, in the heart of 
                <br>Kyoto. For generations, we have upheld a tradition of aromatic, well-balanced 
                <br>teas by carefully selecting, blending, and crafting each of our 30+ blends.</p>
        </div>
    </section>

    <!-- Featured Products -->
@php
    

    $products = [
        ['id' => 1,'name' => 'Matcha Teen (White)', 'price' => 14.8, 'image' => 'IMG_2991.PNG'],
        ['id' => 2,'name' => 'Matcha Teen (Green)', 'price' => 14.8, 'image' => 'IMG_3000.jpg'],
        ['id' => 3,'name' => 'UMI', 'price' => 38, 'image' => 'IMG_3007.PNG'],
        ['id' => 4,'name' => 'Sadō', 'price' => 27, 'image' => 'IMG_3012.PNG'],
        ['id' => 5,'name' => 'Ummon', 'price' => 43, 'image' => 'IMG_3014.jpg'],
        ['id' => 6,'name' => 'Mellow', 'price' => 29, 'image' => 'IMG_3017.jpg'],
        ['id' => 7,'name' => 'Uji', 'price' => 38, 'image' => 'IMG_3016.jpg'],
        ['id' => 8,'name' => 'Chymey', 'price' => 32, 'image' => 'IMG_3018.jpg'],
        ['id' => 9,'name' => 'Marukyu koyamaen ISUZU matcha', 'price' => 41, 'image' => 'IMG_3019.jpg'],
        ['id' => 10,'name' => 'Yugen (Marukyu Koyamaen)', 'price' => 37, 'image' => 'IMG_3020.jpg'],
        ['id' => 11,'name' => 'Kanbayashi Shunsho', 'price' => 45.36, 'image' => 'IMG_3021.jpg'],
        ['id' => 12,'name' => 'Tsubokiri matcha', 'price' => 39, 'image' => 'IMG_3023.jpg'],
    ];
@endphp
{{-- <div class="product-search">
  <input type="text" id="searchInput" placeholder="Search products..." />
</div> --}}
<div class="desktop-wrapper">
    <!-- All your content here (hero, products, etc.) -->
<section class="products">
    <div class="container">
        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        {{-- <h1 class = "catalog-title">Catalog</h1> --}}
        <div class="product-grid">
            @foreach ($products as $index => $product)
                <div class="product-card">
                    <img class="imagesize" src="{{ asset('images/product/' . $product['image']) }}" alt="{{ $product['name'] }}">
                    <h6 class="product-title"> {{ $product['name'] }}</h6>   
                    <p>$ {{ $product['price'] }}</p>
                    <form action="{{ route('cart.add', $product['id']) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-add">Add</button>
                        {{-- View Button --}}
                        <button type="button" class="btn-view" onclick="openModal({{ $product['id'] }})">View</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
    {{-- Modal --}}
    <div id="productModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modalImage" class="modal-img" src="" alt="">
            <h2 id="modalName"></h2>
            <p id="modalPrice"></p>
            <p id="modalDescription"></p>

            <form id="modalAddForm" method="POST">
                @csrf
                <button type="submit" class="btn-view-add">Add</button>
            </form>
        </div>
    </div>
    
</section>
</div>

<script>
  const searchInput = document.getElementById("searchInput");

  searchInput.addEventListener("input", function () {
    const searchValue = this.value.toLowerCase();
    const productCards = document.querySelectorAll(".product-card");

    productCards.forEach(card => {
      const title = card.querySelector(".product-title").textContent.toLowerCase();
      card.style.display = title.includes(searchValue) ? "block" : "none";
    });
  });
</script>
<script>
    function openModal(id) {
        fetch(`/product/${id}`)
            .then(response => response.json())
            .then(product => {
                if (product.error) {
                    alert(product.error);
                    return;
                }

                // Fill modal content
                document.getElementById('modalImage').src = `/images/product/${product.image}`;
                document.getElementById('modalName').textContent = product.name;
                document.getElementById('modalPrice').textContent = `$ ${product.price}`;
                document.getElementById('modalDescription').textContent = product.description || '';

                // Set correct form action
                document.getElementById('modalAddForm').action = `/cart/add/${product.id}`;

                document.getElementById('productModal').style.display = 'block';
            })
            .catch(error => {
                console.error("Error fetching product:", error);
                alert("Failed to load product details.");
            });
    }

    function closeModal() {
        document.getElementById('productModal').style.display = 'none';
    }


    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('productModal');
        if (event.target === modal) {
            closeModal();
        }
    }
</script>


@endsection
