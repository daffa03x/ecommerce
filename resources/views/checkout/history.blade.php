@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <h2>Order {{ auth()->user()->name }}</h2>
        <table class="table table-striped mt-4">
            <tr>
                <th>No</th>
                <th>Order Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td>
                        @if ($order->status == 'pending')
                            <span class="badge bg-warning">{{ $order->status }}</span>
                        @elseif ($order->status == 'success')
                            <span class="badge bg-success">{{ $order->status }}</span>
                        @elseif ($order->status == 'failed')
                            <span class="badge bg-danger">{{ $order->status }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($order->status == 'pending')
                            <a href="{{ url('payment/' . $order->id) }}" class="btn btn-success">Payment</a>
                        @else
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
