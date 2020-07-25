@foreach($subcategories as $subcategory)
    @if(isset($category_parent))
        @if(old('parent_id') == $subcategory['id'])
            <optgroup>
                <option
                    value="{{$subcategory['id']}}" selected>{{$level ? $level : ""}}{{$subcategory['name']}}</option>
            </optgroup>
        @else
            <optgroup>
                <option
                    value="{{$subcategory['id']}}" {{($subcategory['id'] == $category_parent) ? "selected" : ""}}>{{$level ? $level : ""}}{{$subcategory['name']}}</option>
            </optgroup>
        @endif
    @else
        @if(old('parent_id') == $subcategory['id'])
            <optgroup>
                <option value="{{$subcategory['id']}}" selected>{{$level ? $level : ""}}{{$subcategory['name']}}</option>
            </optgroup>
        @else
            <optgroup>
                <option value="{{$subcategory['id']}}">{{$level ? $level : ""}}{{$subcategory['name']}}</option>
            </optgroup>
        @endif
    @endif
    @if(!empty($subcategory['sub_category']))
        @include('admin.includes.subcategory.all',['subcategories' => $subcategory['sub_category'], 'level' => " - - - "])
    @endif
@endforeach
