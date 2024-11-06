@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Your Cart</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="cart-items">
                {{-- List product di keranjang --}}
            </tbody>
        </table>

        {{-- Total --}}
        <div class="text-end">
            <h4>Total: Rp <span id="total-price">0</span></h4>
        </div>

        {{-- Tombol Checkout --}}
        <div class="text-end">
            <button class="btn btn-success mt-3 mb-5" id="checkout-button" disabled>Proceed to Checkout</button>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Ambil data cart dari localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let cartItemsContainer = document.getElementById('cart-items');
        let totalPriceContainer = document.getElementById('total-price');
        let checkoutButton = document.getElementById('checkout-button');

        // Fungsi untuk menampilkan item di cart
        function displayCartItems() {
            let totalPrice = 0;
            cartItemsContainer.innerHTML = ''; // Kosongkan kontainer sebelum diisi ulang

            if (cart.length === 0) {
                // Jika keranjang kosong
                cartItemsContainer.innerHTML = '<tr><td colspan="7" class="text-center">Your cart is empty.</td></tr>';
                checkoutButton.disabled = true; // Disable tombol checkout jika tidak ada item di cart
                return;
            }

            cart.forEach((item, index) => {
                let subtotal = item.price * item.quantity;
                totalPrice += subtotal;

                // Tambahkan row item ke tabel
                cartItemsContainer.innerHTML += `
                    <tr>
                        <td><img src="${item.image}" alt="${item.name}" class="img-fluid" style="width: 80px;"></td>
                        <td>${item.name}</td>
                        <td>Rp ${item.price.toLocaleString()}</td>
                        <td>${item.stock}</td>
                        <td>
                            <input type="number" value="${item.quantity}" min="1" max="${item.stock}" class="form-control quantity-input" data-index="${index}">
                        </td>
                        <td>Rp ${subtotal.toLocaleString()}</td>
                        <td><button class="btn btn-danger btn-sm remove-item" data-index="${index}">Remove</button></td>
                    </tr>
                `;
            });

            // Update total harga
            totalPriceContainer.textContent = totalPrice.toLocaleString();
            checkoutButton.disabled = false; // Enable tombol checkout jika ada item
        }

        // Fungsi untuk mengupdate kuantitas item
        function updateQuantity(index, quantity) {
            cart[index].quantity = quantity;
            localStorage.setItem('cart', JSON.stringify(cart));
            displayCartItems();
        }

        // Fungsi untuk menghapus item dari cart
        function removeItem(index) {
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            displayCartItems();
        }

        // Event listener untuk update quantity
        cartItemsContainer.addEventListener('input', function(event) {
            if (event.target.classList.contains('quantity-input')) {
                let index = event.target.getAttribute('data-index');
                let quantity = parseInt(event.target.value);
                if (quantity > 0) {
                    updateQuantity(index, quantity);
                }
            }
        });

        // Event listener untuk tombol remove
        cartItemsContainer.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-item')) {
                let index = event.target.getAttribute('data-index');
                removeItem(index);
            }
        });

        // Panggil fungsi untuk menampilkan item cart
        displayCartItems();

        // Event listener untuk checkout
        checkoutButton.addEventListener('click', function() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }

            $.ajax({
                url: '/checkout',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify({
                    products: cart,
                    total: cart.reduce((acc, item) => acc + (item.price * item.quantity), 0)
                }),
                contentType: 'application/json',
                success: function(data) {
                    if (data.success) {
                        alert('Order placed successfully!');
                        localStorage.removeItem('cart'); // Kosongkan cart setelah order berhasil
                        window.location.href = '/payment/' + data.order_id;
                    } else {
                        alert('Failed to place order.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Something went wrong!');
                }
            });
        });
    </script>
@endsection
