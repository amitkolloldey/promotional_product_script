@extends('admin.layouts.app')
@section('title')All Manufacturers @endsection
@section('page_title')All Manufacturers @endsection
@section('content')
@include('admin.partials.error_message')
@include('admin.partials.datatable',['model_name' => 'MANUFACTURER', 'delete_button' => 1, 'addnew_route' =>
route('manufacturer_create'), 'cache_delete_route' => route('products_all_with_categories')])
<table id="dataTable1" class="table table-bordered table-hover">
    <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($manufacturers as $manufacturer)
        <tr>
            @include('admin.partials.select_checkbox',['id' => $manufacturer['id']])
            <td>{{$manufacturer['name']}}</td>
            <td>{{$manufacturer['address']}}</td>
            <td>{{$manufacturer['email']}}</td>
            @include('admin.partials.action', [ 'item' => $manufacturer, 'item_edit_route' => 'manufacturer_edit',
            'item_delete_route' => 'manufacturer_delete'])
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
@include('admin.includes.scripts.common.datatable', ['model_name' => 'MANUFACTURER', 'delete_button' => 1, 'item_delete_route'
                => 'manufacturer_delete'])
@endsection
