@extends('admin.layouts.app')
@section('title')Edit Product @endsection
@section('page_title')Edit Product @endsection
@section('content')
    @include('admin.partials.error_message')

    <div class="row">
        <form action="{{route('product_update',$product['slug'])}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" placeholder="Name" name="name"
                           value="{{old('name', $product['name'])}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Product Type</label>
                    <input type="text"
                           value="{{old('product_type', ucwords(str_replace('_',' ',$product['product_type'])))}}"
                           disabled="disabled" class="form-control" name="product_type">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Product Code</label>
                    <input type="text" class="form-control" placeholder="Product Code" name="product_code"
                           value="{{old('product_code', $product['product_code'])}}">
                </div>
            </div>

            <div class="col-md-6 ">
                <div class="row">
                    <div class="col-md-8  d-flex">
                        <div class="form-group width-100">
                            <label>Product Main Image</label>
                            <input type="file" name="main_image" class="form-control" value="{{old('main_image')}}">
                        </div>
                    </div>
                    <div class="col-md-4  d-flex">
                        <div class="form-group my-auto">
                            @if (isset($product['main_image']))
                                <img src="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$product['main_image']}}" alt="{{$product['name']}}" width="50px">
                            @else
                                <img src="{{asset('files/23/Photos/Products/no_image.png')}}"
                                     alt="{{$product['name']}}" width="50px">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3 mb-3 card">
                <!-- Nav pills -->
                <ul class="nav nav-pills  mb-2" id="product_tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#general">General</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#description">Description</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#categories">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#price">Price</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#personalize">Personalise</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#attributes">Attributes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#other">Other</a>
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
                                    <label>Product Alternative Image</label>
                                    <input type="file" name="alternative_image" class="form-control"
                                           value="{{old('alternative_image')}}">
                                    @if (isset($product['alternative_image']))
                                        <img src="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$product['alternative_image']}}" alt="{{$product['name']}}" width="50px">
                                    @else
                                        <img src="{{asset('files/23/Photos/Products/no_image.png')}}"
                                             alt="{{$product['name']}}" width="50px">
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>Dimensions</label>
                                    <input type="text" class="form-control" name="dimensions"
                                           value="{{old('dimensions', $product['dimensions'])}}">
                                </div>
                                <div class="form-group">
                                    <label>Print Area</label>
                                    <input type="text" class="form-control" name="print_area"
                                           value="{{old('print_area', $product['print_area'])}}">
                                </div>
                                <div class="form-group">
                                    <label>Decoration Areas</label>
                                    <input type="text" class="form-control" name="decoration_areas"
                                           value="{{old('decoration_areas', $product['decoration_areas'])}}">
                                </div>

                                <div class="form-group">
                                    <label>Video Link</label>
                                    <input type="text" class="form-control" name="video_link"
                                           value="{{old('video_link', $product['print_area'])}}">
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value="1" @if(old('status') == "1") selected
                                                @elseif($product['status'] == '1') selected @endif>Active
                                        </option>
                                        <option value="0" @if(old('status') == "0") selected
                                                @elseif($product['status'] == '0') selected @endif>In Active
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="description">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Item Size</label>
                                    <textarea name="item_size" class="form-control" cols="30" rows="10"
                                              id="item_size">{{old('item_size', $product['product_item_size'])}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Short Description</label>
                                    <textarea name="short_desc" class="form-control" cols="30" rows="10"
                                              id="short_desc">{{old('short_desc', $product['short_desc'])}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Long Description</label>
                                    <textarea name="long_desc" class="form-control" cols="30" rows="10"
                                              id="long_desc">{{old('long_desc', $product['long_desc'])}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Product Features</label>
                                    <textarea name="product_features" class="form-control" cols="30" rows="10"
                                              class="product_features">{{old('product_features', $product['product_features'])}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="categories">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="main_category">Main category</label>
                                    <select name="main_category" class="get_sub_category form-control" role="listbox" id="main_category">
                                        @foreach($product['categories'] as $category)
                                            @if($category['pivot']['level'] == 1)
                                                <option value="{{$category['id']}}" selected disabled>
                                                    {{$category['name']}}
                                                </option>
                                            @endif
                                        @endforeach
                                        <option value="0"> ---------Select Main Category---------</option>
                                        @foreach($parent_categories as $category)
                                            <option value="{{$category['id']}}" {{old('main_category') == $category['id'] ? "selected" : ""}}>{{$category['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sub_category">Sub Category</label>
                                    <select name="sub_category" class="form-control get_sub_sub_category"
                                            id="sub_category" role="listbox">
                                        @foreach($parent_categories as $category)
                                            @foreach($category['sub_category'] as $sub_category)
                                                @if((old('sub_category') == $sub_category['id']))
                                                    <option value="{{$sub_category['id']}}"
                                                            selected>{{$sub_category['name']}}</option>
                                                @endif
                                            @endforeach
                                        @endforeach
                                        @foreach($product['categories'] as $category)
                                            @if($category['pivot']['level'] == 2)
                                                <option value="{{$category['id']}}" selected>
                                                    {{$category['name']}}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sub_sub_category">Sub Sub-Category</label>
                                    <select name="sub_sub_category" class="form-control get_category"
                                            id="sub_sub_category">
                                        @foreach($parent_categories as $category)
                                            @foreach($category['sub_category'] as $sub_category)
                                                @foreach($sub_category['sub_category'] as $sub_sub_category)
                                                    @if((old('sub_sub_category') == $sub_sub_category['id']))
                                                        <option value="{{$sub_sub_category['id']}}"
                                                                selected>{{$sub_sub_category['name']}}</option>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                        @foreach($product['categories'] as $category)
                                            @if($category['pivot']['level'] == 3)
                                                <option value="{{$category['id']}}" selected="selected">
                                                    {{$category['name']}}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="price">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <h4>Select Manufacturer</h4>
                                    @foreach($manufacturers as $manufacturer)
                                        <div class="custom-control custom-switch">
                                            <input type="radio" value="{{$manufacturer['id']}}" name="manufacturer"
                                                   class="custom-control-input" id="manufacturer{{$manufacturer['id']}}"
                                                   @if(old('manufacturer') == $manufacturer['id']) checked
                                                   @elseif($product['manufacturer_id'] && ($manufacturer['id'] == $product['manufacturer_id'])) checked @endif>
                                            <label class="custom-control-label"
                                                   for="manufacturer{{$manufacturer['id']}}">
                                                {{$manufacturer['name']}}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="card-title mt-5">
                                    <h4>Purchase Price</h4>
                                </div>

                                <div class="form-group">
                                    @if($product['product_type'] == "promo_product")
                                        @foreach($quantities as $quantity)
                                            <input type="hidden" value="{{$quantity['id']}}"
                                                   name="price[{{$quantity['id']}}][qty]">
                                            <label>
                                                {{$quantity['title']}}
                                                <input type="number" name="price[{{$quantity['id']}}][amount]"
                                                       class="form-control"
                                                       @if(isset(old('price')[$quantity['id']]['amount'])) value="{{ old('price')[$quantity['id']]['amount'] }}"
                                                       @elseif(isset($purchase_price_list[$quantity['id']]))  value="{{ $purchase_price_list[$quantity['id']] }}"
                                                       @endif placeholder="Ex: 10.02" step="0.01"/>
                                            </label>
                                        @endforeach
                                    @else
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th scope="col">USB Types</th>
                                                @foreach($quantities as $quantity)
                                                    <th>{{$quantity['title']}}</th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($usb_types as $usb_type)
                                                <tr>
                                                    <td> {{$usb_type['title']}}</td>
                                                    @foreach($quantities as $quantity)
                                                        <td>
                                                            <input type="number"
                                                                   name="usb_price[{{$usb_type['id']}}][{{$quantity['id']}}]"
                                                                   class="form-control" step=".01"
                                                                   value="{{isset($purchase_price_list[$usb_type['id']][$quantity['id']]) ? $purchase_price_list[$usb_type['id']][$quantity['id']] : 0}}"/>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="personalize">
                        <div class="card">
                            <div class="card-body">
                                @foreach($personalisationtypes as $personalisationtype)
                                    <br>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" value="{{$personalisationtype['id']}}"
                                               name="type[{{$personalisationtype['id']}}][id]"
                                               class="custom-control-input" id="type{{$personalisationtype['id']}}"
                                               @if(isset(old('type')[$personalisationtype['id']]['id']) && old('type')[$personalisationtype['id']]['id'] == $personalisationtype['id']) checked
                                               @elseif((in_array($personalisationtype['id'], $product_personalisationtype_id_list))) checked @endif >
                                        <label class="custom-control-label" for="type{{$personalisationtype['id']}}">
                                            {{$personalisationtype['name']}}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="attributes">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered" id="dynamicTable">
                                    <tr>
                                        <th>Attribute Color</th>
                                        <th>Attribute Name</th>
                                        <th>Upload an image</th>
                                        <th>Description</th>
                                        <th>Primary Color</th>
                                        <th>Action</th>
                                    </tr>

                                    <input type="hidden" name="attr_token" id="attr_token" value="{{ csrf_token() }}">
                                    <tr>
                                        <td>
                                            <input type="color" name="attr_color" id="attr_color">
                                        </td>
                                        <td>
                                            <input type="text" name="attr_name" placeholder="Enter Attribute Name"
                                                   class="form-control" id="attr_name"/>
                                        </td>
                                        <td>
                                            <input type="file" name="attr_image" id="attr_image">
                                        </td>
                                        <td>
                                        <textarea name="attr_description" cols="10" rows="3" class="form-control"
                                                  id="attr_description"> </textarea>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select name="primarycolor_id" class="form-control"
                                                        id="primarycolor_id">
                                                    @foreach($primarycolors as $primarycolor)
                                                        <option value="{{$primarycolor['id']}}">
                                                            {{$primarycolor['name']}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            @if(isset($product['manufacturer_key']))
                                                <button type="button" id="add" class="btn btn-success">Add</button>
                                            @else
                                                <div class="badge badge-danger">No Manufacturer Selected!</div>
                                            @endif
                                        </td>
                                    </tr>
                                    @if(count($product['attributes']))
                                        @foreach($product['attributes'] as $attribute)
                                            <tr id="inserted_attributes">
                                                <td>
                                                    <div class="color"
                                                         style="background-color:{{$attribute['color']}};width: 50px;height: 50px">
                                                    </div>
                                                </td>
                                                <td><span>{{$attribute['name']}}</span></td>
                                                <td>
                                                    <img src="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$attribute['image']}}" width="80px">
                                                </td>
                                                <td><span>{{$attribute['description']}}</span></td>
                                                <td>
                                                    <div class="form-group">
                                                        <select name="selected_primarycolor_id"
                                                                class="form-control selected_primarycolor_id">
                                                            <option value="0">Select Primary Color</option>
                                                            @foreach($primarycolors as $primarycolor)
                                                                @if(isset($attribute['primarycolor_id']))
                                                                    <option value="{{$primarycolor['id']}}"
                                                                            {{$attribute['primarycolor_id'] == $primarycolor['id'] ? "selected" : ""}} data-attr_id="{{$attribute['id']}}">
                                                                @else
                                                                    <option value="{{$primarycolor['id']}}"
                                                                            data-attr_id="{{$attribute['id']}}">
                                                                        @endif
                                                                        {{$primarycolor['name']}}
                                                                    </option>
                                                                    @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if(isset($product['manufacturer_id']))
                                                        <button type="button" class="remove btn btn-danger"
                                                                data-aid="{{$attribute['id']}}"
                                                                data-pid="{{$product['id']}}"
                                                                data-pcid="{{$attribute['primarycolor_id']}}"
                                                                data-mfkey="{{$product['manufacturer_key']}}">Remove
                                                        </button>
                                                    @else
                                                        <div class="badge badge-danger">No Manufacturer Id Set!</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="other">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Delivery Charges</label>
                                    <textarea name="delivery_charges" class="form-control" cols="30" rows="10"
                                              id="delivery_charges">{{old('delivery_charges', $product['delivery_charges'])}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Payment Terms</label>
                                    <textarea name="payment_terms" class="form-control" cols="30" rows="10"
                                              id="payment_terms">{{old('payment_terms', $product['payment_terms'])}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Returns Policy</label>
                                    <textarea name="return_policy" class="form-control" cols="30" rows="10"
                                              class="return_policy">{{old('return_policy', $product['return_policy'])}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Disclaimer</label>
                                    <textarea name="disclaimer" class="form-control" cols="30" rows="10"
                                              class="disclaimer">{{old('disclaimer' ,$product['disclaimer'])}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="meta">
                        @include('admin.includes.meta.edit', ['item' => $product])
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>

        </form>

    </div>

@endsection
@section('scripts')
    @include('admin.includes.scripts.products.edit')
@endsection
