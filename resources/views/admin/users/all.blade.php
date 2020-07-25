@extends('admin.layouts.app')
@section('title')All Users @endsection
@section('page_title')All Users @endsection
@section('content')
@include('admin.partials.error_message')
@include('admin.partials.datatable', [ 'model_name' => 'USER', 'delete_button' => 1, 'addnew_route' =>
route('user_create'), 'cache_delete_route' => route('users_all')])
<table id="dataTable1" class="table table-bordered table-hover">
    <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Name</th>
            <th>EMail</th>
            <th>Role</th>
            <th>Status</th>
            <th>Email Status</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
        <tr>
            @include('admin.partials.select_checkbox',['id' => $user['id']])
            <td>{{$user['name']}}</td>
            <td>{{$user['email']}}</td>
            <td>
                @forelse($user['roles'] as $role)
                {{ucfirst($role['name'])}}
                @empty
                <p>None</p>
                @endforelse
            </td>
            <td> {!! ($user['status'] == '1') ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-warning'>Not Active</span>" !!}</td>
            <td>
                {!! isset($user['email_verified_at']) ? "<span class='badge badge-success'>Verified</span>" : "<span class='badge badge-danger'>Not Verified</span>" !!}
            </td>
            @include('admin.partials.action', [ 'item' => $user, 'item_edit_route' => 'user_edit', 'item_delete_route'
            => 'user_delete'])
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
    @include('admin.includes.scripts.common.datatable', ['model_name' => 'USER', 'delete_button' => 1, 'item_delete_route'
            => 'user_delete'])
@endsection
