<table class="table table-bordered">
    <tbody>
    <tr>
        <th>LA</th>
        <th>LB</th>
        <th>LC</th>
    </tr>
    @if($quantities)
        @foreach($quantities as $quantity)
            <tr>
                <input type="hidden" value="{{$quantity['id']}}" name="price[{{$quantity['id']}}][qty]">
                <td>
                    <label>{{$quantity['title']}}
                        <input type="number" name="price[{{$quantity['id']}}][laamount]"
                               class="form-control"
                               @if(isset(old('price')[$quantity['id']]['laamount'])) value="{{old('price')[$quantity['id']]['laamount']}}"
                               @else value="{{ $item_la_markup_list [$quantity['id']] ?? "0" }}" @endif/>
                    </label>
                </td>
                <td>
                    <label>{{$quantity['title']}}
                        <input type="number" name="price[{{$quantity['id']}}][lbamount]"
                               class="form-control"
                               @if(isset(old('price')[$quantity['id']]['lbamount'])) value="{{old('price')[$quantity['id']]['lbamount']}}"
                               @else value="{{ $item_lb_markup_list [$quantity['id']] ?? "0" }}" @endif/>
                    </label>
                </td>
                <td>
                    <label>{{$quantity['title']}}
                        <input type="number" name="price[{{$quantity['id']}}][lcamount]"
                               class="form-control"
                               @if(isset(old('price')[$quantity['id']]['lcamount'])) value="{{old('price')[$quantity['id']]['lcamount']}}"
                               @else value="{{ $item_lc_markup_list [$quantity['id']] ?? "0" }}" @endif/>
                    </label>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
