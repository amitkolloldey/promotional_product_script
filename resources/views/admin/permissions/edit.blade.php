@extends('admin.layouts.app')
@section('title')Edit Permission @endsection
@section('page_title')Edit Permission @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('permission_update',$permission['id'])}}" method="post">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <div class="form-group">
            <label>Permission Name</label>
            <input type="text" class="form-control" placeholder="edit product" name="name"
                   value="{{ old('name', $permission['name']) }}">
            <small class="text-muted">Ex: edit product</small>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection
