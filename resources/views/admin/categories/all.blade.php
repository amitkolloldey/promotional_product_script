@extends('admin.layouts.app')
@section('title')All Categories @endsection
@section('page_title')All Categories @endsection
@section('content')
    @include('admin.partials.error_message')
    @include('admin.partials.datatable', ['model_name' => 'CATEGORY', 'delete_button' => 1, 'addnew_route' => route('category_create'), 'cache_delete_route' => route('categories_all')])
    <table id="dataTable1" class="table table-bordered table-hover">
        <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Name</th>
            <th>Slug</th>
            <th>Parent</th>
            <th>Status</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($categories as $category)
            <tr>
                @include('admin.partials.select_checkbox',['id' => $category['id']])
                <td>{{$category['name']}}</td>
                <td>{{$category['slug']}}</td>
                <td>
                    @if ($category['parent_id'])
                    {{ $categories[$category['parent_id']]['name'] }}
                    @else
                    None
                    @endif
                </td>
                <td> {!! ($category['status'] == '1') ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-warning'>Not Active</span>" !!}</td>
                @include('admin.partials.action', [ 'item' => $category, 'item_edit_route' => 'category_edit', 'item_delete_route' => 'category_delete'])
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
    @include('admin.includes.scripts.common.datatable', ['model_name' => 'CATEGORY', 'delete_button' => 1, 'item_delete_route'
                => 'category_delete'])
@endsection
