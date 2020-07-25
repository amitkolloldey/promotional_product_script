@extends('admin.layouts.app')
@section('title')Update Quantity @endsection
@section('page_title')Update Quantity @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('quantity_update',$quantity->id)}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <div class="form-group">
                    <label>Title</label>
                    <input name="title" class="form-control" id="title" value="{{old('title',$quantity->title)}}">
                </div>
                <div class="form-group">
                    <label>Minimum Quantity</label>
                    <input type="number" name="min_qty" class="form-control"
                           value="{{old('min_qty',$quantity->min_qty)}}">
                </div>
                <div class="form-group">
                    <label>Maximum Quantity</label>
                    <input type="number" name="max_qty" class="form-control"
                           value="{{old('max_qty',$quantity->max_qty)}}">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection