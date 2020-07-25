@foreach($subcategories as $subcategory)
    <optgroup>
        <option value="{{$subcategory->id}}" {{$subcategory->id == $category_parent ? "selected" : ""}}>{{$level ? $level : ""}}{{$subcategory->name}}</option>
        </li>
    </optgroup>
    @if(count($subcategory->subCategory))
        @include('admin.includes.subcategory.update',['subcategories' => $subcategory->subCategory,'level' => " --- ", 'category_parent' => $category_parent,'category_id'=>$category_id])
    @endif
@endforeach