@extends('layout')
@section('content')
<style>
  .push-top {
    margin-top: 50px;
  }
</style>
<div>
    <a href="{{ route('products.create') }}" class="btn">Add Product</a>
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
          <td>Product Name</td>
          <td>Category</td>
          <td>Price</td>
          <td class="text-center">Action</td>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
        <tr>
            <td>{{$product->id}}</td>
            <td>{{$product->name}}</td>
            <td>{{$product->category->name}}</td>
            <td>{{$product->price}}</td>
            <td class="text-center">
                <a href="{{ route('products.edit', $product->id)}}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('products.destroy', $product->id)}}" method="post" style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                  </form>
            </td>
        </tr>
        @empty
            <tr><td colspan="5" style='text-align:center; vertical-align:middle'>No Products</td></tr>
        @endforelse
    </tbody>
  </table>
<div>
@endsection