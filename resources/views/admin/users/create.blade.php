@extends('admin.layouts.app')
@section('title')Add New User @endsection
@section('page_title')Add New User @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('user_store')}}" method="post">
        {{csrf_field()}}
        {{method_field('POST')}}
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" placeholder="Name" name="name" value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password" value="{{ old('password') }}">
        </div>
        <div class="form-group">
            <label>Role</label>
            <select class="form-control" name="role">
                <option value="none">None</option>
                @foreach($roles as $role)
                    <option
                        value="{{$role['id']}}" {{ old('role') == $role['id'] ? 'selected' : '' }}>{{$role['name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="tel" class="form-control" placeholder="Phone no" name="phone_no" value="{{ old('phone_no') }}">
        </div>
        <div class="form-group">
            <label>Company</label>
            <input type="text" class="form-control" placeholder="Company" name="company" value="{{ old('phone_no') }}">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select class="form-control" name="status">
                <option value="1" @if(old('status') == "1") selected @endif>Active</option>
                <option value="0" @if(old('status') == "0") selected @endif>In Active</option>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection
