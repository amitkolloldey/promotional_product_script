<div class="col-md-12">
    <hr>
    <div class="row order_info">
        <div class="col-xs-12 col-sm-3 col-md-3">
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="unicase-checkout-title">Product Details</h4>
                    </div>
                    <div class="order_product_info">
                        <div class="row product_order_card  text-center">
                            <a href="{{route('product_show',$product->associatedModel->slug)}}" target="_blank">
                                <div class="col-md-12">
                                    <div class="order_product_image">
                                        <img src="{{asset('files/23/Photos/Products/').'/'.$product->associatedModel->manufacturer_key.'/'.$product->associatedModel->main_image}}"
                                             height="200px">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="order_product_meta">
                                        <h4>
                                            {{$product->name}}
                                        </h4>
                                        <span>
                                    {{$product->associatedModel->product_code}}
                                </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- checkout-progress-sidebar -->
        </div><!-- /.row -->
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="panel-group " id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="unicase-checkout-title">Personalisation Options</h4>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-group checkout-steps">
                        <h2 class="checkout_heading">Requirements</h2>
                        <div class="form-group">
                            <label for="quantity">How many do you need?</label>
                            <input id="quantity" class="form-control" type="number" name="quantity"
                                   min="{{$product->associatedModel->min_quantity}}"
                                   value="{{$product->quantity > 1 ? $product->quantity : $product->associatedModel->min_quantity}}"/>
                            <input type="hidden" id="min_quantity" value="{{$product->associatedModel->min_quantity}}"/>
                            <input type="hidden" id="product_id" value="{{$product->id}}" name="product_id"/>
                            <input type="hidden" id="product_name" value="{{$product->name}}" name="product_name"/>
                        </div>
                        <div class="form-group">
                            <label for="color">Which Color Do You Want?</label>
                            <select name="color" class="form-control">
                                <option value="none">None</option>
                                @foreach($product->associatedModel->attributes as $attribute)
                                    <option value="{{$attribute->id}}"
                                            {{$product->attributes->color == $attribute->id ? 'selected' : ''}}>{{$attribute->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="storage">Storage</label>
                            <select name="storage" class="form-control" id="storage">
                                @foreach($usb_type_titles as $key => $usb_title)
                                    @if(old('storage'))
                                        <option value="{{$key}}" {{(old('storage') == $key) ? "selected" : ""}}>{{$usb_title['title']}}
                                        </option>
                                    @else
                                        <option value="{{$key}}" {{($key == $product->attributes->storage) ? "selected" : ""}}>{{$usb_title['title']}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <h2 class="checkout_heading">Personalisation Options</h2>
                        <div class="form-group">
                            <div id="loading_image">
                                <img src="{{asset('front/assets/images/ajax.gif')}}" width="50px">
                            </div>
                            <label for="personalisation_options">Select a personalisation Option</label>
                            <select name="personalisation_options" class="form-control" id="personalisation_option">
                                <option value="">Available Option</option>
                                @foreach($product->associatedModel->personalisationtypes as $personalisationtype)
                                    <option value="{{$personalisationtype->id}}"
                                            data-pro_id="{{$product->associatedModel->id}}"
                                            {{$product->attributes->personalisation_options == $personalisationtype->id ? 'selected' : ''}}>
                                        {{$personalisationtype->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="personalisation_color">Select a personalisation
                                Color</label>
                            <select name="personalisation_color" class="form-control" id="personalisation_color">
                                @if($personalisation_options == "contact")
                                    <option value="contact" selected>Contact Us</option>
                                @else
                                    @foreach ($personalisation_options as $personalisation_price)
                                        @php
                                            $color_and_position_id = explode(',', $personalisation_price->color_position_id);

                                            $color_and_position_name = (count($color_and_position_id) == 2) ? $attribute_names[intval($color_and_position_id[0])]['value'] . ' & ' .
                                            $attribute_names[intval($color_and_position_id[1])]['value'] : $attribute_names[intval($color_and_position_id[0])]['value'];

                                            $size_name = $attribute_names[$personalisation_price->size_id]['value'];

                                            $personalisation_color = $personalisation_price->personalisationtype_id . '_' . $personalisation_price->printingagency_id . '_' . $personalisation_price->size_id . '_' . $personalisation_price->color_position_id
                                        @endphp
                                        <option value="{{$personalisation_color}}"
                                                {{$personalisation_color == $product->attributes->personalisation_options ? "selected" : ""}}>
                                            {{ $size_name }} & {{ $color_and_position_name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

            </div><!-- /.checkout-steps -->
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3">
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="unicase-checkout-title">Pricing</h4>
                    </div>
                    <div class="order_product_pricing">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="show_pricing">
                                    {!! $final_pricing !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
