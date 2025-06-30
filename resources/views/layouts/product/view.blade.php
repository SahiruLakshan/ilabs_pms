@extends('root')

@section('content')

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Product List</h3>
    <a href="{{ route('product.add') }}" class="btn btn-primary">
      Add New Product
    </a>
  </div>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Price (LKR)</th>
        <th>Image</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $product)
      <tr>
        <td>{{ $product->name }}</td>
        <td>{{ $product->description }}</td>
        <td>{{ number_format($product->price, 2) }}</td>
        <td>
          @if($product->image)
            <img src="{{ asset($product->image) }}" alt="Product Image" class="product-img rounded" width="100" height="100">
          @else
            <span class="text-muted">No image</span>
          @endif
        </td>
        <td>
          <a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning">
            Edit
          </a>
          <a href="{{ route('product.delete', $product->id) }}" class="btn btn-danger">
            Delete
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>


@endsection