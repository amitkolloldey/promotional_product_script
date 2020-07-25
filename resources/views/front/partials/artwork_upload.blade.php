<h2 class="checkout_heading">Artwork Upload</h2>
<div class="form-group">
    <label>Type</label><span class="required">*</span><br>
    <input type="radio" id="upload" name="type" value="upload" @if(old('type') == "upload") checked @endif>
    <label for="upload" data-toggle="tooltip"
           title="AI, EPS or PDF vector files are preferred. Text must be converted to outlines.">Upload Artwork</label><br>

    <input type="radio" id="only_text" name="type" value="only_text" @if(old('type') == "only_text") checked @endif>
    <label for="only_text" data-toggle="tooltip" title="Please provide both the text and font you need.">Text Only</label><br>

    <input type="radio" id="no_artwork" name="type" value="no_artwork" @if(old('type') == "no_artwork") checked @endif>
    <label for="no_artwork" data-toggle="tooltip"
           title="You can provide your Artwork Later.">None</label><br>
</div>

<div class="form-group" id="upload_inputs">
    <div class="form-group">
        <label for="images" data-toggle="tooltip" title="Max. file size 10MB">Select Files</label><br>
        <input type="file" name="images[]" multiple data-toggle="tooltip" title="Max. file size 10MB"><br>
        Or
        <br>
        <label for="drive_link">Drive Link</label>
        <input type="url" id="drive_link" name="drive_link" data-toggle="tooltip" title="For Larger Files Than 10MB Provide Drive Link Here." class="form-control" value="{{old('drive_link')}}">
    </div>
</div>
<div id="add_text_inputs">
    <div class="form-group">
        <label for="text_to_brand" data-toggle="tooltip"
               title="Write the text you want to use.">Text</label>
        <textarea name="text_to_brand" id="text_to_brand"
                  data-toggle="tooltip"
                  title="Write the text you want to use."
                  class="form-control">{{old('text_to_brand')}}</textarea>

        <label for="text_to_brand_font" data-toggle="tooltip"
               title="Write the name of the Font you want to use.">Font</label>
        <input type="text" name="text_to_brand_font"
               data-toggle="tooltip"
               title="Write the name of the Font you want to use. Ex: Arial"
               class="form-control" value="{{old('text_to_brand_font')}}">
    </div>
</div>
<div class="form-group">
    <label for="comment" data-toggle="tooltip"
           title="Any additional instruction For Artwork?">Comment</label>
    <textarea name="comment" id="comment" data-toggle="tooltip" title="Any additional instruction For Artwork?"
              class="form-control">{{old('comment')}}</textarea>
</div>