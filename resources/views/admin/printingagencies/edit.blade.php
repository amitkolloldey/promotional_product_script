@extends('admin.layouts.app')
@section('title')Edit Printing Agency @endsection
@section('page_title')Edit Printing Agency @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('printing_agency_update',$printingagency->id)}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{old('name',$printingagency->name)}}">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" cols="30" rows="10"
                              class="form-control">{{old('address',$printingagency->address)}}</textarea>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{old('email',$printingagency->email)}}">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" class="form-control" value="{{old('phone',$printingagency->phone)}}">
                </div>
                <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" class="form-control"
                           value="{{old('contact_person',$printingagency->contact_person)}}">
                </div>
                <div class="form-group form-check-inline">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="status" value="1" class="custom-control-input"
                               id="status" {{$printingagency->status == '1' ? "checked" : ""}}>
                        <label class="custom-control-label" for="status"> Active</label>&nbsp; &nbsp;&nbsp;
                    </div>
                </div>

            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection