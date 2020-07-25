@extends('admin.layouts.app')
@section('title')Add New Role @endsection
@section('page_title')Add New Role @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('role_store')}}" method="post">
        {{csrf_field()}}
        {{method_field('POST')}}
        <div class="form-group">
            <label>Role Name</label>
            <input type="text" class="form-control" placeholder="Role Name" name="name" value="{{ old('name') }}">
            <small class="text-muted">Role Name Must Be Lowercase and Without Spaces Ex: super-admin</small>
        </div>
        <div class="form-group">
            <label>Permissions</label>
            <br>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="selectall" class="custom-control-input" id="selectall">
                <label class="custom-control-label" for="selectall">Select All / Unselect All</label>
            </div>
            <div class="all_permissions">
                @foreach($permissions as $permission)
                    <div class="custom-control custom-switch" style="display: inline-block; margin-left: 10px">
                        <input type="checkbox" value="{{$permission['id']}}" name="permissions[]"
                               class="custom-control-input" id="permissions{{$permission['id']}}"
                               @if(!empty(old('permissions')) && in_array($permission['id'], old('permissions'))) checked @endif>
                        <label class="custom-control-label"
                               for="permissions{{$permission['id']}}"> {{$permission['name']}}</label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection
