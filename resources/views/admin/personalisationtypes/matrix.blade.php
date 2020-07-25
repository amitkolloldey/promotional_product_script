@if($matrixarray)
    <table class="table table-bordered">
        <tr>
            <td>
                <table>
                    <tr>
                        <th><strong>Printer / Size / Quantity</strong></th>
                        @if (isset($matrixarray))
                            @foreach ($matrixarray as $matrixval)
                                <th><strong>{{$matrixval}}</strong></th>
                            @endforeach
                        @endif
                    </tr>
                  
                    @if (!empty($printing_agency_type))
                        @foreach ($printing_agency_type as $printing_agency_value) 
                            @if (!empty($size_type))
                                @foreach ($size_type as $size_value)
                                    <td><strong>{{$printing_agency_names[$printing_agency_value]}} & {{$size_names[$size_value]}}</strong></td>
                                    @if($quantities)
                                        @foreach ($quantities as $quantity)
                                            <tr>
                                                <td><strong>{{$quantity['title']}}</strong></td>
                                                @if (isset($matrixarray) && !empty($matrixarray))
                                                    @foreach ($matrixarray as $martixrow => $matrixval)
                                                        <td>
                                                            <input type="number" step="0.01" class="form-control" value="{{isset($personalisationtype_id) ? $personalisation_prices[$printing_agency_value][$size_value][$martixrow][$quantity['id']][0]['price'] : ""}}" name="matrix[{{$printing_agency_value}}][{{$size_value}}][{{$quantity['id']}}][{{$martixrow}}]"/>
                                                        </td>
                                                    @endforeach
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </table>
            </td>
        </tr>
    </table>
@endif