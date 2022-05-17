
<div class="sidebar">
  <a class="{{ (request()->segment(1) == 'products') ? 'active' : '' }}" href="{{ route('products.index') }}">Products</a>
  <a class="{{ (request()->segment(1) == 'orders') ? 'active' : '' }}" href="{{ route('orders.index') }}">Orders</a>
</div>