<table class="table table-bordered">
    <tbody>
    <tr>
        <th>LA</th>
        <th>LB</th>
        <th>LC</th>
    </tr>
    @foreach($quantities as $quantity)
        <tr>
            <input type="hidden" value="{{$quantity['id']}}" name="price[{{$quantity['id']}}][qty]">
            <td>
                <label>{{$quantity['title']}}
                    <input type="number" name="price[{{$quantity['id']}}][laamount]" class="form-control" value="{{old('price')[$quantity['id']]['laamount']}}"/>
                </label>
            </td>
            <td>
                <label>{{$quantity['title']}}
                    <input type="number" name="price[{{$quantity['id']}}][lbamount]" class="form-control" value="{{old('price')[$quantity['id']]['lbamount']}}"/>
                </label>
            </td>
            <td>
                <label>{{$quantity['title']}}
                    <input type="number" name="price[{{$quantity['id']}}][lcamount]" class="form-control" value="{{old('price')[$quantity['id']]['lcamount']}}"/>
                </label>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
