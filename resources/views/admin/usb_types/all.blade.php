@extends('admin.layouts.app')
@section('title')All USB Types @endsection
@section('page_title')All USB Types @endsection
@section('content')
@include('admin.partials.error_message')
@include('admin.partials.datatable', [ 'model_name' => 'USBTYPE', 'delete_button' => 1, 'addnew_route' =>
route('usb_type_create'), 'cache_delete_route' => route('products_all_with_categories')])
<table id="dataTable1" class="table table-bordered table-hover">
    <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Title</th>
            <th>Status</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($usb_types as $usb_type)
        <tr>
            @include('admin.partials.select_checkbox',['id' => $usb_type['id']])
            <td>{{$usb_type['title']}}</td>
            <td> {!! ($usb_type['status'] == '1') ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-warning'>Not Active</span>" !!}
            </td>
            @include('admin.partials.action', [ 'item' => $usb_type, 'item_edit_route' => 'usb_type_edit',
            'item_delete_route' => 'usb_type_delete'])
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
@include('admin.includes.scripts.common.datatable', ['model_name' => 'USBTYPE', 'delete_button' => 1, 'item_delete_route' => 'usb_type_delete'])
@endsection
