<tr>
    <td>
        <input type="hidden" name="order_details_id[]" value="{{ $details->id }}" />
        <select class="form-control" name="product_id[]">
            <option disabled>Select Product</option>
            @forelse($products as $product)
            <option value="{{ $product->id }}" {{ (isset($details->id) && ($details->product_id == $product->id)) ? 'selected' : null }}>
                {{ $product->name }}
            </option>
            @empty
            <option>No Product</option>
            @endforelse
        </select>
    </td>
    <td>
        <input type="text" class="form-control" name="quantity[]" value="{{ isset($details->quantity) ? $details->quantity : '' }}" />
    </td>
    <td>
        <button type="button" class="btn-sm btn-danger order-product-btn remove-product">Remove</button>
    </td>
</tr>