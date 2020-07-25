@extends('admin.layouts.app')
@section('title')All Personalisation Types @endsection
@section('page_title')All Personalisation Types @endsection
@section('content')
@include('admin.partials.error_message')
@include('admin.partials.datatable', [ 'model_name' => 'PERSONALISATIONTYPE', 'delete_button' => 1, 'addnew_route' =>
route('personalisation_type_create'), 'cache_delete_route' => route('products_all_with_categories')])
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
        @forelse($personalisationtypes as $personalisationtype)
        <tr>
            @include('admin.partials.select_checkbox',['id' => $personalisationtype['id']])
            <td>{{$personalisationtype['name']}}</td>
            @include('admin.partials.action', ['item' => $personalisationtype, 'item_edit_route' =>
            'personalisation_type_edit', 'item_delete_route' => 'personalisation_type_delete'])
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
@include('admin.includes.scripts.common.datatable', ['model_name' => 'PERSONALISATIONTYPE', 'delete_button' => 1, 'item_delete_route' => 'personalisation_type_delete'])
@endsection
