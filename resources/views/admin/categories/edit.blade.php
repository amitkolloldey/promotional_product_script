@extends('admin.layouts.app')
@section('title')Update Category @endsection
@section('page_title')Update Category @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('category_update',$category['id'])}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <!-- Nav pills -->
                <ul class="nav nav-pills  mb-2" id="category_tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#general">General</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#category_markup">Markup(%)</a>
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
                                    <label>Name</label>
                                    <input type="text" class="form-control" placeholder="Name" name="name"
                                           value="{{old('name',$category['name'])}}">
                                </div>
                                <div class="form-group">
                                    <label>Category Description</label>
                                    <textarea name="description" cols="10" rows="5"
                                              class="form-control description" id="">{{old('description',$category['description'])}}</textarea>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8 d-flex">
                                            <div class="form-group width-100">
                                                <label>Category Main Image</label>
                                                <input type="file" name="main_image" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-4 d-flex">
                                            <div class="form-group width-100 my-auto">
                                                <img src="{{asset('files/23/Photos/Categories/').'/'.$category['main_image']}}"
                                                     width="100px">
                                                <label>{{$category['main_image']}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8 d-flex">
                                            <div class="form-group width-100">
                                                <label>Category Thumbnail Image</label>
                                                <input type="file" name="thumbnail_image" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-4 d-flex">
                                            <div class="form-group width-100 my-auto">
                                                <img
                                                        src="{{asset('files/23/Photos/Categories/').'/'.$category['thumbnail_image']}}"
                                                        width="100px">
                                                <label>{{$category['thumbnail_image']}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Parent Category</label>
                                    <br>
                                    <select name="parent_id" class="form-control">
                                        <option value="">None</option>
                                        @foreach($parent_categories as $parent_category)
                                            @if(old('parent_id') == $parent_category['id'])
                                                <optgroup>
                                                    <option value="{{$parent_category['id']}}" style="font-weight:bold"
                                                            selected>
                                                        - {{$parent_category['name']}}</option>
                                                </optgroup>
                                            @else
                                                <optgroup>
                                                    <option value="{{$parent_category['id']}}"
                                                            {{$parent_category['id'] == $category['parent_id'] ? "selected" : ""}} style="font-weight:bold">
                                                        - {{$parent_category['name']}}</option>
                                                </optgroup>
                                            @endif
                                            @if(count($parent_category['sub_category']))
                                                @include('admin.includes.subcategory.all',['subcategories' => $parent_category['sub_category'],'level' => " -- ",'category_parent' => $category['parent_id']])
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value="1" @if(old('status') == "1") selected
                                                @elseif($category['status'] == '1') selected @endif>Active
                                        </option>
                                        <option value="0" @if(old('status') == "0") selected
                                                @elseif($category['status'] == '0') selected @endif>In Active
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="category_markup">
                        <div class="card">
                            <div class="card-body">
                                @include('admin.includes.markups.edit',['item_la_markup_list' => isset($category_la_markup_list) ? $category_la_markup_list : null, 'item_lb_markup_list' => isset($category_lb_markup_list) ? $category_lb_markup_list : null, 'item_lc_markup_list' => isset($category_lc_markup_list) ? $category_lc_markup_list : null])
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="meta">
                        @include('admin.includes.meta.edit', ['item' => $category])
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
    @include('admin.includes.scripts.categories.create')
@endsection
