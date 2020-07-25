@extends('admin.layouts.app')
@section('title')Add New Client @endsection
@section('page_title')Add New Client @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('client_store')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('POST')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{old('name')}}">
                </div>
                <div class="form-group">
                    <label>Link</label>
                    <input type="url" name="link" class="form-control" value="{{old('link')}}">
                </div>
                <div class="form-group">
                    <label>Grey Image</label>
                    <input type="file" name="grey_image" class="form-control">
                </div>
                <div class="form-group">
                    <label>Colored Image</label>
                    <input type="file" name="colored_image" class="form-control">
                </div>

            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </div>
    </form>
@endsection