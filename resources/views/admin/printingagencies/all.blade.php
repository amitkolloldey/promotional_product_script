@extends('admin.layouts.app')
@section('title')All Printing Agencies @endsection
@section('page_title')All Printing Agencies @endsection
@section('content')
@include('admin.partials.error_message')
@include('admin.partials.datatable', [ 'model_name' => 'PRINTINGAGENCY', 'delete_button' => 1, 'addnew_route' =>
route('printing_agency_create'), 'cache_delete_route' => route('products_all_with_categories')])
<table id="dataTable1" class="table table-bordered table-hover">
    <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Name</th>
            <th>email</th>
            <th>Status</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($printingagencies as $printingagency)
        <tr>
            @include('admin.partials.select_checkbox',['id' => $printingagency['id']])
            <td>{{$printingagency['name']}}</td>
            <td>{{$printingagency['email']}}</td>
            <td> {!! ($printingagency['status'] == '1') ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-warning'>Not Active</span>" !!}</td>
            @include('admin.partials.action', [ 'item' => $printingagency, 'item_edit_route' => 'printing_agency_edit',
            'item_delete_route' => 'printing_agency_delete'])
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
@include('admin.includes.scripts.common.datatable', ['model_name' => 'PRINTINGAGENCY', 'delete_button' => 1, 'item_delete_route' => 'printing_agency_delete'])
@endsection
