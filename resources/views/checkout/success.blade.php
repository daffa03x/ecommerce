@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <h2>Checkout Success</h2>
        <p>Terima kasih sudah melakukan checkout. Pesanan anda sedang diproses.</p>
        <p>Anda akan diarahkan ke halaman utama dalam beberapa detik.</p>
    </div>

    <script>
        window.setTimeout(function() {
            window.location.href = "{{ url('/history') }}";
        }, 3000);
    </script>

    <style>
        .container {
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
@endsection
