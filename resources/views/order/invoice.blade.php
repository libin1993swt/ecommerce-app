@extends('layout')
@section('content')
<div class="push-top">
    <table class="table">
        <tr>
            <td>Order ID</td>
            <td> {{ $order->id }} </td>
        </tr>
        <tr>
            <td>Products</td>
            <td>
                @forelse($orderDetails as $key => $details)
                    {{ ++$key }}.{{ $details->product->name }} x {{ $details->quantity }} = {{ $details->product->price * $details->quantity  }} <?php echo "<br>"; ?>
                @empty
                @endforelse
            </td>
        </tr>
        <tr>
            <td>Total</td>
            <td> {{ $netTotal }} </td>
        </tr>
    </table>
</div>
@endsection