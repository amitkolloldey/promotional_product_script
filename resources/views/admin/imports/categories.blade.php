@extends('admin.layouts.app')
@section('title')Import Categories @endsection
@section('page_title')Import Categories @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('categories_upload')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('POST')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <div class="form-group">
                    <p class="alert alert-info">**Find the upload format in the <strong>"Downloads"</strong> section!</p>
                    <label>Upload</label>
                    <input type="file" name="upload_category" class="form-control" accept=".xlsx">

                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </div>
    </form>
@endsection
