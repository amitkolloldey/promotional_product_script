<script>
    var options = {
        filebrowserImageBrowseUrl: '{{config('app.url')}}/admin/filemanager?type=files',
        filebrowserImageUploadUrl: '{{config('app.url')}}/admin/filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: '{{config('app.url')}}/admin/filemanager?type=Files',
        filebrowserUploadUrl: '{{config('app.url')}}/admin/filemanager/upload?type=Files&_token='
    };
</script>
<script>
    CKEDITOR.replace('page_content', options);
</script>