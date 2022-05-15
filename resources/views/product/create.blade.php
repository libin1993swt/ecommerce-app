@extends('layout')
@section('content')
<style>
    .container {
      max-width: 450px;
    }
    .push-top {
      margin-top: 50px;
    }
</style>
<div class="card push-top">
  <div class="card-header">
    @if(isset($product->id))
      Edit Product
    @else
      Add Product
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
      @if(isset($product->id))
        <form method="post" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
        @method('PATCH')
      @else
        <form method="post" action="{{ route('products.store') }}" enctype="multipart/form-data">
      @endif
          <div class="form-group">
              @csrf
              <label for="name">Name</label>
              <input type="text" class="form-control" name="name" value="{{ isset($product->name) ? $product->name : '' }}"/>
          </div>
          <div class="form-group">
              <label for="image">Image</label>
              <input type="file" class="form-control" name="image"/>
          </div>
          <div class="form-group">
              <label for="category_id">Category</label>
              <select class="form-control" name="category_id">
                <option disabled>Select Category</option>
                @forelse($categories as $category)
                    <option value="{{ $category->id }}" 
                    {{ (isset($product->category_id) && ($category->id == $product->category_id)) ? 'selected' : null }}>
                    {{ $category->name }}</option>
                @empty
                    <option>No Category</option>
                @endforelse
              </select>
          </div>
          <div class="form-group">
              <label for="price">Price</label>
              <input type="text" class="form-control" name="price" value="{{ isset($product->price) ? $product->price : '' }}"/>
          </div>
          <button type="submit" class="btn btn-block btn-danger">Add Product</button>
      </form>
  </div>
</div>
@endsection