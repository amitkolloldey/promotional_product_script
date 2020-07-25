@extends('admin.layouts.app')
@section('title')Add New Quantity @endsection
@section('page_title')Add New Quantity @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('quantity_store')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('POST')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <div class="form-group">
                    <label>Title</label>
                    <input name="title" class="form-control" id="title" value="{{old('title')}}">
                </div>
                <div class="form-group">
                    <label>Minimum Quantity</label>
                    <input type="number" name="min_qty" class="form-control" value="{{old('min_qty')}} ">
                </div>
                <div class="form-group">
                    <label>Maximum Quantity</label>
                    <input type="number" name="max_qty" class="form-control" value="{{old('max_qty')}} ">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </div>
    </form>
@endsection