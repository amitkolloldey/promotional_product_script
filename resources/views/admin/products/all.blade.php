@extends('admin.layouts.app')
@section('title')All Products @endsection
@section('page_title')All Products @endsection
@section('content')
@include('admin.partials.error_message')
@include('admin.partials.datatable', [ 'model_name' => 'PRODUCT', 'delete_button' => 1, 'make_popular_button' => 1,
'make_new_button' => 1, 'undo_popular_button' => 1, 'undo_new_button' => 1, 'make_discontinued_stock_button' => 1,
'undo_discontinued_stock_button' => 1, 'addnew_route' => route('product_create'), 'cache_delete_route' => route('products_all_with_categories')])
<table id="products-table" class="table table-bordered table-hover">
    <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Main Image</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Product Type</th>
            <th>Product Code</th>
            <th>Category</th>
            <th>Is New</th>
            <th>Is Popular</th>
            <th>Is Discontinued Stock</th>
            <th>Status</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
</table>
@endsection
@section('scripts')

    @include('admin.includes.scripts.common.datatable', ['model_name' => 'PRODUCT', 'delete_button' => 1,
    'make_popular_button' => 1, 'make_new_button' => 1, 'undo_popular_button' => 1, 'undo_new_button' =>
    1,'make_discontinued_stock_button' => 1, 'undo_discontinued_stock_button' => 1, 'item_delete_route'
                => 'product_delete'])

@endsection
