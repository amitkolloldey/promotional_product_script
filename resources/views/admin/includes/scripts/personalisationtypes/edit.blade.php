<script>
    $(".custom-control-input").change(function () {
        $("#personalisation_submit").attr("disabled", true);
        $("#generate_message").html("<p class='text-danger'>Please generate the table with new options</p>");
    });
    function generatematrix() {
        $("#personalisation_submit").attr("disabled", false);
        $("#generate_message").html("");
        var ele = document.getElementById('personalisation_type').elements;
        var printing_agencies = "";
        var colors = "";
        var sizes = "";
        var positions = "";
        var color_id = "";
        var position_id = "";
        var size_id = "";
        for (var i = 0; i < ele.length; i++) {
            if (ele[i].type === "radio") {
                if (ele[i].name === "printingagency[]") {
                    if (ele[i].checked === true) {
                        if (printing_agencies !== "")
                            printing_agencies = printing_agencies + "," + ele[i].value;
                        else
                            printing_agencies = ele[i].value;
                    }
                }
            }
            if (ele[i].type === "checkbox") {
                if (ele[i].name === "printingagency[]") {
                    if (ele[i].checked === true) {
                        if (printing_agencies !== "")
                            printing_agencies = printing_agencies + "," + ele[i].value;
                        else
                            printing_agencies = ele[i].value;
                    }
                }
                if (ele[i].className.indexOf("color") !== -1) {
                    if (ele[i].checked === true) {
                        color_id = ele[i].getAttribute('data-color-id');
                        if (colors !== "") {
                            colors = colors + "," + ele[i].value;
                        } else
                            colors = ele[i].value;
                    }
                }
                if (ele[i].className.indexOf("position") !== -1) {
                    if (ele[i].checked === true) {
                        position_id = ele[i].getAttribute('data-position-id');
                        if (positions !== "") {
                            positions = positions + "," + ele[i].value;
                        } else
                            positions = ele[i].value;
                    }
                }
                if (ele[i].className.indexOf("size") !== -1) {
                    if (ele[i].checked === true) {
                        size_id = ele[i].getAttribute('data-size-id');
                        if (sizes !== "")
                            sizes = sizes + "," + ele[i].value;
                        else
                            sizes = ele[i].value;
                    }
                }
            }
        }
        if (printing_agencies === "") {
            alert('Please Select atleast one Printer');
            document.getElementById('add_matrix').innerHTML = "";
            return false;
        }
        if (sizes === "") {
            alert('Please Select atleast one Size/Type');
            document.getElementById('add_matrix').innerHTML = "";
            return false;
        }

        if (printing_agencies !== "" && sizes !== "") {
            $.ajax(
                {
                    url: "{{route('view_personalisation_type_pricing')}}",
                    type: 'GET',
                    data: {
                        "printing_agencies": printing_agencies,
                        "sizes": sizes,
                        "positions": positions,
                        "colors": colors,
                        "size_id": size_id,
                        "position_id": position_id,
                        "color_id": color_id,
                    },
                    success: function (data) {
                        console.log(data.success);
                        $('#add_matrix').html(data.success)
                    }
                });
        }
    }
</script>
