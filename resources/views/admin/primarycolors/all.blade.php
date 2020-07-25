@extends('admin.layouts.app')
@section('title')All Primary Colors @endsection
@section('page_title')All Primary Colors @endsection
@section('content')
@include('admin.partials.error_message')
@include('admin.partials.datatable', [ 'model_name' => 'PRIMARYCOLOR', 'delete_button' => 1, 'addnew_route' =>
route('primary_color_create'), 'cache_delete_route' => route('products_all_with_categories')])
<table id="dataTable1" class="table table-bordered table-hover">
    <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Name</th>
            <th>Color Code</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($primarycolors as $primarycolor)
        <tr>
            @include('admin.partials.select_checkbox',['id' => $primarycolor['id']])
            <td>{{$primarycolor['name']}}</td>
            <td>{{$primarycolor['color_code']}}</td>
            @include('admin.partials.action', [ 'item' => $primarycolor, 'item_edit_route' => 'primary_color_edit',
            'item_delete_route' => 'primary_color_delete'])
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
@include('admin.includes.scripts.common.datatable', ['model_name' => 'PRIMARYCOLOR', 'delete_button' => 1, 'item_delete_route'
                => 'primary_color_delete'])
@endsection
