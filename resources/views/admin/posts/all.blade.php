@extends('admin.layouts.app')
@section('title')All Posts @endsection
@section('page_title')All Posts @endsection
@section('content')
    @include('admin.partials.error_message')
    @include('admin.partials.datatable', [ 'model_name' => 'POST', 'delete_button' => 1, 'addnew_route' => route('post_create'),'cache_delete_route' => route('products_all_with_categories')])
    <table id="dataTable1" class="table table-bordered table-hover">
        <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Title</th>
            <th>Slug</th>
            <th>Status</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($posts as $post)
            <tr>
                @include('admin.partials.select_checkbox',['id' => $post['id']])
                <td>{{$post['title']}}</td>
                <td>{{$post['slug']}}</td>
                <td> {!! ($post['status'] == '1') ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-warning'>Not Active</span>" !!}</td>
                @include('admin.partials.action', [ 'item' => $post, 'item_edit_route' => 'post_edit', 'item_delete_route' => 'post_delete'])
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
    @include('admin.includes.scripts.common.datatable', ['model_name' => 'POST', 'delete_button' => 1, 'item_delete_route' => 'post_delete'])
@endsection