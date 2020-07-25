<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(function() {
        $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('get_products') !!}',
            columns: [
                { data: 'check', name: 'check', orderable: false },
                { data: 'main_image', name: 'main_image' },
                { data: 'name', name: 'name' },
                { data: 'slug', name: 'slug' },
                { data: 'product_type', name: 'product_type' },
                { data: 'product_code', name: 'product_code' },
                { data: 'categories', name: 'categories' },
                { data: 'is_new', name: 'is_new' },
                { data: 'is_popular', name: 'is_popular' },
                { data: 'discontinued_stock', name: 'discontinued_stock' },
                { data: 'status', name: 'status' },
                { data: 'created_updated', name: 'created_updated' },
                { data: 'action', name: 'action' }
            ]
        });
    });
</script>

@if(isset($delete_button) && $delete_button == 1)
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function deleteData(id) {
            swal({
                title: "Are you sure you want to delete?",
                text: "Once clicked OK, the record will be permanently deleted!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{route($item_delete_route )}}" + '/' + id,
                            type: "POST",
                            data: {'_method': 'DELETE'},
                            success: function () {
                                location.reload();
                            },
                        })
                    }
                });
        }
    </script>
@endif

<script>
    @if(isset($delete_button) && $delete_button == 1)
    $("#delete_id").click(function () {
        var id = [];
        $('input:checkbox.check_id:checked').each(function () {
            id.push($(this).val());
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        swal({
            title: "Are you sure you want to delete?",
            text: "Once clicked OK, the record/s will be permanently deleted!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    swal({
                        title: "Deleting....",
                        text: "Please Wait While Deleting. Do Not Close The Window.",
                        buttons: false,
                        dangerMode: false,
                        closeModal: false,
                    });
                    $.ajax({
                        url: "{{route('selected_item_delete')}}",
                        type: 'DELETE',
                        data: {
                            "id": id,
                            "model_name": '{{$model_name}}',
                            "_token": $('meta[name=_token]').attr('content'),
                        },
                        success: function (data) {
                            $('#ajax_error').html(data.success);
                            location.reload();
                        }
                    })
                }
            });
    });
    @endif

    @if(isset($make_new_button) && $make_new_button == 1)
    $("#make_new").click(function () {
        var new_id = [];
        $('input:checkbox.check_id:checked').each(function () {
            new_id.push($(this).val());
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax(
            {
                url: "{{route('make_new')}}",
                type: 'POST',
                data: {
                    "new_id": new_id,
                    "model_name": '{{$model_name}}',
                    "_token": $('meta[name=_token]').attr('content')
                },
                success: function (data) {
                    $('#ajax_error').html(data.success);
                    window.location.reload();
                }
            });
    });
    @endif

    @if(isset($make_popular_button) && $make_popular_button == 1)
    $("#make_popular").click(function () {
        var popular_id = [];
        $('input:checkbox.check_id:checked').each(function () {
            popular_id.push($(this).val());
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax(
            {
                url: "{{route('make_popular')}}",
                type: 'POST',
                data: {
                    "popular_id": popular_id,
                    "model_name": '{{$model_name}}',
                    "_token": $('meta[name=_token]').attr('content')
                },
                success: function (data) {
                    $('#ajax_error').html(data.success);
                    window.location.reload();
                }
            });
    });
    @endif

    @if(isset($make_discontinued_stock_button) && $make_discontinued_stock_button == 1)
    $("#make_discontinued_stock").click(function () {
        var id = [];
        $('input:checkbox.check_id:checked').each(function () {
            id.push($(this).val());
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax(
            {
                url: "{{route('make_discontinued_stock')}}",
                type: 'POST',
                data: {
                    "id": id,
                    "model_name": '{{$model_name}}',
                    "_token": $('meta[name=_token]').attr('content')
                },
                success: function (data) {
                    $('#ajax_error').html(data.success);
                    window.location.reload();
                }
            });
    });
    @endif

    @if(isset($undo_new_button) && $undo_new_button == 1)
    $("#undo_new").click(function () {
        var new_id = [];
        $('input:checkbox.check_id:checked').each(function () {
            new_id.push($(this).val());
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax(
            {
                url: "{{route('undo_new')}}",
                type: 'POST',
                data: {
                    "new_id": new_id,
                    "model_name": '{{$model_name}}',
                    "_token": $('meta[name=_token]').attr('content')
                },
                success: function (data) {
                    $('#ajax_error').html(data.success);
                    window.location.reload();
                }
            });
    });
    @endif

    @if(isset($undo_popular_button) && $undo_popular_button == 1)
    $("#undo_popular").click(function () {
        var popular_id = [];
        $('input:checkbox.check_id:checked').each(function () {
            popular_id.push($(this).val());
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax(
            {
                url: "{{route('undo_popular')}}",
                type: 'POST',
                data: {
                    "popular_id": popular_id,
                    "model_name": '{{$model_name}}',
                    "_token": $('meta[name=_token]').attr('content')
                },
                success: function (data) {
                    $('#ajax_error').html(data.success);
                    window.location.reload();
                }
            });
    });
    @endif

    @if(isset($undo_discontinued_stock_button) && $undo_discontinued_stock_button == 1)
    $("#undo_discontinued_stock").click(function () {
        var id = [];
        $('input:checkbox.check_id:checked').each(function () {
            id.push($(this).val());
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax(
            {
                url: "{{route('undo_discontinued_stock')}}",
                type: 'POST',
                data: {
                    "id": id,
                    "model_name": '{{$model_name}}',
                    "_token": $('meta[name=_token]').attr('content')
                },
                success: function (data) {
                    $('#ajax_error').html(data.success);
                    window.location.reload();
                }
            });
    });
    @endif

    @if(isset($mark_as_read) && $mark_as_read == 1)
    $("#mark_as_read").click(function () {
        var id = [];
        $('input:checkbox.check_id:checked').each(function () {
            id.push($(this).val());
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax(
            {
                url: "{{route('mark_as_read')}}",
                type: 'POST',
                data: {
                    "id": id,
                    "model_name": '{{$model_name}}',
                    "_token": $('meta[name=_token]').attr('content')
                },
                success: function (data) {
                    $('#ajax_error').html(data.success);
                    window.location.reload();
                }
            });
    });
    @endif


    @if(isset($mark_as_unread) && $mark_as_unread == 1)
    $("#mark_as_unread").click(function () {
        var id = [];
        $('input:checkbox.check_id:checked').each(function () {
            id.push($(this).val());
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax(
            {
                url: "{{route('mark_as_unread')}}",
                type: 'POST',
                data: {
                    "id": id,
                    "model_name": '{{$model_name}}',
                    "_token": $('meta[name=_token]').attr('content')
                },
                success: function (data) {
                    $('#ajax_error').html(data.success);
                    window.location.reload();
                }
            });
    });
    @endif
</script>
