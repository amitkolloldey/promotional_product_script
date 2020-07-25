@extends('admin.layouts.app')
@section('title')All Permissions @endsection
@section('page_title')All Permissions @endsection
@section('content')
    @include('admin.partials.error_message')
    @include('admin.partials.datatable', [ 'model_name' => 'PERMISSION', 'delete_button' => 1, 'addnew_route' => route('permission_create'), 'cache_delete_route' => route('products_all_with_categories')])
    <table id="dataTable1" class="table table-bordered table-hover">
        <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Name</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($permissions as $permission)
            <tr>
                @include('admin.partials.select_checkbox',['id' => $permission['id']])
                <td>{{$permission['name']}}</td>
                @include('admin.partials.action', [ 'item' => $permission, 'item_edit_route' => 'permission_edit', 'item_delete_route' => 'permission_delete'])
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
    @include('admin.includes.scripts.common.datatable', ['model_name' => 'PERMISSION', 'delete_button' => 1, 'item_delete_route' => 'permission_delete'])
@endsection
