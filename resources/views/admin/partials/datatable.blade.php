<input type="hidden" name="model_name" value={{$model_name}}>

@if(isset($delete_button) && $delete_button == 1)
    <button class="btn btn-danger delete_selected" id="delete_id"> Delete
    </button>
@endif

@if(isset($make_new_button) && $make_new_button == 1)
    <button class="btn btn-info delete_selected" id="make_new"> Make New Product
    </button>
@endif

@if(isset($make_popular_button) && $make_popular_button == 1)
    <button class="btn btn-info delete_selected" id="make_popular"> Make Popular Product
    </button>
@endif

@if(isset($make_discontinued_stock_button) && $make_discontinued_stock_button == 1)
    <button class="btn btn-info delete_selected" id="make_discontinued_stock"> Make Discontinued Stock
    </button>
@endif

@if(isset($undo_new_button) && $undo_new_button == 1)
    <button class="btn btn-info delete_selected" id="undo_new"> Undo New Product
    </button>
@endif

@if(isset($undo_popular_button) && $undo_popular_button == 1)
    <button class="btn btn-info delete_selected" id="undo_popular"> Undo Popular Product
    </button>
@endif

@if(isset($undo_discontinued_stock_button) && $undo_discontinued_stock_button == 1)
    <button class="btn btn-info delete_selected" id="undo_discontinued_stock"> Undo Discontinued Stock
    </button>
@endif

@if(isset($addnew_route))
    <a class="btn btn-info delete_selected text-light" href="{{$addnew_route}}"> Add New
    </a>
@endif

@if(isset($mark_as_read) && $mark_as_read == 1)
    <button class="btn btn-info delete_selected text-light" id="mark_as_read"> Mark As Read
    </button>
@endif

@if(isset($mark_as_unread) && $mark_as_unread == 1)
    <button class="btn btn-info delete_selected text-light" id="mark_as_unread"> Mark As UnRead
    </button>
@endif

<a class="btn btn-info delete_selected text-light" href="{{$cache_delete_route}}"> Delete Cache
</a>
