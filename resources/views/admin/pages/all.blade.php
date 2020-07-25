@extends('admin.layouts.app')
@section('title')All Pages @endsection
@section('page_title')All Pages @endsection
@section('content')
    @include('admin.partials.error_message')
    @include('admin.partials.datatable', [ 'model_name' => 'PAGE', 'delete_button' => 1, 'addnew_route' => route('page_create'),'cache_delete_route' => route('products_all_with_categories')])
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
        @forelse($pages as $page)
            <tr>
                @include('admin.partials.select_checkbox',['id' => $page['id']])
                <td>{{$page['title']}}</td>
                <td>{{$page['slug']}}</td>
                <td> {!! ($page['status'] == '1') ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-warning'>Not Active</span>" !!}</td>
                @include('admin.partials.action', [ 'item' => $page, 'item_edit_route' => 'page_edit', 'item_delete_route' => 'page_delete'])
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
    @include('admin.includes.scripts.common.datatable', ['model_name' => 'PAGE', 'delete_button' => 1, 'item_delete_route' => 'page_delete'])
@endsection