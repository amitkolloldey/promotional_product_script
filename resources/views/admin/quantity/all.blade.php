@extends('admin.layouts.app')
@section('title')All Quantities @endsection
@section('page_title')All Quantities @endsection
@section('content')
@include('admin.partials.error_message')
@include('admin.partials.datatable', [ 'model_name' => 'QUANTITY', 'delete_button' => 1, 'addnew_route' =>
route('quantity_create'), 'cache_delete_route' => route('products_all_with_categories')])
<table id="dataTable1" class="table table-bordered table-hover">
    <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Title</th>
            <th>Min</th>
            <th>Max</th>
            <th>Status</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($quantities as $quantity)
        <tr>
            @include('admin.partials.select_checkbox',['id' => $quantity['id']])
            <td>{{$quantity['title']}}</td>
            <td>{{$quantity['min_qty']}}</td>
            <td>{{$quantity['max_qty']}}</td>
            <td> {!! ($quantity['status'] == '1') ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-warning'>Not Active</span>" !!}</td>
            @include('admin.partials.action', [ 'item' => $quantity, 'item_edit_route' => 'quantity_edit', 'item_delete_route' => 'quantity_delete'])
        </tr>
        @empty
        <tr>
            No Data Found
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
@section('scripts')
@include('admin.includes.scripts.common.datatable', ['model_name' => 'QUANTITY', 'delete_button' => 1, 'item_delete_route'
                => 'quantity_delete'])
@endsection
