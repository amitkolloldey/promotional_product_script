<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
    $('#loading_image').hide();
    $("#personalisation_option").change(function () {
        var product_id = $(this).find('option:selected').attr('data-pro_id');
        $('#loading_image').show();
        $.ajax({
            url: "{{ route('get_personalisation_color') }}?personalisation_type_id=" + this.value + "&product_id=" + product_id,
            method: 'GET',
            success: function (data) {
                $('#personalisation_color').html(data.personalisation_color_html);
            },
            complete: function () {
                $('#loading_image').hide();
            }
        });
    });

    $('input[name="quantity"], select[name="personalisation_color"], select[name="storage"]').change(function () {
        $('#loading_image').show();
        $('#order_btn').hide();
        if (Number($("#quantity").val()) < Number($("#min_quantity").val())) {
            swal({
                title: "Quantity Can not be Lower Then " + $("#min_quantity").val(),
                icon: "warning",
                buttons: true,
                dangerMode: true,
            });
            $("#quantity").val('');
        }

        $.ajax({
            url: "{{ route('get_pricing') }}?quantity=" + $("#quantity").val() + "&personalisation_color=" + $("#personalisation_color option:selected").val()  + "&storage=" + $("#storage option:selected").val() + "&product_id=" + $("#product_id").val(),
            method: 'GET',
            success: function (data) {
                $('#show_pricing').html(data.html);
                $('#loading_image').hide();
                $('#order_btn').show();
            },
            complete: function () {
                $('#loading_image').hide();
                $('#order_btn').show();
            }
        });
    });

    $(window).on("load", function () {
        if ($("#upload").prop("checked") == true) {
            $("#upload_inputs").show();
            $("#add_text_inputs").show();
        }
        else if($("#only_text").prop("checked") == true) {
            $("#add_text_inputs").show();
            $("#upload_inputs").hide();
        }else {
            $("#upload_inputs").hide();
            $("#add_text_inputs").hide();
        }
    });

    $('#upload').click(function () {
        $("#upload_inputs").show();
        $("#add_text_inputs").show();
    });

    $('#only_text').click(function () {
        $("#upload_inputs").hide();
        $("#add_text_inputs").show();
    });

    $('#no_artwork').click(function () {
        $("#upload_inputs").hide();
        $("#add_text_inputs").hide();
    });


    $(window).on("load", function () {
        if ($("#shipping_same_as_billing").prop("checked") == true) {
            $("#shipping_info").hide();
        }
    });

    $('#shipping_same_as_billing').click(function () {
        if ($(this).prop("checked") == true) {
            $("#shipping_info").hide();
        } else if ($(this).prop("checked") == false) {
            $("#shipping_info").show();
        }
    });

</script>
