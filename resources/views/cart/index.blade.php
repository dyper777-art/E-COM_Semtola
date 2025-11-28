@extends('layouts.app')

@section('content')
    @php $total = 0; @endphp

    <div class="container py-5">
        <h1 class="mb-4">🛒 Your Cart</h1>

        {{-- Alert Messages --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (count($cart) > 0)
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $id => $item)
                                    @php
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total += $subtotal;
                                    @endphp
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td class="text-center">{{ $item['quantity'] }}</td>
                                        <td class="text-end">${{ number_format($item['price'], 2) }}</td>
                                        <td class="text-end">${{ number_format($subtotal, 2) }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($total, 2) }}</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex flex-column align-items-start mt-4 gap-2">
                        <!-- Total Display -->
                        <h4 class="text-success fw-semibold">Total: ${{ number_format($total, 2) }}</h4>

                        <!-- Stripe Payment Button -->
                        <form action="{{ route('stripe.checkout') }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">Pay with Card (Stripe)</button>
                        </form>

                        <!-- KHQR Payment Button -->
                        <button type="submit" class="btn btn-success w-100" data-bs-toggle="modal"
                            data-bs-target="#khqrModal">
                            Pay with KHQR
                        </button>

                        {{-- <!-- Debug Button -->
                        <button type="button" class="btn btn-warning w-100 mt-2" onclick="debugKHQR()">
                            Debug KHQR
                        </button> --}}
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <p class="fs-4">Your cart is empty 😢</p>
                <a href="{{ url('/') }}" class="btn btn-primary mt-3">Continue Shopping</a>
            </div>
        @endif
    </div>

