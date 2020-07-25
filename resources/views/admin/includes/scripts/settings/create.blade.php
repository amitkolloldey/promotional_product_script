<script>
    var options = {
        filebrowserImageBrowseUrl: '{{url('/').'/'.config('lfm.url_prefix')}}?type=Images',
        filebrowserImageUploadUrl: '{{url('/').'/'.config('lfm.url_prefix')}}/upload?type=Images&_token=',
        filebrowserBrowseUrl: '{{url('/').'/'.config('lfm.url_prefix')}}?type=Files',
        filebrowserUploadUrl: '{{url('/').'/'.config('lfm.url_prefix')}}/upload?type=Files&_token=',
    };
</script>

<script>
    CKEDITOR.replace('delivery_charges', options);
    CKEDITOR.replace('payment_terms', options);
    CKEDITOR.replace('return_policy', options);
    CKEDITOR.replace('disclaimer', options);
</script>