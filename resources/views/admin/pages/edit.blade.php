@extends('admin.layouts.app')
@section('title')Edit Page @endsection
@section('page_title')Edit Page @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('page_update', $page['id'])}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('POST')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <!-- Nav pills -->
                <ul class="nav nav-pills mb-2" id="category_tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#general">General</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#meta">Meta</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="general">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" placeholder="Title" name="title" value="{{old('title',$page['title'])}}">
                                </div>
                                <div class="form-group">
                                    <label>Content</label>
                                    <textarea name="page_content" id="page_content" cols="30" rows="10">{{old('page_content',$page['content'])}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Featured Image</label>
                                    <input type="file" name="image">
                                    @if (isset($page['image']))
                                        <img src="{{asset('files/23/Photos/Pages/').'/'.$page['image']}}" alt="{{$page['title']}}" width="50px">
                                    @else
                                        <img src="{{asset('files/23/Photos/Pages/no_image.png')}}" alt="{{$page['title']}}" width="50px">
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value="1" @if(old('status') == "1") selected
                                                @elseif($page['status'] == '1') selected @endif>Active
                                        </option>
                                        <option value="0" @if(old('status') == "0") selected
                                                @elseif($page['status'] == '0') selected @endif>In Active
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="meta">
                        @include('admin.includes.meta.edit', ['item' => $page])
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    @include('admin.includes.scripts.pages.create')
@endsection