<div class="modal fade" id="khqrModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Pay with KHQR</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                
                <!-- QR Code Display -->
                <div id="khqrContent">
                    <p class="text-muted mb-3">Scan this QR code with your Bakong app</p>
                    
                    <!-- QR Code Container -->
                    <div class="mb-3 border rounded p-3 bg-white d-inline-block">
                        @php
                            try {
                                // Use the KHQR package directly (same as your example)
                                $individualInfo = new \KHQR\Models\IndividualInfo(
                                    bakongAccountID: 'sem_tola@aclb',
                                    merchantName: 'Teen',
                                    merchantCity: 'PHNOM PENH',
                                    currency: \KHQR\Helpers\KHQRData::CURRENCY_USD,
                                    amount: 0.01 #this is static price
                                );

                                $response = \KHQR\BakongKHQR::generateIndividual($individualInfo);

                                // Extract the QR string from the response
                                // The package might return the QR string directly or in a specific format
                                $qrString = $response->qrString ?? $response->data['qr'] ?? $response;
                                
                                // If it's an object, try to get the QR string
                                if (is_object($response) && method_exists($response, 'getQrString')) {
                                    $qrString = $response->getQrString();
                                } elseif (is_object($response) && property_exists($response, 'qrString')) {
                                    $qrString = $response->qrString;
                                } elseif (is_array($response)) {
                                    $qrString = $response['qrString'] ?? $response['qr'] ?? null;
                                }

                                if (!$qrString) {
                                    throw new \Exception('KHQR did not return QR payload');
                                }

                                // DEBUG: Show the generated QR string
                                echo "<div style='background: #f8f9fa; padding: 8px; margin: 8px 0; border-radius: 4px; font-size: 10px; word-break: break-all;'>";
                                // echo "<strong>Package Generated QR String:</strong><br>" . htmlspecialchars($qrString);
                                echo "</div>";

                                $qrImageUrl = "https://quickchart.io/qr?text=" . urlencode($qrString) . "&size=250&format=png&margin=2";

                            } catch (\Exception $e) {
                                $qrImageUrl = "https://quickchart.io/qr?text=ERROR:{$e->getMessage()}&size=250";
                                echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
                            }
                        @endphp
                        
                        <img src="{{ $qrImageUrl }}" alt="KHQR Code" style="width: 250px; height: 250px;">
                    </div>
                    
                    <!-- Order Details -->
                    <div class="card border-0 bg-light">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between small">
                                <span>Amount:</span>
                                <strong class="text-success">${{ number_format($total, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span>Currency:</span>
                                <span>USD</span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span>Merchant:</span>
                                <span>Teen</span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span>Account:</span>
                                <span class="text-muted">sem_tola@aclb</span>
                            </div>
                        </div>
                    </div>
                    
                    <p class="small text-muted mt-3">
                        <i class="bi bi-clock"></i> QR code expires in 15 minutes
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                {{-- <button type="button" class="btn btn-outline-success" onclick="location.reload()">
                    Refresh QR
                </button> --}}
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Make sure Bootstrap CSS is loaded in your layout
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 KHQR System - Fixed Version');

    const khqrModal = document.getElementById('khqrModal');
    const khqrLoading = document.getElementById('khqrLoading');
    const khqrContent = document.getElementById('khqrContent');
    const khqrError = document.getElementById('khqrError');
    const qrImage = document.getElementById('qrImage');

    // Simple function to generate and display QR
    async function generateQRCode() {
        console.log('🚀 Starting QR generation');
        
        // Show loading, hide others
        khqrLoading.style.display = 'block';
        khqrContent.style.display = 'none';
        khqrError.style.display = 'none';
        
        const amount = {{ $total }};
        const currency = 'USD';
        
        try {
            console.log('📤 Fetching QR data...');
            const response = await fetch(`/bakong/generate-qr-json?amount=${amount}&currency=${currency}`);
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const data = await response.json();
            console.log('✅ Data received:', data);
            
            if (data.success && data.qr_image_url) {
                console.log('🖼️ Setting image:', data.qr_image_url);
                
                // Set image source
                qrImage.src = data.qr_image_url;
                
                // Wait for image to load
                qrImage.onload = function() {
                    console.log('✅ Image loaded successfully');
                    // Show content, hide loading
                    khqrLoading.style.display = 'none';
                    khqrContent.style.display = 'block';
                    
                    // Start payment checking
                    if (data.md5) {
                        startPaymentChecking(data.md5);
                    }
                };
                
                qrImage.onerror = function() {
                    console.error('❌ Image failed to load');
                    showError('Failed to load QR image');
                };
                
            } else {
                throw new Error('No QR data received');
            }
            
        } catch (error) {
            console.error('💥 Error:', error);
            showError('Failed to generate QR: ' + error.message);
        }
    }

    function showError(message) {
        khqrLoading.style.display = 'none';
        khqrContent.style.display = 'none';
        khqrError.style.display = 'block';
        document.getElementById('errorMessage').textContent = message;
    }

    function startPaymentChecking(md5) {
        console.log('💰 Starting payment checking for MD5:', md5);
        // Add your payment checking logic here
        // You can use setInterval to poll for payment status
    }

    // When modal opens, generate QR
    khqrModal.addEventListener('show.bs.modal', function() {
        console.log('📱 Modal opening - generating QR');
        generateQRCode();
    });

    // When modal closes, reset
    khqrModal.addEventListener('hidden.bs.modal', function() {
        console.log('📱 Modal closed - resetting');
        khqrLoading.style.display = 'block';
        khqrContent.style.display = 'none';
        khqrError.style.display = 'none';
        qrImage.src = '';
    });

    // Retry button
    document.getElementById('retryQRBtn')?.addEventListener('click', generateQRCode);
    
    // Regenerate button
    document.getElementById('regenerateQRBtn')?.addEventListener('click', generateQRCode);

    console.log('✅ KHQR system ready - click "Pay with KHQR"');
});

// Debug function
async function debugKHQR() {
    console.log('🧪 Debugging KHQR...');
    
    const amount = {{ $total }};
    const currency = 'USD';
    
    try {
        // Test backend
        const response = await fetch(`/bakong/generate-qr-json?amount=${amount}&currency=${currency}`);
        const data = await response.json();
        console.log('Backend data:', data);
        
        if (data.success && data.qr_image_url) {
            console.log('✅ Backend working, QR URL:', data.qr_image_url);
            
            // Test image loading
            const testImg = new Image();
            testImg.onload = function() {
                console.log('✅ QR image loads successfully');
                alert('✅ Backend and image loading working! Check console for details.');
            };
            testImg.onerror = function() {
                console.error('❌ QR image failed to load');
                alert('❌ Image loading failed. Check console.');
            };
            testImg.src = data.qr_image_url;
            
        } else {
            throw new Error('Backend returned no QR data');
        }
    } catch (error) {
        console.error('Debug error:', error);
        alert('❌ Debug failed: ' + error.message);
    }
}
</script>

<style>
/* Ensure everything displays properly */
#khqrLoading, #khqrContent, #khqrError {
    transition: all 0.3s ease;
}

#qrImage {
    display: block;
    width: 250px;
    height: 250px;
    margin: 0 auto;
}
</style>
@endsection