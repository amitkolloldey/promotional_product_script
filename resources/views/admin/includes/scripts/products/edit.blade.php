<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(function () {
        $('a[data-toggle="tab"]').on('click', function (e) {
            window.localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = window.localStorage.getItem('activeTab');
        if (activeTab) {
            $('#product_tabs a[href="' + activeTab + '"]').tab('show');
            window.localStorage.removeItem("activeTab");
        }
    });
</script>

<script type="text/javascript">
    $(".get_sub_sub_category").change(function () {
        $.ajax({
            url: "{{ route('get_sub_sub_categories') }}?category_id=" + $(this).val(),
            method: 'GET',
            success: function (data) {
                $('#sub_sub_category').html(data.sub_sub_category_html);
            }
        });
    });
</script>

<script type="text/javascript">
    $(".get_sub_category").change(function () {
        $('#sub_sub_category').html("");
        $.ajax({
            url: "{{ route('get_sub_categories') }}?category_id=" + $(this).val(),
            method: 'GET',
            success: function (data) {
                $('#sub_category').html(data.sub_category_html);
            }
        });
    });
</script>

<script type="text/javascript">
    $("select").change(function () {
        var product_id = '{{$product["id"]}}';
        var attr_id = $(this).children("option:selected").data('attr_id');
        var selected_primarycolor_id = $(this).children("option:selected").val();
        var form_data = new FormData();
        form_data.append("product_id", product_id);
        form_data.append("selected_primarycolor_id", selected_primarycolor_id);
        form_data.append("attr_id", attr_id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: "{{ route('attribute_primary_color_update') }}",
            data: form_data,
            contentType: false,
            processData: false,
            success: function (data) {
                location.reload();
            }
        });
    });
    $("#add").click(function () {
        var color = $("#attr_color").val();
        var image = $("#attr_image").prop("files")[0];
        var name = $("#attr_name").val();
        var description = $("#attr_description").val();
        var product_id = '{{$product["id"]}}';
        var manufacturer_key = '{{$product["manufacturer_key"]}}';
        console.log(manufacturer_id);
        var primarycolor_id = $("#primarycolor_id").children("option:selected").val();
        if (color === " " || image === undefined || name === " " || !primarycolor_id) {
            swal({
                title: "Please Fill All The Required Fields!",
                text: "Name and Image Fields Are Required!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
        } else {
            var form_data = new FormData();
            form_data.append("image", image);
            form_data.append("color", color);
            form_data.append("name", name);
            form_data.append("description", description);
            form_data.append("product_id", product_id);
            form_data.append("primarycolor_id", primarycolor_id);
            form_data.append("manufacturer_key", manufacturer_key);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            swal({
                title: "Please Save Other Options First!",
                text: "If you have Saved, then click on 'Yes, I Have Saved!' ",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "No",
                        value: null,
                        visible: true,
                        closeModal: true,
                    },
                    confirm: {
                        text: "Yes, I Have Saved!",
                        value: true,
                        visible: true,
                        closeModal: true
                    }
                },
                dangerMode: true
            })
                .then((willDelete) => {
                    if (willDelete) {

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('attribute_insert') }}",
                            data: form_data,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                console.log(data.success);
                                location.reload();
                            }
                        });
                    }
                });
        }
    });

    $(".remove").click(function () {
        var aid = $(this).data("aid");
        var pid = $(this).data("pid");
        var pcid = $(this).data("pcid");
        var mfid = $(this).data("mfid");
        var token = $("meta[name='csrf-token']").attr("content");
        swal({
            title: "Are you sure you want to Remove the Attribute!",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "No",
                    value: null,
                    visible: true,
                    closeModal: true,
                },
                confirm: {
                    text: "Yes",
                    value: true,
                    visible: true,
                    closeModal: true
                }
            },
            dangerMode: true
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax(
                    {
                        url: "{{route('attribute_delete')}}?aid=" + aid,
                        type: 'DELETE',
                        data: {
                            "aid": aid,
                            "pid": pid,
                            "pcid": pcid,
                            "mfid": mfid,
                            "_token": token,
                        },
                        success: function (data) {
                            console.log(data.success);
                            location.reload();
                        }
                     });
                }
            });
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
    CKEDITOR.replace('item_size', options);
    CKEDITOR.replace('short_desc', options);
    CKEDITOR.replace('product_features', options);
    CKEDITOR.replace('long_desc', options);
    CKEDITOR.replace('delivery_charges', options);
    CKEDITOR.replace('payment_terms', options);
    CKEDITOR.replace('return_policy', options);
    CKEDITOR.replace('disclaimer', options);
</script>
