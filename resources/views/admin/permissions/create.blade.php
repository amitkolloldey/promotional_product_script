@extends('admin.layouts.app')
@section('title')Add New Permission @endsection
@section('page_title')Add New Permission @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('permission_store')}}" method="post">
        {{csrf_field()}}
        {{method_field('POST')}}
        <div class="form-group">
            <label>Permission Name</label>
            <input type="text" class="form-control" placeholder="edit product" name="name" value="{{ old('name') }}">
            <small class="text-muted">Ex: edit product</small>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection
