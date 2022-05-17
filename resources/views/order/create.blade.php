@extends('layout')
@section('content')
<style>
    .container {
        max-width: 450px;
    }

    .push-top {
        margin-top: 10px;
    }
</style>
<div class="card push-top">
    <div class="card-header">
        @if(isset($order->id))
        Edit Order
        @else
        Add Order
        @endif
    </div>
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div><br />
        @endif
        @if(isset($order->id))
        <form method="post" action="{{ route('orders.update', $order->id) }}" enctype="multipart/form-data">
            @method('PATCH')
            @else
            <form method="post" action="{{ route('orders.store') }}" enctype="multipart/form-data">
                @endif
                <div class="form-group">
                    @csrf
                    <label for="customer_name">Customer Name</label>
                    <input type="hidden" name="customer_id" value="{{ isset($order->customer->id) ? $order->customer->id : '' }}">
                    <input type="text" class="form-control" name="customer_name" value="{{ isset($order->customer->name) ? $order->customer->name : '' }}" />
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="{{ isset($order->customer->phone) ? $order->customer->phone : '' }}" />
                </div>
                <div class="form-group">
                    <table class="table" id="order_table">
                        <thead>
                            <th> Product </th>
                            <th> Quantity </th>
                            <th></th>
                        </thead>
                        <tbody>
                            @if(isset($order->order_details))
                                @foreach($order->order_details as $details)
                                    @include('order.invoice_products')
                                @endforeach
                            @endif
                            <tr>
                                <td>
                                    <select class="form-control" name="product_id[]">
                                        <option disabled>Select Product</option>
                                        @forelse($products as $product)
                                        <option value="{{ $product->id }}" {{ (isset($order->id) && ($order->id == $product->id)) ? 'selected' : null }}>
                                            {{ $product->name }}
                                        </option>
                                        @empty
                                        <option>No Product</option>
                                        @endforelse
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="quantity[]" value="{{ isset($order->quantity) ? $quantity->quantity : '' }}" />
                                </td>
                                <td><button type="button" class="btn-sm btn-success order-product-btn add-product">Add</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-block btn-danger">
                    @if(isset($order->id))
                        Update Order
                    @else
                        Add Order
                    @endif
                </button>
            </form>
    </div>
</div>
@endsection

@section('js-script')
<script type="text/javascript">
    $('#order_table').on('click', '.add-product', function() {
        var $table = $(this).closest('table'),
            $row = $(this).closest('tr'),
            $newRow = $row.clone();

        $table.append($('<tbody>').append($newRow));
    });
</script>
@endsection