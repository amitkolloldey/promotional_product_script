@extends('admin.layouts.app')
@section('title')All Personalisation Options @endsection
@section('page_title')All Personalisation Options @endsection
@section('content')
@include('admin.partials.error_message')
@include('admin.partials.datatable', [ 'model_name' => 'PERSONALISATIONOPTION', 'delete_button' => 1, 'addnew_route' =>
route('personalisation_option_create'), 'cache_delete_route' => route('products_all_with_categories') ])
<table id="dataTable1" class="table table-bordered table-hover">
    <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Name</th>
            <th>Status</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($personalisationoptions as $personalisationoption)
        <tr>
            @include('admin.partials.select_checkbox',['id' => $personalisationoption['id']])
            <td>{{$personalisationoption['name']}}</td>
            <td> {!! ($personalisationoption['status'] == '1') ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-warning'>Not Active</span>" !!}
            </td>
            @include('admin.partials.action', [ 'item' => $personalisationoption, 'item_edit_route' =>
            'personalisation_option_edit', 'item_delete_route' => 'personalisation_option_delete'])
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
@include('admin.includes.scripts.common.datatable', ['model_name' => 'PERSONALISATIONOPTION', 'delete_button' => 1, 'item_delete_route'
                => 'personalisation_option_delete'])
@endsection
