@extends('admin.layouts.app')
@section('title')All Quotations @endsection
@section('page_title')All Quotations @endsection
@section('content')
@include('admin.partials.error_message')
@include('admin.partials.datatable',['model_name' => 'QUOTATION', 'delete_button' => 1, 'cache_delete_route' => route('quotations_all')])
<table id="dataTable1" class="table table-bordered table-hover">
    <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($quotations as $quotation)
        <tr>
            @include('admin.partials.select_checkbox',['id' => $quotation['id']])
            <td>{{$quotation['name']}}</td>
            <td>{{$quotation['email']}}</td>
            <td>{!! $quotation['status'] == "pending" ? "<span class='badge badge-warning'>Pending</span>" : $quotation['status'] !!}</td>
            @include('admin.partials.action', [ 'item' => $quotation, 'item_edit_route' => 'quotation_edit',
            'item_delete_route' => 'quotation_delete'])
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
@include('admin.includes.scripts.common.datatable', ['model_name' => 'QUOTATION', 'delete_button' => 1, 'item_delete_route'
                => 'quotation_delete'])
@endsection
