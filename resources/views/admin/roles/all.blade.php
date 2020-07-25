@extends('admin.layouts.app')
@section('title')All Roles @endsection
@section('page_title')All Roles @endsection
@section('content')
    @include('admin.partials.error_message')
    @include('admin.partials.datatable', [ 'model_name' => 'ROLE', 'delete_button' => 1, 'addnew_route' => route('role_create'), 'cache_delete_route' => route('products_all_with_categories')])
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
        @forelse($roles as $role)
            <tr>
                @include('admin.partials.select_checkbox',['id' => $role['id']])
                <td>{{$role['name']}}</td>
                @include('admin.partials.action', [ 'item' => $role, 'item_edit_route' => 'role_edit', 'item_delete_route' => 'role_delete'])
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
    @include('admin.includes.scripts.common.datatable', ['model_name' => 'ROLE', 'delete_button' => 1, 'item_delete_route' => 'role_delete'])
@endsection
