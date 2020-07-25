<script>
    $(function () {
        $('a[data-toggle="tab"]').on('click', function (e) {
            window.localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = window.localStorage.getItem('activeTab');
        if (activeTab) {
            $('#category_tabs a[href="' + activeTab + '"]').tab('show');
            window.localStorage.removeItem("activeTab");
        }
    });
</script>

<script>
    var options = {
        filebrowserImageBrowseUrl: '{{config('app.url')}}/admin/filemanager?type=files',
        filebrowserImageUploadUrl: '{{config('app.url')}}/admin/filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: '{{config('app.url')}}/admin/filemanager?type=Files',
        filebrowserUploadUrl: '{{config('app.url')}}/admin/filemanager/upload?type=Files&_token='
    };
</script>
<script>
    CKEDITOR.replace('description', options);
</script>