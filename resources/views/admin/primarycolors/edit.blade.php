@extends('admin.layouts.app')
@section('title')Edit Primary Color @endsection
@section('page_title')Edit Primary Color @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('primary_color_update',$primarycolor->id)}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('POST')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{old('name', $primarycolor->name)}}">
                </div>
                <div class="form-group">
                    <label>Select Color</label>&nbsp;&nbsp;
                    <input type="color" name="color_code" value="{{old('color_code', $primarycolor->color_code)}}">
                </div>

            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection