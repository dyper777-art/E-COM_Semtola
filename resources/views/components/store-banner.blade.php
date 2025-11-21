<!-- resources/views/components/store-banner.blade.php -->
<div class="relative w-full overflow-hidden">
    <div class="banner-slider flex transition-transform duration-500 ease-in-out">
        <!-- Slide 1 -->
        <div class="min-w-full h-64 md:h-96 bg-cover bg-center" style="background-image: url('/images/banner1.jpg')">
            <div class="bg-black bg-opacity-40 h-full flex items-center justify-center">
                <h2 class="text-white text-2xl md:text-4xl font-bold">Big Sale 50% Off!</h2>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="min-w-full h-64 md:h-96 bg-cover bg-center" style="background-image: url('/images/banner2.jpg')">
            <div class="bg-black bg-opacity-40 h-full flex items-center justify-center">
                <h2 class="text-white text-2xl md:text-4xl font-bold">New Arrivals Are Here</h2>
            </div>
        </div>
        <!-- Slide 3 -->
        <div class="min-w-full h-64 md:h-96 bg-cover bg-center" style="background-image: url('/images/banner3.jpg')">
            <div class="bg-black bg-opacity-40 h-full flex items-center justify-center">
                <h2 class="text-white text-2xl md:text-4xl font-bold">Free Shipping on Orders $50+</h2>
            </div>
        </div>
    </div>

    <!-- Navigation buttons -->
    <button id="prev" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded">‹</button>
    <button id="next" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded">›</button>
</div>

<script>
    const slider = document.querySelector('.banner-slider');
    const slides = document.querySelectorAll('.banner-slider > div');
    let index = 0;

    document.getElementById('next').addEventListener('click', () => {
        index = (index + 1) % slides.length;
        slider.style.transform = `translateX(-${index * 100}%)`;
    });

    document.getElementById('prev').addEventListener('click', () => {
        index = (index - 1 + slides.length) % slides.length;
        slider.style.transform = `translateX(-${index * 100}%)`;
    });

    // Optional: Auto-slide every 5 seconds
    setInterval(() => {
        index = (index + 1) % slides.length;
        slider.style.transform = `translateX(-${index * 100}%)`;
    }, 5000);
</script>
