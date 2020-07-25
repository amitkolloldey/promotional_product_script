<div class="card">
    <div class="card-body">
        <div class="form-group">
            <label>Meta Title</label>
            <input name="meta_title" class="form-control" id="meta_title"
                   value="{{old('meta_title',isset($item['meta']['title']) ? $item['meta']['title'] : "")}}">
        </div>
        <div class="form-group">
            <label>Meta Keywords</label>
            <input name="meta_keywords" class="form-control"
                   value="{{old('meta_keywords',isset($item['meta']['keywords']) ? $item['meta']['keywords'] : "")}} ">
        </div>
        <div class="form-group">
            <label>Meta Description</label>
            <textarea name="meta_description" class="form-control" cols="30" rows="10"
                      id="meta_description">{{old('meta_description', isset($item['meta']['description']) ? $item['meta']['description'] : "")}}</textarea>
        </div>
    </div>
</div>
