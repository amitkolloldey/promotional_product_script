@extends('admin.layouts.app')
@section('title')Update USB Type @endsection
@section('page_title')Update USB Type @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('usb_type_update',$usb_type->id)}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <div class="form-group">
                    <label>Title</label>
                    <input name="title" class="form-control" id="title" value="{{old('title',$usb_type->title)}}">
                </div>
                <div class="form-group form-check-inline">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="status" value="1" class="custom-control-input"
                               id="status" {{$usb_type->status == '1' ? "checked" : ""}}>
                        <label class="custom-control-label" for="status"> Status</label>&nbsp; &nbsp;&nbsp;
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection