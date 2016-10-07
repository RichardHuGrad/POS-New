$(document).ready(function () {
    $("#submit_button").click(function () {
        if ($.trim($("#source_pick_up_id").val()) == "") {
            $("#source_pick_up_id-error").html("Please select source point");
            $("#source_pick_up_id").parent('div').addClass('has-error');
            $("#source_pick_up_id").focus();
            return false;
        } else {
            $("#source_pick_up_id").parent('div').removeClass('has-error');
            $("#source_pick_up_id-error").html("");
        }

        if ($.trim($("#destination_pick_up_id").val()) == "") {
            $("#destination_pick_up_id-error").html("Please select destination point");
            $("#destination_pick_up_id").parent('div').addClass('has-error');
            $("#destination_pick_up_id").focus();
            return false;
        } else {
            $("#destination_pick_up_id").parent('div').removeClass('has-error');
            $("#destination_pick_up_id-error").html("");
        }       
        
        if ($.trim($("#destination_pick_up_id").val()) == $.trim($("#source_pick_up_id").val())) {
            $("#destination_pick_up_id-error").html("You can't add fare for same source and target points");
            $("#destination_pick_up_id").parent('div').addClass('has-error');
            $("#destination_pick_up_id").focus();
            return false;
        } else {
            $("#destination_pick_up_id").parent('div').removeClass('has-error');
            $("#destination_pick_up_id-error").html("");
        }   

        if ($.trim($("#fare").val()) == "") {
            $("#fare-error").html("Please specify fare");
            $("#fare").parent('div').addClass('has-error');
            $("#fare").focus();
            return false;
        } else {
            $("#fare").parent('div').removeClass('has-error');
            $("#fare-error").html("");
        }

    });
});