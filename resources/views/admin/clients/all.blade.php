@extends('admin.layouts.app')
@section('title')All Clients @endsection
@section('page_title')All Clients @endsection
@section('content')
    @include('admin.partials.error_message')
    @include('admin.partials.datatable',['model_name' => 'CLIENT', 'delete_button' => 1, 'addnew_route' =>
    route('client_create'), 'cache_delete_route' => route('products_all_with_categories')])
    <table id="dataTable1" class="table table-bordered table-hover">
        <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Name</th>
            <th>Link</th>
            <th>Created / Updated</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($clients as $client)
            <tr>
                @include('admin.partials.select_checkbox',['id' => $client['id']])
                <td>{{$client['name']}}</td>
                <td>{{$client['link']}}</td>
                @include('admin.partials.action', [ 'item' => $client, 'item_edit_route' => 'client_edit',
                'item_delete_route' => 'client_delete'])
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
    @include('admin.includes.scripts.common.datatable', ['model_name' => 'CLIENT', 'delete_button' => 1, 'item_delete_route'
                    => 'client_delete'])
@endsection
