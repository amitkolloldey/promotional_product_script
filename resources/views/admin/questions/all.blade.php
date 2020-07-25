@extends('admin.layouts.app')
@section('title')All Questions @endsection
@section('page_title')All Questions @endsection
@section('content')
@include('admin.partials.error_message')
@include('admin.partials.datatable',['model_name' => 'QUESTION', 'delete_button' => 1, 'cache_delete_route' => route('questions_all')])
<table id="dataTable1" class="table table-bordered table-hover">
    <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Name</th>
            <th>Email</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($questions as $question)
        <tr>
            @include('admin.partials.select_checkbox',['id' => $question['id']])
            <td>{{$question['name']}}</td>
            <td>{{$question['email']}}</td>
            @include('admin.partials.action', [ 'item' => $question, 'item_edit_route' => 'question_edit',
            'item_delete_route' => 'question_delete'])
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
@include('admin.includes.scripts.common.datatable', ['model_name' => 'QUESTION', 'delete_button' => 1, 'item_delete_route' => 'question_delete'])
@endsection
