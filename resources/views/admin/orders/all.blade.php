@extends('admin.layouts.app')
@section('title')All Orders @endsection
@section('page_title')All Orders @endsection
@section('content')
@include('admin.partials.error_message')
@include('admin.partials.datatable',['model_name' => 'ORDER', 'delete_button' => 1, 'cache_delete_route' => route('orders_all')])
<table id="dataTable1" class="table table-bordered table-hover">
    <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Order no</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Created By</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            @include('admin.partials.select_checkbox',['id' => $order['id']])
            <td>{{$order['order_no']}}</td>
            <td>{{$order['name']}}</td>
            <td>{{$order['email']}}</td>
            <td>{!! $order['status'] == "pending" ? "<span class='badge badge-warning'>Pending</span>" : $order['status'] !!}</td>
            <td>
                @foreach ($order['users'] as $user)
                {{$user['name']}}
                @endforeach
            </td>
            @include('admin.partials.action', [ 'item' => $order, 'item_edit_route' => 'order_edit',
            'item_delete_route' => 'order_delete'])
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
@include('admin.includes.scripts.common.datatable', ['model_name' => 'ORDER', 'delete_button' => 1, 'item_delete_route'
                => 'order_delete'])
@endsection
