<script type="text/javascript">
    $('#loading_image').hide();
    $(document).on('click', '.nav li a', function () {
        $('#loading_image').show();
        $.ajax({
            url: "{{ route('view_product_pricing') }}?personalisation_type_id=" + $(this).attr('data-ptype_id')+"&product_slug="+$(this).attr('data-pro_slug'),
            method: 'GET',
            success: function (data) {
                $('#show_matrix').html(data.html);
            },
            complete: function(){
                $('#loading_image').hide();
            }
        });
    });
</script>
