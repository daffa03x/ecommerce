@extends('layouts.main')

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-6">
                <!-- Gambar produk -->
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name_product }}"
                    class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <!-- Detail produk -->
                <h1 class="mb-3">{{ $product->name_product }}</h1>

                <!-- Stock produk -->
                <p><strong>Stock:</strong> {{ $product->stock }}</p>

                <!-- Harga produk -->
                <p><strong>Price:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                <!-- Status produk -->
                <p><strong>Status:</strong>
                    @if ($product->status == 'available')
                        <span class="badge bg-success">Available</span>
                    @else
                        <span class="badge bg-danger">Unavailable</span>
                    @endif
                </p>

                <!-- Deskripsi produk -->
                <div class="mt-4">
                    <h5>Description</h5>
                    <p>{{ $product->description }}</p>
                </div>

                <!-- Tombol Aksi (misalnya: Tambah ke keranjang) -->
                @if ($product->status == 'available' && $product->stock > 0)
                    <button class="btn btn-success mt-3" onclick="addToCart({{ $product->id }})">Add to Cart</button>
                @else
                    <button class="btn btn-secondary mt-3" disabled>Out of Stock</button>
                @endif
            </div>
        </div>
    </div>
    <script>
        function addToCart(productId) {
            let product = {
                id: productId,
                name: "{{ $product->name_product }}",
                price: {{ $product->price }},
                quantity: 1,
                status: "{{ $product->status }}",
                image: "{{ asset('storage/' . $product->image) }}"
            };

            // Ambil data keranjang dari Local Storage (jika ada)
            let cart = localStorage.getItem('cart') ? JSON.parse(localStorage.getItem('cart')) : [];

            // Cek apakah produk sudah ada di keranjang
            let productIndex = cart.findIndex(p => p.id === productId);

            if (productIndex === -1) {
                // Jika produk belum ada di keranjang, tambahkan ke array cart
                cart.push(product);
            } else {
                // Jika produk sudah ada, Anda bisa menambahkan logic untuk update qty atau alert
                alert('Product is already in the cart!');
            }

            // Simpan kembali array cart ke Local Storage
            localStorage.setItem('cart', JSON.stringify(cart));

            // Notifikasi berhasil menambahkan ke keranjang
            alert('Product added to cart!');
        }
    </script>
@endsection
