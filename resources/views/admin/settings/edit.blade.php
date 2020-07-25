@extends('admin.layouts.app')
@section('title')Update Site Settings @endsection
@section('page_title')Update Site Settings @endsection
@section('content')
@include('admin.partials.error_message')

<form action="{{route('settings_update',$settings[0]['id'])}}" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    {{method_field('POST')}}
    <div class="form-group">
        <label>Site Name</label>
        <input type="text" class="form-control" placeholder="Site Name" name="site_name"
            value="{{$settings[0]['data']['site_name']}}">
    </div>

    <div class="form-group">
        <label>Site Tagline</label>
        <input type="text" class="form-control" placeholder="Site Tagline" name="site_tagline"
            value="{{$settings[0]['data']['site_tagline']}}">
    </div>
    <div class="form-group">
        <label>Site Email</label>
        <input type="email" class="form-control" placeholder="Email" name="site_email"
            value="{{$settings[0]['data']['site_email']}}">
    </div>
    <div class="form-group">
        <label>Site Phone</label>
        <input type="text" class="form-control" placeholder="Phone No" name="site_phone"
            value="{{$settings[0]['data']['site_phone']}}">
    </div>
    <div class="form-group">
        <label>Site Address</label>
        <input type="text" class="form-control" placeholder="Site Address" name="site_address"
            value="{{$settings[0]['data']['site_address']}}">
    </div>
    <div class="form-group">
        <label>Site Description</label>
        <textarea name="site_description" class="form-control" cols="30"
            rows="10">{{$settings[0]['data']['site_description']}}</textarea>
    </div>
    <div class="form-group">
        <label>Site Logo</label>
        <input type="file" name="site_logo" class="form-control">
        <img src="{{asset('files/23/Photos/Settings/'.$settings[0]['data']['site_logo'])}}" width="100">
        <label>{{$settings[0]['data']['site_logo']}}</label>
    </div>
    <div class="form-group">
        <label>Site Favicon</label>
        <input type="file" name="site_favicon" class="form-control">
        <img src="{{asset('files/23/Photos/Settings/'.$settings[0]['data']['site_favicon'])}}" width="50">
        <label>{{$settings[0]['data']['site_favicon']}}</label>
    </div>
    <div class="form-group">
        <label>Facebook</label>
        <input type="text" name="site_facebook" class="form-control" value="{{$settings[0]['data']['site_facebook']}}">
    </div>
    <div class="form-group">
        <label>Twitter</label>
        <input type="text" name="site_twitter" class="form-control" value="{{$settings[0]['data']['site_twitter']}}">
    </div>
    <div class="form-group">
        <label>Instagram</label>
        <input type="text" name="site_instagram" class="form-control"
            value="{{$settings[0]['data']['site_instagram']}}">
    </div>
    <div class="form-group">
        <label>Linkedin</label>
        <input type="text" name="site_linkedin" class="form-control" value="{{$settings[0]['data']['site_linkedin']}}">
    </div>
    <div class="form-group">
        <label>GitHub</label>
        <input type="text" name="site_github" class="form-control" value="{{$settings[0]['data']['site_github']}}">
    </div>
    <div class="form-group">
        <label>Meta Title</label>
        <input type="text" name="site_meta_title" class="form-control"
            value="{{$settings[0]['data']['site_meta_title']}}">
    </div>
    <div class="form-group">
        <label>Meta Keywords</label>
        <input type="text" name="site_meta_keywords" class="form-control"
            value="{{$settings[0]['data']['site_meta_keywords']}}">
    </div>
    <div class="form-group">
        <label>Meta Description</label>
        <textarea name="site_meta_description" class="form-control" cols="30"
            rows="10">{{$settings[0]['data']['site_meta_description']}}</textarea>
    </div>

    <h4 class="text-dark mt-5 mb-3">Product's Common Information</h4>
    <div class="form-group">
        <label>Delivery Charges</label>
        <textarea name="delivery_charges" class="form-control" cols="30" rows="10"
            id="delivery_charges">{!! $settings[0]['delivery_charges'] !!}</textarea>
    </div>
    <div class="form-group">
        <label>Payment Terms</label>
        <textarea name="payment_terms" class="form-control" cols="30" rows="10"
            id="payment_terms">{!! $settings[0]['payment_terms'] !!}</textarea>
    </div>
    <div class="form-group">
        <label>Return Policy</label>
        <textarea name="return_policy" class="form-control" cols="30" rows="10"
            id="return_policy">{!! $settings[0]['return_policy'] !!}</textarea>
    </div>
    <div class="form-group">
        <label>Disclaimer</label>
        <textarea name="disclaimer" class="form-control" cols="30" rows="10"
            id="disclaimer">{!! $settings[0]['disclaimer'] !!}</textarea>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
@endsection
@section('scripts')
@include('admin.includes.scripts.settings.create')
@endsection
