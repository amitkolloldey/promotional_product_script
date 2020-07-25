@extends('admin.layouts.app')
@section('title')Add New Product @endsection
@section('page_title')Add New Product @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('product_store')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('POST')}}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" placeholder="Name" name="name" value="{{old('name')}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Product Type</label>
                    <select class="form-control" name="product_type">
                        <option value="promo_product" {{old('product_type') == "promo_product" ? "selected" : ""}}>Promo Product</option>
                        <option value="usb_product" {{old('product_type') == "usb_product" ? "selected" : ""}}>USB Product</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Product Code</label>
                    <input type="text" class="form-control" placeholder="Product Code" name="product_code" value="{{old('product_code')}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Product Main Image</label>
                    <input type="file" name="main_image" class="form-control">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Select Manufacturer</label>
                    <br>
                    @foreach($manufacturers as $manufacturer)
                        <div class="custom-control custom-switch" style="display: inline-block">
                            <input type="radio" value="{{$manufacturer['id']}}" name="manufacturer"
                                   class="custom-control-input" id="manufacturer{{$manufacturer['id']}}"
                                   @if(old('manufacturer') == $manufacturer['id']) checked @endif>
                            <label class="custom-control-label" for="manufacturer{{$manufacturer['id']}}">
                                {{$manufacturer['name']}}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <div class="form-group pull-right">
                    <button type="submit" class="btn btn-primary">Next Step</button>
                </div>
            </div>
        </div>
    </form>
@endsection
