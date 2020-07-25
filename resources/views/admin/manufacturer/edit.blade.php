@extends('admin.layouts.app')
@section('title')Update Manufacturer @endsection
@section('page_title')Update Manufacturer @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('manufacturer_update',$manufacturer->id)}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{old('name',$manufacturer->name)}}">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control">{{old('address',$manufacturer->address)}}</textarea>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{old('email',$manufacturer->email)}} ">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" class="form-control" value="{{old('phone',$manufacturer->phone)}} ">
                </div>
                <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" class="form-control"
                           value="{{old('contact_person',$manufacturer->contact_person)}} ">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection