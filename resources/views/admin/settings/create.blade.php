@extends('admin.layouts.app')
@section('title')Site Settings @endsection
@section('page_title')Site Settings @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('settings_store')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('POST')}}
        <div class="form-group">
            <label>Site Name</label>
            <input type="text" class="form-control" placeholder="Site Name" name="site_name" value="{{ old('site_name') }}">
        </div>
        <div class="form-group">
            <label>Site Tagline</label>
            <input type="text" class="form-control" placeholder="Site Tagline" name="site_tagline" value="{{ old('site_tagline') }}">
        </div>
        <div class="form-group">
            <label>Site Email</label>
            <input type="email" class="form-control" placeholder="Email" name="site_email" value="{{ old('site_email') }}">
        </div>
        <div class="form-group">
            <label>Site Phone</label>
            <input type="text" class="form-control" placeholder="Phone No" name="site_phone"
                   value="{{ old('site_phone') }}">
        </div>
        <div class="form-group">
            <label>Site Address</label>
            <input type="text" class="form-control" placeholder="Site Address" name="site_address"
                   value="{{ old('site_address') }}">
        </div>
        <div class="form-group">
            <label>Site Description</label>
            <textarea name="site_description" class="form-control" cols="30"
                      rows="10">{{old('site_description')}}</textarea>
        </div>
        <div class="form-group">
            <label>Site Logo</label>
            <input type="file" name="site_logo" class="form-control" value="{{ old('site_logo') }}">
        </div>
        <div class="form-group">
            <label>Site Favicon</label>
            <input type="file" name="site_favicon" class="form-control" value="{{ old('site_favicon') }}">
        </div>
        <div class="form-group">
            <label>Facebook</label>
            <input type="text" name="site_facebook" class="form-control" value="{{ old('site_facebook') }}">
        </div>
        <div class="form-group">
            <label>Twitter</label>
            <input type="text" name="site_twitter" class="form-control" value="{{ old('site_twitter') }}">
        </div>
        <div class="form-group">
            <label>Instagram</label>
            <input type="text" name="site_instagram" class="form-control"
                   value="{{ old('site_instagram') }}">
        </div>
        <div class="form-group">
            <label>Linkedin</label>
            <input type="text" name="site_linkedin" class="form-control" value="{{ old('site_linkedin') }}">
        </div>
        <div class="form-group">
            <label>GitHub</label>
            <input type="text" name="site_github" class="form-control" value="{{ old('site_github') }}">
        </div>
        <div class="form-group">
            <label>Meta Title</label>
            <input type="text" name="site_meta_title" class="form-control"
                   value="{{ old('site_meta_title') }}">
        </div>
        <div class="form-group">
            <label>Meta Keywords</label>
            <input type="text" name="site_meta_keywords" class="form-control"
                   value="{{ old('site_meta_keywords') }}">
        </div>
        <div class="form-group">
            <label>Meta Description</label>
            <textarea name="site_meta_description" class="form-control" cols="30"
                      rows="10">{{old('site_meta_description')}}</textarea>
        </div>

        <h4 class="text-dark mt-5 mb-3">Product's Common Information</h4>
        <div class="form-group">
            <label>Delivery Charges</label>
            <textarea name="delivery_charges" class="form-control" cols="30" rows="10" id="delivery_charges">{{old('delivery_charges')}}</textarea>
        </div>
        <div class="form-group">
            <label>Payment Terms</label>
            <textarea name="payment_terms" class="form-control" cols="30" rows="10" id="payment_terms">{{old('site_meta_description')}}</textarea>
        </div>
        <div class="form-group">
            <label>Return Policy</label>
            <textarea name="return_policy" class="form-control" cols="30"
                      rows="10" id="return_policy">{{old('return_policy')}}</textarea>
        </div>
        <div class="form-group">
            <label>Disclaimer</label>
            <textarea name="disclaimer" class="form-control" cols="30"
                      rows="10" id="disclaimer">{{old('disclaimer')}}</textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection
@section('scripts')
    @include('admin.includes.scripts.settings.create')
@endsection