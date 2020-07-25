<script type="text/javascript">
    var i = {{count($personalisationoption->personalisationOptionValues)}}
    $("#add").click(function(){
        ++i;
        $("#dynamicvalue").append('<tr><td><input type="text" name="addoption['+i+'][value]" placeholder="Enter Option Value" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
    });
    $(document).on('click', '.remove-tr', function(){
        $(this).parents('tr').remove();
    });
    $(".remove_old_option").click(function () {
        var oid = $(this).data("oid");
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax(
            {
                url: "{{route('option_delete')}}?oid="+oid,
                type: 'DELETE',
                data: {
                    "oid": oid,
                    "_token": token,
                },
                success: function (data){
                    console.log(data.success);
                    location.reload();
                }
            });
    });
</script>