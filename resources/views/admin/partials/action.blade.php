<td>{{Carbon\Carbon::parse($item['created_at'])->diffForHumans()}} /
    {{Carbon\Carbon::parse($item['updated_at'])->diffForHumans()}}</td>
<td>
    @if(isset($item_edit_route))
    <a href="{{route($item_edit_route, $item['id'])}}">
        <i class="fa fa-edit"></i>
    </a>
    @endif

    <a class="text-danger" onclick="deleteData({{ $item['id'] }})">
        <i class="fa fa-remove"></i>
    </a>

</td>
