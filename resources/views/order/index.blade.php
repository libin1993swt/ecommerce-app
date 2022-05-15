@extends('layout')
@section('content')
<style>
  .push-top {
    margin-top: 50px;
  }
</style>
<div>
    <a href="{{ route('orders.create') }}" class="btn">Add Order</a>
</div>
<div class="push-top">
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif
  <table class="table">
    <thead>
        <tr class="table-warning">
          <td>ID</td>
          <td>Order Id</td>
          <td>Customer Name</td>
          <td>Phone</td>
          <td>Net Amount</td>
          <td>Order Date</td>
          <td class="text-center">Action</td>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>{{$order->id}}</td>
            <td>{{$order->id}}</td>
            <td>{{$order->customer->name}}</td>
            <td>{{$order->customer->phone}}</td>
            <td>{{$order->customer->phone}}</td>
            <td>{{ date('d M Y',strtotime($order->date)) }}</td>
            <td class="text-center">
                <a href="{{ route('orders.edit', $order->id)}}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('orders.destroy', $order->id)}}" method="post" style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                </form>
                <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-warning btn-sm">Invoice</a>
            </td>
        </tr>
        @empty
            <tr><td colspan="7" style='text-align:center; vertical-align:middle'>No Orders</td></tr>
        @endforelse
    </tbody>
  </table>
<div>
@endsection