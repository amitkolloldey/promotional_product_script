<script type="text/javascript">

    var i = 0;

    $("#add").click(function () {

        ++i;

        $("#dynamicvalue").append('<tr><td><input type="text" name="addoption[' + i + '][value]" placeholder="Enter Option Value" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
    });

    $(document).on('click', '.remove-tr', function () {
        $(this).parents('tr').remove();
    });

</script>