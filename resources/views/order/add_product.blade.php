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