@extends('layouts.main')

@section('content')
    <section id="latest-products" class="product-store py-2 my-2 py-md-5 my-md-5 pt-0">
        <div class="container-md">
            <div class="display-header d-flex align-items-center justify-content-between">
                <h2 class="section-title text-uppercase">All Products</h2>
            </div>
            <div class="product-content padding-small">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5">
                    @foreach ($products as $product)
                        <div class="col mb-4 mb-3">
                            <div class="product-card position-relative">
                                <div class="card-img">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="product-item"
                                        class="product-image img-fluid">
                                    <div class="cart-concern position-absolute d-flex justify-content-center">
                                        <div class="cart-button d-flex gap-2 justify-content-center align-items-center">
                                            <button onclick="onClick({{ $product->id }})" class="btn btn-light">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- cart-concern -->
                                </div>
                                <div class="card-detail d-flex justify-content-between align-items-center mt-3">
                                    <h3 class="card-title fs-6 fw-normal m-0">
                                        <a href="#">{{ $product->name_product }}</a>
                                    </h3>
                                    <span class="card-price fw-bold">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <script>
        function onClick(id) {
            window.location.href = `/product/${id}`;
        }
    </script>
@endsection
