@extends('admin.layouts.app')
@section('title')Messages @endsection
@section('page_title')All Messages @endsection
@section('content')
    @include('admin.partials.error_message')
    @include('admin.partials.datatable', [ 'model_name' => 'MESSAGE', 'delete_button' => 1, 'cache_delete_route' => route('messages_all'), 'mark_as_read' => 1, 'mark_as_unread' => 1])
    <table id="dataTable1" class="table table-bordered table-hover">
        <thead>
        <tr>
            @include('admin.partials.selectall_checkbox')
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Created / Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($messages as $message)
            <tr>
                @include('admin.partials.select_checkbox',['id' => $message['id']])
                <td>{{$message['fname']}} {{$message['lname']}}</td>
                <td>{{$message['email']}}</td>
                <td>{{$message['subject']}}</td>
                <td> {!! ($message['status'] == '1') ? "<span class='badge badge-success'>Read</span>" : "<span class='badge badge-warning'>UnRead</span>" !!}</td>
                @include('admin.partials.action', [ 'item' => $message, 'item_delete_route' => 'message_delete'])
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
    @include('admin.includes.scripts.common.datatable', ['model_name' => 'MESSAGE', 'delete_button' => 1, 'item_delete_route'
                => 'message_delete', 'mark_as_read' => 1, 'mark_as_unread' => 1, 'mark_as_read_route'
                => 'mark_as_read', 'mark_as_unread_route'
                => 'mark_as_unread'])
@endsection