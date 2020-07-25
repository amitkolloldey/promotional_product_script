@extends('admin.layouts.app')
@section('title')Edit Client @endsection
@section('page_title')Edit Client @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('client_update',$client['id'])}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{old('name',$client['name'])}}">
                </div>
                <div class="form-group">
                    <label>Link</label>
                    <input type="url" name="link" class="form-control" value="{{old('link',$client['link'])}}">
                </div>
                <div class="form-group">
                    <label>Grey Image</label>
                    <p>{{$client['grey_image']}}</p>
                    <input type="file" name="grey_image" class="form-control">
                </div>
                <div class="form-group">
                    <label>Colored Image</label>
                    <p>{{$client['colored_image']}}</p>
                    <input type="file" name="colored_image" class="form-control">
                </div>

            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection