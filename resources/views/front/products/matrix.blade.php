@if ($product['product_type'] == "promo_product")
    @if($matrixarray)
        <div class="table_wrapper">
            <table class="table table-bordered">
                <tr>
                    <th><strong>Quantity</strong></th>
                    @foreach($product['purchase_prices'] as $purchase_price)
                        @if(isset($purchase_price['qty_id']) && ($purchase_price['price'] != "CALL" || $purchase_price['price'] != "0"))
                            <th>
                                <strong>
                                    {{ $quantity_titles[ $purchase_price['qty_id'] ]['title'] }}
                                </strong>
                            </th>
                        @endif
                    @endforeach
                </tr>
                @if (!empty($printing_agency_type))
                    @foreach ($printing_agency_type as $printing_agency_value)
                        @if (!empty($size_type))
                            @foreach ($size_type as $size_value)
                                @if (isset($matrixarray) && !empty($matrixarray))
                                    @foreach ($matrixarray as $martixrow => $matrixval)
                                        <tr>
                                            <td><strong>{{ $size_names[$size_value]['value']}} / {{$matrixval}}</strong>
                                            </td>
                                            @foreach ($product['purchase_prices'] as $purchase_price)
                                                @php
                                                    $qty_id = isset($purchase_price['qty_id']) ? $purchase_price['qty_id'] : "";

                                                    if (isset($printing_agency_value) && isset($size_value)){
                                                        $personalisation_price = isset ($personalisation_prices[$printing_agency_value][$size_value][$martixrow][$qty_id][0]) ? $personalisation_prices[$printing_agency_value][$size_value][$martixrow][$qty_id][0]['price'] : '0';
                                                    }else{
                                                        $personalisation_price = 0;
                                                    }

                                                    $purchaseprice = $purchase_price['price'] ? $purchase_price['price'] : '0';
                                                    $qty_title = isset($quantity_titles) ? $quantity_titles[$qty_id]['title'] : "";

                                                    $category_markup = (isset($category_markups) && (array_key_exists($qty_id, $category_markups))) ? $category_markups[$qty_id]['lc_price'] : '0';

                                                    $personalisationtype_markup = (isset($personalisation_type_markups) && (array_key_exists($qty_id, $personalisation_type_markups))) ? $personalisation_type_markups[$qty_id]['lc_price'] : '0'
                                                @endphp
                                                <td>
                                                    <span class="site_currency">$</span>
                                                    {{ number_format($purchaseprice +
                                                    ($purchaseprice *
                                                    ($category_markup/100)) +
                                                    $personalisation_price  +
                                                    ($personalisation_price *
                                                    $personalisationtype_markup/100), 2, '.', ',') }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            </table>
        </div>
    @endif
@else
    @foreach ($usb_type_titles as $usb_type)
        @if($matrixarray)
            <a class="usb_type_title" href="#" data-toggle="collapse" data-target="#{{seoUrl($usb_type['title'])}}" onclick="$('#{{seoUrl($usb_type['title'])}}').collapse('toggle')">{{$usb_type['title']}} Pricing With Branding Options <i class="fa fa-plus pull-right"></i></a>
            <div class="collapse" id="{{seoUrl($usb_type['title'])}}" >
                <div class="table_wrapper">
                    <table  class="table table-bordered">
                        <tr>
                            <th><strong>Printer / Size / Quantity</strong></th>
                            @if (isset($matrixarray))
                                @foreach ($matrixarray as $matrixval)
                                    <th><strong>{{$matrixval}}</strong></th>
                                @endforeach
                            @endif
                        </tr>
                        @if (!empty($printing_agency_type))
                            @foreach ($printing_agency_type as $printing_agency_row => $printing_agency_value)
                                @if (!empty($size_type))
                                    @foreach ($size_type as $size_value)
                                        <td><strong>{{ $size_names[$size_value]['value']}}</strong></td>
                                        @foreach ($product['usb_purchase_prices'] as $purchase_price)
                                            <tr>
                                                @php
                                                    $qty_id = $purchase_price['quantity_id'];
                                                    $qty_title = $quantity_titles[$qty_id]['title'];

                                                    $purchaseprice = $purchase_price['price'] ? $purchase_price['price'] : '0';

                                                    $category_markup = array_key_exists($qty_id, $category_markups) ? $category_markups[$qty_id]['lc_price'] : '0';

                                                    $personalisationtype_markup = array_key_exists($qty_id, $personalisation_type_markups) ? $personalisation_type_markups[$qty_id]['lc_price'] : '0'
                                                @endphp

                                                @if(isset($qty_title))
                                                    @if ($purchase_price['usb_type_id'] == $usb_type['id'] )
                                                        <td><strong>{{$qty_title}}</strong></td>
                                                        @if (isset($matrixarray) && !empty($matrixarray))
                                                            @foreach ($matrixarray as $martixrow => $matrixval)
                                                                @if(isset($personalisationtype_id) && !empty($purchase_price))
                                                                    @php
                                                                        $personalisation_price = isset
                                                                        ($personalisation_prices[$printing_agency_value][$size_value][$martixrow][$qty_id][0]) ?
        $personalisation_prices[$printing_agency_value][$size_value][$martixrow][$qty_id][0]['price'] : '0'
                                                                    @endphp
                                                                    <td>
                                                                        <span class="site_currency">$</span>
                                                                        {{ number_format($purchaseprice + ($purchaseprice * ($category_markup/100)) + $personalisation_price  + ($personalisation_price * $personalisationtype_markup/100), 2, '.', ',') }}
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        @endif
    @endforeach
@endif
